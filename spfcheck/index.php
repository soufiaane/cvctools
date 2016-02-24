<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
} else {
    $sessionname = $_SESSION['name'];
}
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("../includes/inc.php"); ?>
</head>

<body>

<?php
include("../navbar-all.php");
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<form method="POST" action="script.php">
    <div class="row">
        <div class="col-md-3 col-md-offset-2">
            <center><h1 class="title">SPF Bulk Check Tool</h1></center>
            <br>
            <center><h3>Put your Domain names</h3></center>
            <textarea class="form-control" rows="20" name="dms"><?php if (isset($_SESSION['doms'])) {
                    echo $_SESSION['doms'];
                } ?></textarea>
            <br>
            <center>
                <button type="submit" class="btn btn-primary">Check SPF</button>
            </center>
        </div>
    </div>
</form>
</body>
</html>