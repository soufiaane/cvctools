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
    <form method="POST" action="script.php">
        <center><h1 class="title">Ip's Implode Tool</h1></center>
        <br>
        <center><h3>Put your Ip Adresses</h3></center>
        <center><font color="grey">(Each ip in new line)</font></center>
        <textarea class="form-control" rows="15" name="ips"></textarea>
        <br>
        <center>
            <button type="submit" class="btn btn-primary">Submit</button>
        </center>
    </form>
    <form enctype="multipart/form-data" action="script.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
        <center>Choose a file to upload: <input name="uploadedfile" type="file"/></center>
        <br/>
        <center><input type="submit" value="Upload File" class="btn btn-default"/></center>
    </form>
</div>
</body>
</html>