<?php
session_start();
session_destroy();
header("location:http://cvctools.com/login.php");
?>