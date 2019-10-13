<?php

    if (count(cbNewsletter_DIC::get("NLarchive")) < 1) {

        echo "<p>" . gettext("No newsletters have been written yet...") . "</p>\n";

    } else {

?>

      <ul>

<?php

  foreach (cbNewsletter_DIC::get("NLarchive") as $NLarchive) {

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

<?php

    }

    $backlink = assembleGetString("string", array("view" => ""));

?>

      <p><a href="<?php echo empty($backlink) ? "." : $backlink; ?>"><?php echo gettext("back"); ?></a></p>
