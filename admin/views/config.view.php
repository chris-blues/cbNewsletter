<?php

  $link = assembleGetString();

?>
    <div class="config shadow">

      <h2><?php echo gettext("Database settings"); ?></h2>

      <form action="index.php<?php echo $link; ?>" method="POST" accept-charset="utf-8">
        <table>
          <tr>
            <td>
              <label for="driver">driver</label>
            </td>
            <td class="input">
              <input type="text" id="driver" name="driver" value="<?php echo cbNewsletter_DIC::get("database")["driver"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="host">host</label>
            </td>
            <td class="input">
              <input type="text" id="host" name="host" value="<?php echo cbNewsletter_DIC::get("database")["host"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="name">name</label>
            </td>
            <td class="input">
              <input type="text" id="name" name="name" value="<?php echo cbNewsletter_DIC::get("database")["name"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="user">user</label>
            </td>
            <td class="input">
              <input type="text" id="user" name="user" value="<?php echo cbNewsletter_DIC::get("database")["user"]; ?>">
            </td>
          </tr>
          <tr>
            <td>
              <label for="pass">pass</label>
            </td>
            <td class="input">
              <input type="password" id="pass" name="pass" value="<?php echo cbNewsletter_DIC::get("database")["pass"]; ?>">
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


    <div class="config shadow">

      <h2><?php echo gettext("General settings"); ?></h2>

      <form action="index.php<?php echo $link; ?>" method="POST" accept-charset="utf-8">
        <table>
          <tr>
            <td>
              <label for="debug"><?php echo gettext("cbNewsletter debug output"); ?></label>
            </td>
            <td class="input">
              <input
                type="checkbox"
                id="debug"
                name="debug"
                <?php if (cbNewsletter_DIC::get("general")["debug"]) echo "checked"; ?>>
            </td>
          </tr>
          <tr>
            <td>
              <label for="debug_level">PHP debug level</label>
            </td>
            <td class="input">
              <select type="text" id="debug_level" name="debug_level" size="1">
<?php

  foreach (cbNewsletter_DIC::get("general")["debug_levels"] as $key => $value) {

?>
                <option value="<?php echo $key; ?>"<?php if($key == cbNewsletter_DIC::get("general")["debug_level"]) echo " selected"; ?>><?php echo $key; ?></option>
<?php

  }

?>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label for="show_processing_time"><?php echo gettext("show processing time"); ?></label>
            </td>
            <td class="input">
              <input
                type="checkbox"
                id="show_processing_time"
                name="show_processing_time"
                <?php if (cbNewsletter_DIC::get("general")["show_processing_time"]) echo "checked"; ?>>
            </td>
          </tr>
          <tr>
            <td>
              <label for="language"><?php echo gettext("default language"); ?></label>
            </td>
            <td class="input">
              <input
                type="text"
                id="language"
                name="language"
                value="<?php echo cbNewsletter_DIC::get("general")["language"]; ?>"
                placeholder="<?php echo $locale; ?>">
            </td>
          </tr>
        </table>

        <input type="hidden" name="job" value="update_generalSettings">

        <div class="center">
          <button type="reset"><?php echo gettext("Reset"); ?></button>
          <button type="send"><?php echo gettext("Save"); ?></button>
        </div>
      </form>
    </div>



    <div class="clear"></div>
