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

  /**
   * @var int
   */
  protected $todayCount;

  public function __construct($total_count, $today_count, $timestamp) {
    $this->totalCount = (int) $total_count;
    $this->todayCount = (int) $today_count;
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
   * Today number of times the entity has been viewed.
   *
   * @return int
   */
  public function getTodayCount(): int {
    return $this->todayCount;
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
