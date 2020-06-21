<?php
session_start();
unset($_SESSION['Username']);
header("location:http://localhost/project2/src/login.php");
?>