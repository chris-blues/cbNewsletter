<?php

class QueryBuilder {

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

  public function add_subscriber($subscriber) {

    $statement = $this->Database->prepare(
      "INSERT INTO `cbNewsletter_subscribers`
        (`name`, `email`, `hash`, `verified`)
        VALUES
        (:name, :email, :hash, 0) ;"
    );

    $statement->bindParam(':name', $subscriber["name"]);
    $statement->bindParam(':email', $subscriber["email"]);
    $statement->bindParam(':hash', $subscriber["hash"]);

    $result = $this->callExecution($statement);

    return $result;

  }


  public function removeSubscription($id) {

    $statement = $this->Database->prepare(
      "DELETE FROM `cbNewsletter_subscribers`
       WHERE `id` = :id ;"
    );

    $statement->bindParam(':id', $id);

    $result = $this->callExecution($statement);

    return $result;

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

}

?>
