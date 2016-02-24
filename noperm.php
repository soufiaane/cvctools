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
<div class="col-md-4 col-md-offset-3">
    <div class="alert alert-danger" id="full-alert" role="alert">
        <center><b>Error !</b> You don't have permission to access this page. <span class="glyphicon glyphicon-lock"
                                                                                    aria-hidden="true"></span></center>
    </div>
</div>
</body>
</html>