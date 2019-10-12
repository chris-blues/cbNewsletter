<?php

class cbNewsletter_QueryBuilder {

  protected $Database;

  public function __construct(PDO $Database) {
    $this->Database = $Database;
  }

  private function callExecution($statement) {
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      $error["Database"]["callPDOexecution"] = gettext("The PDO call returned an error");
//       die($e->getMessage());
      return($e->getMessage());
    }
    return $result;
  }


  public function show_archive($id = null) {

    if ($id == null) {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_archive` ORDER BY `date` DESC ;"
      );

    } else {

      $statement = $this->Database->prepare(
        "SELECT * FROM `cbNewsletter_archive`
         WHERE `id` = :id ;"
      );

      $statement->bindParam(':id', $id);

    }

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "NLarchive");

  }


  public function add_subscriber($subscriber) {

    $statement = $this->Database->prepare(
      "INSERT INTO `cbNewsletter_subscribers`
        (`name`, `email`, `hash`, `subscribed`, `verified`, `locale`)
        VALUES
        (:name, :email, :hash, :subscribed, 0, :locale) ;"
    );

    $statement->bindParam(':name', $subscriber["name"]);
    $statement->bindParam(':email', $subscriber["email"]);
    $statement->bindParam(':hash', $subscriber["hash"]);
    $statement->bindParam(':subscribed', $subscriber["subscribed"]);
    $statement->bindParam(':locale', $subscriber["locale"]);

    $result = $this->callExecution($statement);

    return $result;

  }


  public function removeSubscription($id) {

    $statement = $this->Database->prepare(
      "DELETE FROM `cbNewsletter_subscribers`
       WHERE `id` = :id ;"
    );

    $statement->bindParam(':id', $id);

    return $this->callExecution($statement);

  }

  public function remove_unverified_subsciptions() {

    $timespan = time() - (30 * 24 * 60 * 60); // 30 days in seconds

    $statement = $this->Database->prepare(
      "DELETE FROM `cbNewsletter_subscribers`
       WHERE `subscribed` < :timespan AND `verified` = '0';"
    );

    $statement->bindParam(':timespan', $timespan, PDO::PARAM_INT);

    $result = $this->callExecution($statement);

    if ($result) return $statement->rowCount();
    else return false;

  }

  public function check_existing($email) {

    $statement = $this->Database->prepare(
      "SELECT COUNT(*) FROM `cbNewsletter_subscribers`
       WHERE `email` = :email ;"
    );

    $statement->bindParam(':email', $email);

    $result = $this->callExecution($statement);

    $tmp = $statement->fetchAll();

    return intval($tmp[0][0]);

  }

  public function check_subscription($id, $hash) {

    $statement = $this->Database->prepare(
      "SELECT COUNT(*) FROM `cbNewsletter_subscribers`
       WHERE ( `id` = :id AND `hash` = :hash ) ;"
    );

    $statement->bindParam(':id', $id);
    $statement->bindParam(':hash', $hash);

    $result = $this->callExecution($statement);

    $tmp = $statement->fetchAll();

    return intval($tmp[0][0]);

  }

  public function check_already_verified($id, $hash) {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_subscribers`
       WHERE ( `id` = :id AND `hash` = :hash ) ;"
    );

    $statement->bindParam(':id', $id);
    $statement->bindParam(':hash', $hash);

    $result = $this->callExecution($statement);

    $result = $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

    $data = $result[0]->getdata();

    if (intval($data["verified"]) == 1) return true;
    else return false;

  }

  public function verify_subscription($id, $hash) {

    $statement = $this->Database->prepare(
      "UPDATE `cbNewsletter_subscribers`
       SET `verified` = '1'
       WHERE ( `id` = :id AND `hash` = :hash ) ;"
    );

    $statement->bindParam(':id', $id);
    $statement->bindParam(':hash', $hash);

    $result = $this->callExecution($statement);

    return $result;

  }

  public function getSubscriberData($id) {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_subscribers`
       WHERE `id` = :id ;"
    );

    $statement->bindParam(':id', $id);

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

  }

  public function getSubscriberByEmail($email) {

    $statement = $this->Database->prepare(
      "SELECT * FROM `cbNewsletter_subscribers`
       WHERE `email` = :email ;"
    );

    $statement->bindParam(':email', $email);

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_CLASS, "Subscriber");

  }

  public function getSubscribersId($hash) {

    $statement = $this->Database->prepare(
      "SELECT `id` FROM `cbNewsletter_subscribers`
       WHERE `hash` = :hash ;"
    );

    $statement->bindParam(':hash', $hash);

    $result = $this->callExecution($statement);

    return $statement->fetchAll(PDO::FETCH_COLUMN, 0);

  }

  public function updateSubscribersEmail($id, $email) {

    $statement = $this->Database->prepare(
      "UPDATE `cbNewsletter_subscribers`
       SET `email` = :email, `verified` = 0
       WHERE ( `id` = :id ) ;"
    );

    $statement->bindParam(':email', $email);
    $statement->bindParam(':id', $id);

    $result = $this->callExecution($statement);

    return $result;

  }

  public function updateSubscribersName($id, $name) {

    $statement = $this->Database->prepare(
      "UPDATE `cbNewsletter_subscribers`
       SET `name` = :name
       WHERE ( `id` = :id ) ;"
    );

    $statement->bindParam(':name', $name);
    $statement->bindParam(':id', $id);

    $result = $this->callExecution($statement);

    return $result;

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

    $cbNewsletter_HTML = new cbNewsletter_HTML;
    $cbNewsletter_HTML->infobox(sprintf(gettext("Database table %s has been optimized."), $name));

    return $result;

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

    $time = time();

    $statement->bindParam(':optimized', $time);
    $statement->bindParam(':name', $name);

    $result = $this->callExecution($statement);

    return $result;

  }

}

?>
