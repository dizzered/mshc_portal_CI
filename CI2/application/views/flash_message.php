<?php
$message = json_decode($general_flash_message);
?>
<div class="flash_message <?php echo $message->type; ?> <?php echo isset($message->class) ? $message->class : ''; ?>">
<p><?php echo $message->text; ?></p>
</div>