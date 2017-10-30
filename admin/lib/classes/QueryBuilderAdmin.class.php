<?php

class QueryBuilderAdmin extends QueryBuilder {

    private function callExecution($statement) {

    try {
      $result = $statement->execute();

    } catch(PDOException $e) {
      $error["Database"]["callPDOexecution"] = gettext("The PDO call returned an error: ") . $e->getMessage();
//       die($e->getMessage());
      echo $e->getMessage() . "<br>\n";
      return($error);
    }

    return $result;
  }

  public function get_verified_subscribers() {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_subscribers`
        WHERE `verified` = '1' ;"
    );

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

  }

  public function get_subscribers($filter = "", $order = "name") {

    if ($filter == "") {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_subscribers`
         ORDER BY `$order` ASC;"
      );

    } else {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_subscribers`
        WHERE `name`  REGEXP :filter
           OR `email` REGEXP :filter
        ORDER BY `name` ASC ;"
      );

      $statement->bindParam(':filter', $filter);

    }

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

  }

  public function get_subscriber($email) {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_subscribers`
        WHERE `email` = :email ;"
    );

    $statement->bindParam(':email', $email);

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

  }

  public function create_missing_tables() {

    $statement = $this->Database->prepare("SHOW TABLES LIKE 'cbNewsletter_%' ;");

    $result = $this->callExecution($statement);

    $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);


    $tablenames = array("cbNewsletter_subscribers", "cbNewsletter_archiv", "cbNewsletter_maintenance");

    if (count($result) < count($tablenames)) {

      foreach($tablenames as $name) {

        if (strlen($name) < 1) continue;

        if (!in_array($name, $result)) {

          $list[] = $name;
          $init[$name] = $this->{"init_$name"}();

        }

      }

      $HTML = new HTML;

      echo $HTML->infobox(gettext("Created database tables:") . "\n" . $HTML->ul($list));

      return $init;

    }

  }

  private function populate_cbNewsletter_maintenance() {

    $time = time();

    $tables = $this->get_table_names();

    foreach ($tables as $name) {
      $statement = $this->Database->prepare(
       "INSERT INTO `cbNewsletter_maintenance`
          (`name`, `ctime`, `optimized`)
        VALUES
          (:name, :time, :time) ;"
      );

      $statement->bindParam(':name', $name);
      $statement->bindParam(':time', $time);

      $result[$name] = $this->callExecution($statement);

    }

    return $result;

  }

  private function init_cbNewsletter_maintenance() {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_maintenance` (
        `name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
        `ctime` INT UNSIGNED NOT NULL,
        `optimized` INT UNSIGNED NOT NULL
      )
      ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_general_ci ;"
    );

    $result["createTable"] = $this->callExecution($statement);

    $result["populateTable"] = $this->populate_cbNewsletter_maintenance();

    return $result;

  }

  private function init_cbNewsletter_subscribers() {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_subscribers` (
        `id` INT UNSIGNED NULL AUTO_INCREMENT ,
        `name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `email` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `locale` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `subscribed` INT NOT NULL ,
        `verified` BOOLEAN NOT NULL ,
        `hash` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        PRIMARY KEY (`id`),
        FULLTEXT(`name`,`email`)
      )
      ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;"
    );

    $result = $this->callExecution($statement);

    return $result;

  }

  private function init_cbNewsletter_archiv () {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_archiv` (
        `id` INT UNSIGNED NULL AUTO_INCREMENT ,
        `date` INT UNSIGNED NOT NULL ,
        `subject` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        PRIMARY KEY (`id`),
        FULLTEXT (`subject`,`text`)
      )
      ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;"
    );

    $result = $this->callExecution($statement);

    return $result;

  }

}

?>
