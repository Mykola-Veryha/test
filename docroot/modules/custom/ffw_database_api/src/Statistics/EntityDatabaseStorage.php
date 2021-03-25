<?php

namespace Drupal\ffw_database_api\Statistics;

use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides the default database storage backend for statistics.
 */
class EntityDatabaseStorage implements StorageInterface {

  /**
  * The database connection used.
  *
  * @var \Drupal\Core\Database\Connection
  */
  protected $connection;

  /**
   * The current user object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;


  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs the statistics storage.
   */
  public function __construct(Connection $connection, AccountInterface $current_user, RequestStack $request_stack) {
    $this->connection = $connection;
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function recordLastView(int $id): bool {
    try {
      return (bool) $this->connection
        ->merge('ffw_entity_last_view')
        ->condition('entity_id', $id)
        ->condition('uid', $this->currentUser->id())
        ->fields([
          'entity_id' => $id,
          'uid' => $this->currentUser->id(),
          'timestamp' => $this->getRequestTime(),
        ])
        ->execute();
    }
    catch (Exception $e) {
      watchdog_exception('ffw_database_api', $e);

      return FALSE;
    }
  }


  /**
   * {@inheritdoc}
   */
  public function recordView($id): bool {
    try {
      return (bool) $this->connection
        ->merge('ffw_entity_views_counter')
        ->key('entity_id', $id)
        ->fields([
          'totalcount' => 1,
          'todaycount' => 1,
          'timestamp' => $this->getRequestTime(),
        ])
        ->expression('totalcount', 'totalcount + 1')
        ->expression('todaycount', 'todaycount + 1')
        ->execute();
    }
    catch (Exception $e) {
      watchdog_exception('ffw_database_api', $e);

      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function fetchViews($ids): array {
    $views = $this->connection
      ->select('ffw_entity_views_counter', 'vc')
      ->fields('vc', ['totalcount', 'todaycount', 'timestamp'])
      ->condition('entity_id', $ids, 'IN')
      ->execute()
      ->fetchAll();
    foreach ($views as $id => $view) {
      $views[$id] = new ViewsResult($view->totalcount, $view->todaycount, $view->timestamp);
    }

    return $views;
  }

  /**
   * {@inheritdoc}
   */
  public function fetchView($id) {
    $views = $this->fetchViews([$id]);

    return reset($views);
  }

  /**
   * {@inheritdoc}
   */
  public function fetchLastViews(array $ids, int $uid = NULL): array {
    $query = $this->connection->select('ffw_entity_last_view', 'lv');
    $query->fields('lv', ['uid', 'timestamp']);
    $query->condition('entity_id', $ids, 'IN');
    if ($uid !== NULL) {
      $query->condition('uid', $uid);
    }
    $views = $query->execute()->fetchAll();
    foreach ($views as $id => $view) {
      $views[$id] = new LastViewsResult($view->uid, $view->timestamp);
    }

    return $views;
  }

  /**
   * {@inheritdoc}
   */
  public function fetchLastView(int $id, int $uid = NULL) {
    $views = $this->fetchLastViews([$id], $uid);

    return reset($views);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteViews($id): bool {
    return (bool) $this->connection
      ->delete('ffw_entity_views_counter')
      ->condition('nid', $id)
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteLastViews($id): bool {
    return (bool) $this->connection
      ->delete('ffw_entity_views_counter')
      ->condition('nid', $id)
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function maxTotalCount(): int {
    $query = $this->connection->select('ffw_entity_views_counter', 'nc');
    $query->addExpression('MAX(totalcount)');

    return (int) $query->execute()->fetchField();
  }

  /**
   * Get current request time.
   *
   * @return int
   *   Unix timestamp for current server request time.
   */
  protected function getRequestTime(): int {
    return $this->requestStack->getCurrentRequest()->server->get('REQUEST_TIME');
  }

}
