<?php
$dsn = 'mysql:host=localhost;dbname=c0cvctools';
$username = 'c0cvcadmin';
$password = 'wl8B#1JXnc';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
$conn = new PDO($dsn, $username, $password, $options);
?>
 