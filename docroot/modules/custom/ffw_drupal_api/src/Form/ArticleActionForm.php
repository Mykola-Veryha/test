<?php

namespace Drupal\ffw_drupal_api\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleActionForm extends FormBase {

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @noinspection PhpParamsInspection
   */
  public static function create(ContainerInterface $container): ArticleActionForm {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ffw_article_action_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['article'] = [
      '#type' => 'select',
      '#title' => t('Articles'),
      '#options' => $this->articles(),
      '#required' => TRUE,
    ];

    $form['article_publish_status'] = [
      '#type' => 'select',
      '#options' => [
        NodeInterface::PUBLISHED => $this->t('Published'),
        NodeInterface::NOT_PUBLISHED => $this->t('Not published'),
      ],
    ];

    $form['article_sticky_status'] = [
      '#type' => 'select',
      '#options' => [
        NodeInterface::STICKY => $this->t('Sticky'),
        NodeInterface::NOT_STICKY => $this->t('Not Sticky'),
      ],
    ];

    $form['update'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update'),
      '#submit' => [
        [$this, 'updateButtonSubmit']
      ],
    ];
    $form['delete'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#submit' => [
        [$this, 'deleteButtonSubmit']
      ],
    ];

    return $form;
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function updateButtonSubmit(array &$form, FormStateInterface $form_state) {
    $atricle_nid = $form_state->getValue('article');
    $publish_status = (bool) $form_state->getValue('article_publish_status');
    $sticky_status =  (bool) $form_state->getValue('article_sticky_status');

    $node = $this->nodeStorage->load($atricle_nid);
    $node->set('status', $publish_status);
    $node->set('sticky', $sticky_status);
    $node->save();
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function deleteButtonSubmit(array &$form, FormStateInterface $form_state) {
    $atricle_nid = $form_state->getValue('article');
    $node = $this->nodeStorage->load($atricle_nid);
    $node->delete();
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  public function articles(): array {
    $values = $this->nodeStorage->getAggregateQuery()
      ->condition('type', 'article')
      ->groupBy('title')
      ->groupBy('nid')
      ->execute();

    return array_column($values, 'title', 'nid');
  }

}
