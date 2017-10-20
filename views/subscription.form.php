      <h3><?php echo gettext("Subscribe to our newsletter:"); ?></h3>

      <form
        action="<?php echo assembleGetString("string", array("view" => "enter_subscription")); ?>"
        id="cbNewsletter_subscription"
        accept-charset="utf-8"
        method="POST"
      >
        <label for="name"><?php echo gettext("Name"); ?></label><br>
          <input
            type="text"
            id="name"
            name="name"
            placeholder="<?php echo gettext("Name"); ?>"
            value="<?php if (isset($data["name"])) echo $data["name"]; ?>"
            required
	  ><br>
	<label for="email" id="cbNewsletter_email_label" class="hidden">Email</label>
	  <input type="email" id="cbNewsletter_email" name="email" class="hidden">
        <label for="address"><?php echo gettext("Email"); ?></label><br>
          <input
            type="email"
            id="address"
            name="address"
            placeholder="<?php echo gettext("you@example.com"); ?>"
            value="<?php if (isset($email)) echo $email; ?>"
            required
	  ><br>
	<button type="reset"><?php echo gettext("Reset"); ?></button>
	<button type="submit"><?php echo gettext("Submit"); ?></button><br>
      </form>
