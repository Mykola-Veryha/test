<?php

namespace Drupal\ffw_database_api\Statistics;

/**
 * Value object for passing statistic results.
 */
class LastViewsResult {

  /**
   * @var int
   */
  protected $uid;

  /**
   * @var int
   */
  protected $timestamp;

  public function __construct(int $uid, int $timestamp) {
    $this->uid = $uid;
    $this->timestamp = $timestamp;
  }

  /**
   * The ID of the user that has been viewed the entity.
   *
   * @return int
   */
  public function getUid(): int {
    return $this->uid;
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
