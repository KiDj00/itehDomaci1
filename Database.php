<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

class Database {
  private $host = "localhost"; 
  private $db_name = "minerals"; 
  private $username = "root"; 
  private $password = ""; 

  private static $instance = null; 
  public $connection = null; 

  private function __construct() {
    $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
  }

  public function getConnection() {
    return $this->connection;
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Database();
    }

    return self::$instance;
  }
  public function insertMineral($props) {
    $props = (object) $props;
    $query = "INSERT INTO mineral (username, title, locality, price, img) VALUES ('$props->username', '$props->title', '$props->locality', $props->price, '$props->img')";
    $result = mysqli_query($this->getConnection(), $query) or die(mysqli_error($this->getConnection()));
    if ($result) {
      return true;
    }

    return false;
  }

  public function getAllMinerals() {
    $query = "SELECT * FROM mineral";
    $result = mysqli_query($this->getConnection(), $query);
    $minerals = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $minerals;
  }

  public function getAllMineralsSorted($sortType) {
    $query = "SELECT * FROM mineral ORDER BY $sortType";
    $result = mysqli_query($this->getConnection(), $query);
    $minerals = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $minerals;
  }
  

  public function searchMinerals($searchText) {
    $query = "SELECT * FROM mineral WHERE title LIKE '%$searchText%'";
    $result = mysqli_query($this->getConnection(), $query);
    $minerals = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $minerals;
  }

  public function getMineral($id) {
    $query = "SELECT * FROM mineral WHERE id = $id";
    $result = mysqli_query($this->getConnection(), $query);
    $mineral = $result->fetch_assoc();

    return $mineral;
  }

  public function updateMineral($props, $id) {
    $props = (object) $props;
    $query = "UPDATE mineral SET title = '$props->title', locality = '$props->locality', price = $props->price, img = '$props->img' WHERE id = $id LIMIT 1";
    $result = mysqli_query($this->getConnection(), $query) or die(mysqli_error($this->getConnection()));

    return $result;
  }

  public function deleteMineral($id) {
    $query = "DELETE FROM mineral WHERE id = $id LIMIT 1";
    $result = mysqli_query($this->getConnection(), $query);

    return $result;
  }

  public function insertComment($props) {
    $props = (object) $props;
    $query = "INSERT INTO comment (username, content, mineral_id) VALUES ('$props->username', '$props->content', '$props->mineral_id')";
    $result = mysqli_query($this->getConnection(), $query) or die(mysqli_error($this->getConnection()));

    if ($result) {
      return true;
    }

    return false;
  }

  public function getAllComments() {
    $query = "SELECT c.*, m.title FROM comment c JOIN mineral m ON m.id = c.mineral_id";
    $result = mysqli_query($this->getConnection(), $query);
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $comments;
  }

  public function getAllCommentsForMineral($id) {
    $query = "SELECT * FROM comment WHERE mineral_id = $id";
    $result = mysqli_query($this->getConnection(), $query);
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $comments;
  }
}
