<?php

namespace Drupal\soon\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dynamodb_client\DynamoDb;

/**
 * Configure soon settings for this site.
 */
class SoonSettingsForm extends ConfigFormBase {

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

    $connection = DynamoDb::database();
    $params = [
      'TableName' => 'PAGE_TABLE',
      'Key' => [
        'PAGE_ID' => ['N' => '001'],
        'PAGE_TITLE' => ['S' => 'The website coming soon...'],
      ],
      'ProjectionExpression' => 'PAGE_DESCRIPTION, START_DATE',
    ];

    $response = $connection->getItem($params);
    if ($response) {
      $params_update = [
        'TableName' => 'PAGE_TABLE',
        'Item' => [
          'PAGE_ID' => [
            'N' => '001',
          ],
          'PAGE_TITLE' => [
            'S' => $title,
          ],
          'PAGE_DESCRIPTION' => [
            'S' => $description,
          ],
          'START_DATE' => [
            'S' => $start_date,
          ],
        ],
      ];
      $result = $connection->putItem($params_update);
    }

    parent::submitForm($form, $form_state);
  }

}
