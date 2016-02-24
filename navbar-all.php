<?php
$dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
if ($dbconn) {
    include("navbar.php");
    if (isset($_SESSION['name'])) {
        include("navbar-username.php");
        include("navbar-menu.php");
    }
    include("navbar-end.php");
}
?>