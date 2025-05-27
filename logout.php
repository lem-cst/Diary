<?php
session_start();
session_unset();
session_destroy();
header("Location: love2.php");
exit();
?>