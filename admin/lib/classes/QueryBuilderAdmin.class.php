<?php

class cbNewsletter_QueryBuilderAdmin extends cbNewsletter_QueryBuilder {

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

  public function get_templates() {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_templates`
       ORDER BY `name` ;"
    );

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Template");

  }

  public function add_template($data) {

    $statement = $this->Database->prepare(
      "INSERT INTO `cbNewsletter_templates`
       (`name`, `subject`, `text`)
       VALUES
       (:name, :subject, :text) ;"
    );

    $statement->bindParam(':name',    $data["name"]);
    $statement->bindParam(':subject', $data["subject"]);
    $statement->bindParam(':text',    $data["text"]);

    return $this->callExecution($statement);

  }

  public function delete_template($id) {

    $statement = $this->Database->prepare(
      "DELETE FROM `cbNewsletter_templates`
       WHERE `id` = :id ;"
    );

    $statement->bindParam(':id', $id);

    return $this->callExecution($statement);

  }



  public function store_newsletter($subject, $text) {

    $statement = $this->Database->prepare(
      "INSERT INTO `cbNewsletter_archive`
       (`date`, `subject`, `text`)
       VALUES
       (:date, :subject, :text) ;"
    );

    $date = time();
    $statement->bindParam(':date',    $date);
    $statement->bindParam(':subject', $subject);
    $statement->bindParam(':text',    $text);

    return $this->callExecution($statement);

  }



  public function create_missing_tables() {

    global $cbNewsletter_Debugout, $cbNewsletter_HTML;

    $statement = $this->Database->prepare("SHOW TABLES LIKE 'cbNewsletter_%' ;");

    $result = $this->callExecution($statement);

    $result = $statement->fetchAll(PDO::FETCH_COLUMN, 0);


    $tablenames = array("cbNewsletter_subscribers", "cbNewsletter_archive", "cbNewsletter_templates", "cbNewsletter_maintenance");

    if (count($result) < count($tablenames)) {

      foreach($tablenames as $name) {

        if (strlen($name) < 1) continue;

        if (!in_array($name, $result)) {

          $list[] = $name;
          $init[$name] = $this->{"init_$name"}();

          $cbNewsletter_Debugout->add(
            "created missing table " . $name,
            ($init[$name]) ? "OK" : "FAILED"
          );

        }

      }

      echo $cbNewsletter_HTML->infobox(gettext("Created database tables:") . "\n" . $cbNewsletter_HTML->ul($list), "notice");



      foreach ($init as $table => $result) {

        if ($table != "cbNewsletter_maintenance") {

          $result = $this->insert_maintenance_data($table);

          $cbNewsletter_Debugout->add(
            "added maintenance data of table " . $table,
            ($result) ? "OK" : "FAILED"
          );

        }

      }

      return $init;

    }

  }

  public function insert_maintenance_data($table) {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_maintenance`
       WHERE `name` = :name ;"
    );

    $statement->bindParam(':name', $table);

    $result = $this->callExecution($statement);

    $result = $statement->fetchAll();



    if (count($result) != 0) {

      $statement = $this->Database->prepare(
        "UPDATE `cbNewsletter_maintenance`
         SET `ctime` = :ctime, `optimized` = :ctime
         WHERE `name` = :name ;"
      );

    } else {

      $statement = $this->Database->prepare(
        "INSERT INTO `cbNewsletter_maintenance`
         (`name`, `ctime`, `optimized`)
         VALUES
         (:name, :ctime, :ctime) ;"
      );

    }

    $statement->bindParam(':ctime', time());
    $statement->bindParam(':name', $table);

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
      ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_general_ci ;"
    );

    $result["createTable"] = $this->callExecution($statement);

    $result["populateTable"] = $this->populate_cbNewsletter_maintenance();

    return $result;

  }

  private function init_cbNewsletter_templates() {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_templates` (
        `id` INT UNSIGNED NULL AUTO_INCREMENT ,
        `name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `subject` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
        PRIMARY KEY (`id`)
      )
      ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_general_ci ;

      INSERT INTO `cbNewsletter_templates`
      (`name`, `subject`, `text`)
      VALUES
      ('default', '" . $_SERVER["SERVER_NAME"] . " newsletter', 'Hello %name%,\n\nthis is a newsletter from %server% .\n\nBest regards,\nyour newsletter team\n\nYou will find an unsubscription link below:\n') ;"
    );

    $result = $this->callExecution($statement);

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

  private function init_cbNewsletter_archive () {

    $statement = $this->Database->prepare(
      "CREATE TABLE IF NOT EXISTS `cbNewsletter_archive` (
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
