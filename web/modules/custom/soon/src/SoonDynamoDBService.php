<?php

namespace Drupal\soon;

use Drupal\dynamodb_client\DynamoDb;

class SoonDynamoDBService {

  /**
   * @var \Drupal\dynamodb_client\Connection
   */
  protected $connection;

  /**
   * Constructs an DynamoDB service.
   */
  public function __construct() {
    $this->connection = DynamoDb::database();
  }

  /**
   * @param string $title
   * @param string $description
   * @param string $start_date
   *
   * @return void
   */
  public function saveData(string $title, string $description, string $start_date) {
    $this->connection->putItem([$title, $description, $start_date]);
  }

}
