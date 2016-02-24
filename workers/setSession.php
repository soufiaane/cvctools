<?php
session_start();
$_SESSION["lvl"] = $_GET["lvl"];
$_SESSION["id"] = $_GET["id"];
$_SESSION["name"] = $_GET["name"];
header("Location: time_table.php");
?>