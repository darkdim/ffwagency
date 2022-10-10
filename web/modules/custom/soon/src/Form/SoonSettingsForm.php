<?php

namespace Drupal\soon\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dynamodb_client\DynamoDb;
use Drupal\soon\SoonDynamoDBService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure soon settings for this site.
 */
class SoonSettingsForm extends ConfigFormBase {

  /**
   * @var \Drupal\soon\SoonDynamoDBService
   */
  protected $dynamoDB;

  /**
   * @param \Drupal\soon\SoonDynamoDBService $dynamoDBService
   */
  public function __construct(SoonDynamoDBService $dynamoDBService) {
    $this->dynamoDB = $dynamoDBService;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\Core\Form\ConfigFormBase|\Drupal\soon\Form\SoonSettingsForm|static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('soon.dynamodb_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'soon_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['soon.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('soon.settings');
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('title'),
      '#description' => $this->t('Enter a title for this page.'),
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $config->get('description'),
      '#description' => $this->t('Enter a description for this page.'),
    ];
    $start_date = $config->get('start_date');
    $default_value = isset($start_date) ? new DrupalDateTime($config->get('start_date')) : new DrupalDateTime('2023-01-01 00:00:00');
    $form['start_date'] = [
      '#type' => 'datetime',
      '#date_date_element' => 'date',
      '#date_time_element' => 'none',
      '#default_value' => $default_value,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $description = $form_state->getValue('description');
    $start_date = (string) $form_state->getValue('start_date');
    $this->config('soon.settings')
      ->set('title', $title)
      ->set('description', $description)
      ->set('start_date', $start_date)
      ->save();
    $this->dynamoDB->saveData($title, $description, $start_date);

    parent::submitForm($form, $form_state);
  }

}
