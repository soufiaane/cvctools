<?php
session_start();
$name = $_SESSION['name'];
$dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
if ($dbconn) {
    $sql = "SELECT * FROM workers WHERE name='$name'";
    $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
    $data = mysqli_fetch_assoc($result);
    $_SESSION["lvl"] = $data["level"];
    $_SESSION["id"] = $data["id"];
    $_SESSION["name"] = $data["name"];
    header("Location: http://cvctools.com/workers/time_table.php");
}
?>