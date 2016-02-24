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
<div class="row">
    <div class="col-md-2">
        <?php include("../sidebar.php"); ?>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="col-md-4"></div>
                <center><h1 class="title">SPF Search</h1></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <br>
                <center><h3>Search Request<font color="red" size="4">*</font></h3><h5><font color="grey"><i>SQL
                                Format</i></font></h5></center>
                <form method="POST" action="spfsearch.php">
                    <label for="req" class="col-md-2 control-label">Request:</label>

                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="Request" name="req">
                    </div>
                    <br><br><br>
                    <?php
                    if ($_SESSION['name'] === 'Bendris Abderrahim') {
                        echo '<label for="req2" class="col-md-2 control-label">Request2:</label>';
                        echo '<div class="col-md-10">';
                        echo '<input type="text" class="form-control" placeholder="Request" name="req2">';
                        echo '</div>';
                        echo '<br><br><br>';
                    }
                    ?>
                    <center>
                        <button type="submit" class="btn btn-primary">Start searching <span
                                class="glyphicon glyphicon-search"></span></button>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>