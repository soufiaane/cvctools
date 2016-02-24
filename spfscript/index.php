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
$id = rand();
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">SPF Script</h1></center>
    <br>

    <form enctype="multipart/form-data" action="script.php" method="POST" name="form" onsubmit="">
        <center>Choose a file to upload: <input name="uploadedfile" type="file"/></center>
        <br/>
        <center>
            <button type="submit" class="btn btn-default" formaction="script.php">Run Searche <span
                    class="glyphicon glyphicon-search"></span></button>
        </center>
        <br>
        <center>
            <button type="submit" class="btn btn-danger" formaction="control.php">Run Synchronisation <span
                    class="glyphicon glyphicon-search"></span></button>
        </center>
    </form>
</div>
</body>
</html>