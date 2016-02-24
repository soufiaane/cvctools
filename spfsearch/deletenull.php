<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:/login.php");
} else {
    $sessionname = $_SESSION['name'];
}
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("/includes/inc.php"); ?>
</head>
<body>
<?php include("/navbar-all.php");
?>
<div class="col-md-2">
    <?php include("/sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">Database script</h1></center>
    <br><br>
</div>
<div class="col-md-8">
    <?php
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    $sql = "select *, count(*) as `NUM_DUPES` from `spf` group by `record`, `domain`, `uteam` ORDER BY `NUM_DUPES` DESC LIMIT 10000";
    $dupes = mysqli_query($dbconn, $sql);
    if ($dupes) {
        foreach ($dupes as $dupe) {
            $drec = $dupe['record'];
            $ddom = $dupe['domain'];
            $dteam = $dupe['uteam'];
            $dnum = $dupe['NUM_DUPES'] - 1;
            $sql2 = "delete from spf where record = '$drec' and domain = '$ddom' and uteam = '$dteam' limit $dnum";
            $result = mysqli_query($dbconn, $sql2);
            if ($result) {
                if ($dnum > 0) {
                    echo 'duplicate deleted<br>';
                }
            } else {
                die (mysqli_error($dbconn));
            }
        }
        echo 'completed<br><br><br>';
    } else {
        die(mysqli_error($dbconn));
    }


    $sql = "SET @count = 0;";
    $sql .= "UPDATE spf SET id = @count:= @count + 1";
    if (mysqli_multi_query($dbconn, $sql)) {
        echo 'Unique id\'s updated<br><br><br>';
    } else {
        die(mysqli_error($dbconn));
    }
    ?>
</div>

</body>
</html>
