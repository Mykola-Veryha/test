<?php

namespace Drupal\ffw_database_api\Render;

use Drupal\ffw_database_api\Statistics\EntityDatabaseStorage;

class NodeStatistics {

  /**
   * The statistics storage.
   *
   * @var \Drupal\ffw_database_api\Statistics\EntityDatabaseStorage
   */
  private $statisticsStorage;

  public function __construct(EntityDatabaseStorage $statistics_storage) {
    $this->statisticsStorage = $statistics_storage;
  }

  public function build(int $nid) {
    return [
      '#theme' => 'ffw_node_view_statistics',
      '#today' => 23,
      '#total' => 23,
      '#last_view_date' => date('d/m/Y H:s'),
      '#username' => 23,
    ];
  }

}
