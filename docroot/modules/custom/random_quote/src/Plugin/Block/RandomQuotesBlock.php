<?php

namespace Drupal\random_quote\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\random_quote\Service\RandomQuotes;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to view a specific entity.
 *
 * @Block(
 *   id = "ffw_random_quotes_block",
 *   admin_label = @Translation("Random Quotes Block")
 * )
 */
class RandomQuotesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Generate the random quote.
   *
   * @var \Drupal\random_quote\Service\RandomQuotes
   */
  private $randomQuotes;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RandomQuotes $random_quotes
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->randomQuotes = $random_quotes;
  }

  /**
   * {@inheritdoc}
   *
   * @noinspection PhpParamsInspection
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('random_quote.client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#markup' => $this->randomQuotes->randomQuote(),
    ];
  }

}
