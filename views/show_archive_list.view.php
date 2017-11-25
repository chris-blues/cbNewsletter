
      <ul>

<?php

  foreach (DIC::get("NLarchive") as $NLarchive) {

    $NL = $NLarchive->getdata();

?>
        <li class="NLarchive">
          <a href="<?php echo assembleGetString("string", array("id" => $NL["id"])); ?>">
            <?php
              echo date("Y-m-d", $NL["date"]) . " - " . $NL["subject"];
            ?>
          </a>
        </li>
<?php

  }

?>

      </ul>
