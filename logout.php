<?php
session_start();
$_SESSION = array();
header("Location: /");
exit;
session_destroy();
?>