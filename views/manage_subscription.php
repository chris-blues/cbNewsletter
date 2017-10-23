
    <div class="shadow">
      <h4><?php echo gettext("Change your subscription"); ?></h4>
      <form action="<?php echo $link; ?>&amp;job=update#cbNewsletter_mainBox" method="POST" accept-charset="utf-8">
        <label for="username"><?php echo gettext("Your name:"); ?></label><br>
        <input
          id="username"
          type="text"
          name="name"
          value="<?php if (isset($db_data["name"])) echo $db_data["name"]; ?>"
          placeholder="<?php echo gettext("your name"); ?>"><br>

        <label for="useremail"><?php echo gettext("Your email address:"); ?></label><br>
        <input
          id="useremail"
          type="email"
          name="new_email"
          value="<?php if (isset($db_data["email"])) echo $db_data["email"]; ?>"
          placeholder="<?php echo gettext("you@example.com"); ?>"><br>

<?php

  foreach ($post as $key => $value) {

?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php

  }

?>
        <button type="reset"><?php echo gettext("reset"); ?></button>
        <button type="submit"><?php echo gettext("submit"); ?></button>
      </form>
    </div>





    <div class="shadow">
      <h4><?php echo gettext("Unsubscribe"); ?></h4>
      <form action="<?php echo $link; ?>&amp;job=unsubscribe#cbNewsletter_mainBox" method="POST" accept-charset="utf-8">
        <label><?php echo gettext("Really unsubscribe me") ?>
          <input type="checkbox" id="unsubscribe" name="agree" value="agree" required>
	</label>
<?php

  foreach ($post as $key => $value) {

?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php

  }

?>
        <button type="submit"><?php echo gettext("OK"); ?></button>
      </form>
    </div>
