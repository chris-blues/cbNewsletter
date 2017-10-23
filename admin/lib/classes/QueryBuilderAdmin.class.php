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

  public function get_subscribers($filter = "", $order = "email") {

    if ($filter == "") {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_subscribers`
         ORDER BY `$order` ASC;"
      );

    } else {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_subscribers`
        WHERE MATCH ( `name`, `email` )
        AGAINST ( :filter IN NATURAL LANGUAGE MODE )
        ORDER BY `$order` ASC ;"
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

  public function get_maintenance_data() {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_maintenance`;"
    );

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Maintenance");

  }


  public function optimize_table($name) {

    $statement = $this->Database->prepare("OPTIMIZE TABLE `$name`;");

    $result = $this->callExecution($statement);

    if ($result) $this->update_maintenance_table($name);

    $HTML = new HTML;
    $HTML->infobox(sprintf(gettext("Database table %s has been optimized."), $name));

    return $result;

  }


  public function create_missing_tables() {

    $statement = $this->Database->prepare("SHOW TABLES LIKE 'cbNewsletter_%' ;");

    $result = $this->callExecution($statement);

    $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);



    if (count($result) < 3) {

      $tablenames = array("cbNewsletter_subscribers", "cbNewsletter_archiv", "cbNewsletter_maintenance");

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

  public function get_table_names() {

    $statement = $this->Database->prepare(
        "SHOW TABLES LIKE 'cbNewsletter_%' ;"
      );

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_COLUMN, 0);

  }

  private function update_maintenance_table($name) {

    $statement = $this->Database->prepare(
      " UPDATE `cbNewsletter_maintenance`
        SET `optimized` = :optimized
        WHERE `name` = :name ;"
    );

    $statement->bindParam(':optimized', time());
    $statement->bindParam(':name', $name);

    $result = $this->callExecution($statement);

    return $result;

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
      ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci ;"
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
        `verified` BOOLEAN NOT NULL,
        `hash` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        PRIMARY KEY (`id`)
      )
      ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

      ALTER TABLE `cbNewsletter_subscribers` ADD FULLTEXT KEY `index_subscribers` (`name`,`email`);"
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
        PRIMARY KEY (`id`)
      )
      ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

      ALTER TABLE `cbNewsletter_archiv` ADD FULLTEXT KEY `index_archiv` (`subject`,`text`);"
    );

    $result = $this->callExecution($statement);

    return $result;

  }

}

?>
