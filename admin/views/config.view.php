<?php

  $link = assembleGetString("string", array());

?>
    <div class="config shadow">

      <h2><?php echo gettext("Database settings"); ?></h2>

      <form action="index.php<?php echo $link; ?>" method="POST" accept-charset="utf-8">
        <table>
          <tr>
            <td>
              <label for="driver">driver</label>
            </td>
            <td>
              <input type="text" id="driver" name="driver" value="<?php echo $cbNewsletter["config"]["database"]["driver"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="host">host</label>
            </td>
            <td>
              <input type="text" id="host" name="host" value="<?php echo $cbNewsletter["config"]["database"]["host"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="name">name</label>
            </td>
            <td>
              <input type="text" id="name" name="name" value="<?php echo $cbNewsletter["config"]["database"]["name"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="user">user</label>
            </td>
            <td>
              <input type="text" id="user" name="user" value="<?php echo $cbNewsletter["config"]["database"]["user"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="pass">pass</label>
            </td>
            <td>
              <input type="password" id="pass" name="pass" value="<?php echo $cbNewsletter["config"]["database"]["pass"]; ?>">
            </td>
          </tr>
        </table>

        <input type="hidden" name="job" value="update_dbSettings">

        <div class="center">
          <button type="reset"><?php echo gettext("Reset"); ?></button>
          <button type="send"><?php echo gettext("Save"); ?></button>
        </div>
      </form>
    </div>
