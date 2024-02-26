<?php
session_start();
session_unset();
session_destroy();

header('location:components/admin_login.php');
exit;
?>
