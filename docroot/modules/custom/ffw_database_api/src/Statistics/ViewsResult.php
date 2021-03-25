<?php

namespace Drupal\ffw_database_api\Statistics;

/**
 * Value object for passing statistic results.
 */
class ViewsResult {

  /**
   * @var int
   */
  protected $totalCount;

  /**
   * @var int
   */
  protected $timestamp;

  public function __construct($total_count, $timestamp) {
    $this->totalCount = (int) $total_count;
    $this->timestamp = (int) $timestamp;
  }

  /**
   * Total number of times the entity has been viewed.
   *
   * @return int
   */
  public function getTotalCount(): int {
    return $this->totalCount;
  }

  /**
   * Timestamp of when the entity was last viewed.
   *
   * @return int
   */
  public function getTimestamp(): int {
    return $this->timestamp;
  }

}
