<?php
  require_once('conn.php');

  setcookie("user_id", "", time()+3600*24);

  session_start();
  session_unset();
  session_destroy();

  header('Location: ./index.php?page=1');
?>