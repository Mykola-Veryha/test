<?php

namespace Drupal\ffw_database_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\ffw_database_api\Render\NodeStatistics;
use Drupal\ffw_database_api\Statistics\EntityDatabaseStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class StatisticsController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * The statistics storage.
   *
   * @var \Drupal\ffw_database_api\Statistics\EntityDatabaseStorage
   */
  private $statisticsStorage;

  /**
   * Render the statistics.
   *
   * @var \Drupal\ffw_database_api\Render\NodeStatistics
   */
  private $statisticsRender;

  public function __construct(
    RequestStack $request_stack,
    EntityDatabaseStorage $statistics_storage,
    NodeStatistics $statistics_render
  ) {
    $this->currentRequest = $request_stack->getCurrentRequest();
    $this->statisticsStorage = $statistics_storage;
    $this->statisticsRender = $statistics_render;
  }

  /**
   * @noinspection PhpParamsInspection
   */
  public static function create(ContainerInterface $container): StatisticsController {
    return new static(
      $container->get('request_stack'),
      $container->get('ffw_database_api.statistics_storage'),
      $container->get('ffw_database_api.build_statistics')
    );
  }

  public function countView(): JsonResponse {
    $nid = (int) $this->currentRequest->request->get('nid');
    if (!empty($nid)) {
      $this->statisticsStorage->recordLastView($nid);
      $this->statisticsStorage->recordView($nid);

      return new JsonResponse([
        'success' => 1,
      ]);
    }

    return new JsonResponse([
      'error' => 'The netity ID is empty.',
    ]);
  }

  public function loadStatistics(int $entity_id): JsonResponse  {
    if (empty($entity_id)) {
      return new JsonResponse([
        'data' => '',
      ]);
    }
    $build = $this->statisticsRender->build($entity_id);

    return new JsonResponse([
      'data' => render($build),
    ]);
  }

}
