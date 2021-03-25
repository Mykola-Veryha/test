<?php

namespace Drupal\ffw_database_api\Statistics;

/**
 * Provides an interface defining Statistics Storage.
 *
 * Stores the views per day, total views and timestamp of last view
 * for entities.
 */
interface StorageInterface {

  /**
   * Counts an entity view.
   *
   * @param int $id
   *   The ID of the entity to count.
   *
   * @return bool
   *   TRUE if the entity view has been counted.
   */
  public function recordView(int $id): bool;

  /**
   * The most recent time the entity has been viewed by the current user.
   *
   * @param int $id
   *   The ID of the entity to count.
   *
   * @return bool
   *   TRUE if the entity last view has been counted.
   */
  public function recordLastView(int $id): bool;

  /**
   * Returns the number of times entities have been viewed.
   *
   * @param array $ids
   *   An array of IDs of entities to fetch the views for.
   *
   * @return \Drupal\statistics\StatisticsViewsResult[]
   *   An array of value objects representing the number of times each entity
   *   has been viewed. The array is keyed by entity ID. If an ID does not
   *   exist, it will not be present in the array.
   */
  public function fetchViews(array $ids): array;

  /**
   * Returns the number of times a single entity has been viewed.
   *
   * @param int $id
   *   The ID of the entity to fetch the views for.
   *
   * @return \Drupal\ffw_database_api\Statistics\ViewsResult|false
   *   If the entity exists, a value object representing the number of times if
   *   has been viewed. If it does not exist, FALSE is returned.
   */
  public function fetchView(int $id);

  /**
   * Returns the user ID and his last view time of entities have been viewed.
   *
   * @param array $ids
   *   An array of IDs of entities to fetch the views for.
   * @param int $uid
   *   A user ID that viewed the entities
   *
   * @return \Drupal\ffw_database_api\Statistics\LastViewsResult[]
   *   An array of value objects representing the ID of user
   *   and his last view time of entities have been viewed.
   */
  public function fetchLastViews(array $ids, int $uid = NULL): array;

  /**
   * eturns the user ID and his last view time of entity have been viewed.
   *
   * @param int $id
   *   The ID of the entity to fetch the views for.
   * @param int $uid
   *   A user ID that viewed the entities
   *
   * @return \Drupal\ffw_database_api\Statistics\LastViewsResult|false
   *   An array of value objects representing the ID of user
   *   and his last view time of entity have been viewed.
   */
  public function fetchLastView(int $id);

  /**
   * Delete counts for a specific entity.
   *
   * @param int $id
   *   The ID of the entity which views to delete.
   *
   * @return bool
   *   TRUE if the entity views have been deleted.
   */
  public function deleteViews(int $id): bool;

  /**
   * Delete last views for a specific entity.
   *
   * @param int $id
   *   The ID of the entity which last views to delete.
   *
   * @return bool
   *   TRUE if the entity last views have been deleted.
   */
  public function deleteLastViews(int $id): bool;

  /**
   * Returns the highest 'totalcount' value.
   *
   * @return int
   *   The highest 'totalcount' value.
   */
  public function maxTotalCount(): int;

}
