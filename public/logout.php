<?php
session_start();
include_once ('../private/conn.php');
session_unset();
session_destroy();
header('location:index.php');
?>