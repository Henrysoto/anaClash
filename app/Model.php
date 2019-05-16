<?php

abstract class Model {
  protected $dbh;
  protected $stmt;

  public function __construct() {
    $this->dbh = new PDO("sqlite:". DB_FULL_NAME, null, null);
  }

  public function query($queryParam) {
    $this->stmt = $this->dbh->query($queryParam);
  }

  public function prepare($prepareParam) {
    $this->stmt = $this->dbh->prepare($prepareParam);
  }

  public function bind($bindParam, $value, $type = null) {
    if ( is_null($type) ) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
          break;
      }

      $this->stmt->bindValue($bindParam, $value, $type);
    }
  }

  public function execute() {
    return $this->stmt->execute();
  }

  public function lastInsertId() {
    return $this->dbh->lastInsertId();
  }

  public function single() {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function resultSet() {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

 ?>
