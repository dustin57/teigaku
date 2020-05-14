<?php

namespace MyApp;

class Subscriptions {
  private $_db;

  public function __construct() {
    $this->_createToken();

    try {
      $this->_dbConnect();
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }

  private function _createToken() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  private function _dbConnect() {
    $db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
    $db['dbname'] = ltrim($db['path'], '/');
    $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
    $user = $db['user'];
    $password = $db['pass'];
    $options = array(
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY =>true,
    );
    $this->_db = new \PDO($dsn,$user,$password,$options);
    return $this->_db;
  }

  public function getAll() {
    $stmt = $this->_db->query("select * from list order by id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getSum() {
    $stmt = $this->_db->query("select sum(price) from list");
    return $stmt->fetchColumn();
  }

  public function getAvg() {
    $stmt = $this->_db->query("select avg(price) from list");
    return $stmt->fetchColumn();
  }

  public function getCount() {
    $stmt = $this->_db->query("select count(price) from list");
    return $stmt->fetchColumn();
  }

  public function post() {
    $this->_validateToken();

    if (!isset($_POST['mode'])) {
      throw new \Exception('mode not set!');
    }
    switch ($_POST['mode']) {
      case 'create':
        return $this->_create();
      case 'delete':
        return $this->_delete();
    }
  }

  private function _validateToken() {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !== $_POST['token']
    ) {
      throw new \Exception('invalid token!');
    }
  }

  private function _create() {
    if (!isset($_POST['title']) || $_POST['title'] === '') {
      throw new \Exception('[create] title not set!');
    }
    if (!isset($_POST['price']) || $_POST['price'] === '') {
      throw new \Exception('[create] price not set!');
    }

    $sql = "insert into list (title, price) values (:title, :price)";
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([':title' => $_POST['title'], ':price' => $_POST['price']]);

    return [
      'id' => $this->_db->lastInsertId(),
      'sum' => number_format((int)$this->getSum()),
      'count' => number_format((int)$this->getCount()),
      'avg' => number_format((int)$this->getAvg()),
    ];

  }

  private function _delete() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[delete] id not set!');
    }

    $sql = sprintf("delete from list where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    return [
      'sum' => $this->getSum(),
      'count' => number_format((int)$this->getCount()),
      'avg' => number_format((int)$this->getAvg()),
    ];

  }


}
