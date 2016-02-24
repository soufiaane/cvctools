<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:http://cvctools.com/login.php");
} else {
    $sessionname = $_SESSION['name'];
}
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("includes/inc.php"); ?>
</head>

<body>
<?php
include("navbar-all.php");
?>
<div class="col-md-2">
    <br>
    <?php include("sidebar.php"); ?>
</div>
<div class="img-index">
    <img src="http://cvctools.com/img/logo.gif">
</div>
</body>
</html>