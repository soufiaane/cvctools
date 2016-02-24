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
<div class="row">
    <div class="col-md-2">
        <?php include("../sidebar.php"); ?>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <center><h1 class="title">Mailboxes bulk checker</h1></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <br>
                <center><h3>Put your Emails and passwords</h3><h5><font color="grey"><i>(Email,Password)</i></font></h5>
                </center>
                <form method="POST" action="script.php?id=<?php echo $id ?>">
                    <textarea class="form-control" rows="15" name="data"></textarea>
                    <center><font color='grey'><h5>Allowed isp's:
                                yahoo,sbcglobal,centurylink,hotmail,aol,gmail,comcast,att,btinternet,gmx
                                ,verizon,roadrunner,sky,bellsouth,prodigy,icloud,earthlink,mail</h5></font></center>
                    <br><br>
                    <center>
                        <button type="submit" class="btn btn-primary">Check mailboxes</button>
                    </center>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>

</body>
</html>