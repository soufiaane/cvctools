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
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">Ip neighbors</h1></center>
    <br>

    <form method="POST" action="script.php">
        <center><h3>Put your Ip Adresses</h3></center>
        <center><font color="grey">(Each ip in new line)</font></center>
        <textarea class="form-control" rows="15" name="ips"></textarea>
        <br>
        <label for="cidr" class="col-md-3 control-label">CIDR:</label>

        <div class="col-md-7">
            <label class="radio-inline"><input type="radio" name="octet" value="1">/8</label>
            <label class="radio-inline"><input type="radio" name="octet" value="2">/16</label>
            <label class="radio-inline"><input type="radio" name="octet" value="3">/24</label>
        </div>
        <div class="col-md-2"><font color="red" size="2">*</font></div>
        <br><br>
        <center>
            <button type="submit" class="btn btn-primary">Start searching <span
                    class="glyphicon glyphicon-search"></span></button>
        </center>
    </form>
</div>
<?php
$sql = "SELECT p_ipcheck_admin, p_teamleader, team, id FROM users WHERE fullname='$sessionname'";
$result = mysqli_query($dbconn, $sql);
$data = mysqli_fetch_assoc($result);
mysqli_free_result($result);
echo '<div class="col-md-2 col-md-offset-3">';
if ($data['p_ipcheck_admin'] == 1 || $data['id'] == 53) {
    ?>
    <br><br>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Administration Panel</h3>
        </div>
        <div class="panel-body">
            <?php if ($data['p_ipcheck_admin'] == 1) { ?>
                <a href="addip.php" class="btn btn-default btn-sm btn-block" role="button">Add ip's to database <span
                        class="glyphicon glyphicon-import"></span></a>
                <br>
                <a href="deleteip.php" class="btn btn-default btn-sm btn-block" role="button">Delete ip's from database
                    <span class="glyphicon glyphicon-trash"></span></a>
                <br>
            <?php } ?>
            <a href="deletenull.php" class="btn btn-default btn-sm btn-block" role="button">Optimisation of Database
                <span class="glyphicon glyphicon-check"></span></a>
        </div>
    </div>
<?php
};
echo '<br><br><br>';
echo '<div class="well">';
if ($data['p_ipcheck_admin'] == 1) {
    echo '<center><button class="btn btn-default" type="button">';
    $sql = "SELECT * FROM ipneighbor";
    $result = mysqli_query($dbconn, $sql);
    $num = mysqli_num_rows($result);
    echo 'Records in database <span class="badge">' . $num . '</span>';
    mysqli_free_result($result);
    echo '</button></center>';
}
if ($data['p_ipcheck_admin'] == 1 || $data['team'] == 'OPM16 Yahoo Team1') {
    echo '<center><button class="btn btn-default" type="button">';
    $sql = "SELECT * FROM ipneighbor WHERE uteam='OPM16 Yahoo Team1'";
    $result = mysqli_query($dbconn, $sql);
    $num = mysqli_num_rows($result);
    echo 'OPM16 Yahoo Team1 <span class="badge">' . $num . '</span>';
    mysqli_free_result($result);
    echo '</button></center>';
    if ($data['p_ipcheck_admin'] == 1 || $data['p_teamleader'] == 1) {
        echo '<center><a href="showall.php?tid=opmya1">Get all records</a></center>';
    }
}
if ($data['p_ipcheck_admin'] == 1 || $data['team'] == 'CVC Yahoo Team1') {
    echo '<center><button class="btn btn-default" type="button">';
    $sql = "SELECT * FROM ipneighbor WHERE uteam='CVC Yahoo Team1'";
    $result = mysqli_query($dbconn, $sql);
    $num = mysqli_num_rows($result);
    echo 'CVC Yahoo Team1 <span class="badge">' . $num . '</span>';
    mysqli_free_result($result);
    echo '</button></center>';
    if ($data['p_ipcheck_admin'] == 1 || $data['p_teamleader'] == 1) {
        echo '<center><a href="showall.php?tid=cvcya1">Get all records</a></center>';
    }
}
if ($data['p_ipcheck_admin'] == 1 || $data['team'] == 'CVC Yahoo Team2') {
    echo '<center><button class="btn btn-default" type="button">';
    $sql = "SELECT * FROM ipneighbor WHERE uteam='CVC Yahoo Team2'";
    $result = mysqli_query($dbconn, $sql);
    $num = mysqli_num_rows($result);
    echo 'CVC Yahoo Team2 <span class="badge">' . $num . '</span>';
    mysqli_free_result($result);
    echo '</button></center>';
    if ($data['p_ipcheck_admin'] == 1 || $data['p_teamleader'] == 1) {
        echo '<center><a href="showall.php?tid=cvcya2">Get all records</a></center>';
    }
}
if ($data['p_ipcheck_admin'] == 1 || $data['team'] == 'CVC Yahoo Team3') {
    echo '<center><button class="btn btn-default" type="button">';
    $sql = "SELECT * FROM ipneighbor WHERE uteam='CVC Yahoo Team3'";
    $result = mysqli_query($dbconn, $sql);
    $num = mysqli_num_rows($result);
    echo 'CVC Yahoo Team3 <span class="badge">' . $num . '</span>';
    mysqli_free_result($result);
    echo '</button></center>';
    if ($data['p_ipcheck_admin'] == 1 || $data['p_teamleader'] == 1) {
        echo '<center><a href="showall.php?tid=cvcya3">Get all records</a></center>';
    }
}
?>
</div>
</div>
</body>
</html>