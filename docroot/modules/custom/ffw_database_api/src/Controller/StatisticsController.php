<?php

namespace Drupal\ffw_database_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
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

  public function __construct(
    RequestStack $request_stack,
    EntityDatabaseStorage $statistics_storage
  ) {
    $this->currentRequest = $request_stack->getCurrentRequest();
    $this->statisticsStorage = $statistics_storage;
  }

  /**
   * @noinspection PhpParamsInspection
   */
  public static function create(ContainerInterface $container): StatisticsController {
    return new static(
      $container->get('request_stack'),
      $container->get('ffw_database_api.statistics_storage')
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

}
