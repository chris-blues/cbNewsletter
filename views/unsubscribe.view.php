    <div class="cbNewsletter_info shadow">

      <p><?php echo gettext("Or are you trying to unsubscribe or something else?"); ?></p>

      <form action="<?php echo assembleGetString("string", array("view" => "")); ?>" method="POST" accept-charset="UTF-8">

        <input type="hidden" name="email" value="<?php echo $_POST["address"]; ?>">
        <input type="hidden" name="job" value="resend_verification_mail">
        <button type="submit"><?php echo gettext("Resend verification mail"); ?></button>

      </form>
    </div>
