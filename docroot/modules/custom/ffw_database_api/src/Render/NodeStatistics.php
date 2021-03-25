<?php

namespace Drupal\ffw_database_api\Render;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ffw_database_api\Statistics\EntityDatabaseStorage;
use Drupal\ffw_database_api\Statistics\LastViewsResult;
use Drupal\ffw_database_api\Statistics\ViewsResult;

class NodeStatistics {

  /**
   * The statistics storage.
   *
   * @var \Drupal\ffw_database_api\Statistics\EntityDatabaseStorage
   */
  private $statisticsStorage;

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * The current logged in user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    EntityDatabaseStorage $statistics_storage,
    EntityTypeManagerInterface $entity_type_manager,
    $current_user
  ) {
    $this->statisticsStorage = $statistics_storage;
    $this->userStorage = $entity_type_manager->getStorage('user');
    $this->currentUser = $current_user;
  }

  public function build(int $nid): array {
    $view_result = $this->statisticsStorage->fetchView($nid);
    $last_view_result = $this->statisticsStorage->fetchLastView($nid, $this->currentUser->id());

    if ($view_result instanceof ViewsResult && $last_view_result instanceof LastViewsResult) {
      $last_datetime = new DrupalDateTime();
      $last_datetime->setTimestamp($last_view_result->getTimestamp());
      $last_datetime->setTimezone(new \DateTimeZone($this->currentUser->getTimeZone()));

      return [
        '#theme' => 'ffw_node_view_statistics',
        '#today' => $view_result->getTodayCount(),
        '#total' => $view_result->getTotalCount(),
        '#last_view_date' => $last_datetime->format('d.m.y H:i'),
        '#username' => $this->getUserName($last_view_result->getUid()),
        '#nid' => $nid,
      ];
    }

    return [];
  }

  private function getUserName(int $uid): string {
    $values = $this->userStorage->getAggregateQuery()
      ->condition('uid', $uid)
      ->groupBy('name')
      ->execute();

    $user_values = reset($values);

    return $user_values['name'] ?? '';
  }

}
