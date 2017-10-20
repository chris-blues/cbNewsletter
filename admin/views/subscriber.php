<?php
  $getString = assembleGetString(
                 "string",
                 array(
                   "job" => "deleteUser",
                   "id" => $subscriber["id"],
		  )
	        );
?>

      <tr>
        <td><?php echo $subscriber["id"]; ?></td>
        <td><?php echo $subscriber["name"]; ?></td>
        <td><?php echo $subscriber["email"]; ?></td>
        <td>
          <div class="center">
            <?php
              if ($subscriber["verified"]) echo "<span class=\"green\">✔</span>";
	    ?>
	  </div>
	</td>
        <td>
          <div class="center">
            <a href="<?php echo $getString; ?>"> ☠ </a>
          </div>
        </td>
      </tr>
