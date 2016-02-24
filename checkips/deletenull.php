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
<?php include("../navbar-all.php");
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">Database script</h1></center>
    <br><br>
</div>
<div class="col-md-8">
    <?php
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    $sql = "SELECT * FROM ipneighbor ORDER BY ip, email ASC LIMIT 5000";
    $result = mysqli_query($dbconn, $sql);
    while ($data = mysqli_fetch_assoc($result)) {
        if ($data['ip'] == null || $data['email'] == null) {
            $id = $data['id'];
            $sql2 = "DELETE FROM ipneighbor WHERE id='$id'";
            $result2 = mysqli_query($dbconn, $sql2) or die (mysqli_error($dbconn));
            echo 'ip:null & email:null Deleted<br>';
        }
    }
    echo 'completed<br><br><br>';
    mysqli_free_result($result);


    $sql = "SELECT * FROM ipneighbor ORDER BY email ASC LIMIT 5000";
    $result = mysqli_query($dbconn, $sql);
    while ($data = mysqli_fetch_assoc($result)) {
        if ($data['email'] == null) {
            $id = $data['id'];
            $sql2 = "DELETE FROM ipneighbor WHERE id='$id'";
            $result2 = mysqli_query($dbconn, $sql2) or die (mysqli_error($dbconn));
            echo "email:null Deleted<br>";
        }
    }
    echo 'completed<br><br><br>';
    mysqli_free_result($result);


    $sql = "SELECT * FROM ipneighbor ORDER BY ip ASC LIMIT 5000";
    $result = mysqli_query($dbconn, $sql);
    while ($data = mysqli_fetch_assoc($result)) {
        if ($data['ip'] == null) {
            $id = $data['id'];
            $sql2 = "DELETE FROM ipneighbor WHERE id='$id'";
            $result2 = mysqli_query($dbconn, $sql2) or die (mysqli_error($dbconn));
            echo "ip:null Deleted<br>";
        }
    }
    echo 'completed<br><br><br>';
    mysqli_free_result($result);


    mysqli_close($dbconn);
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    $sql = "select *, count(*) as `NUM_DUPES` from `ipneighbor` group by `ip`, `email`, `uteam` ORDER BY `NUM_DUPES` DESC LIMIT 5000";
    $dupes = mysqli_query($dbconn, $sql);
    if ($dupes) {
        foreach ($dupes as $dupe) {
            $dip = $dupe['ip'];
            $demail = $dupe['email'];
            $dteam = $dupe['uteam'];
            $dnum = $dupe['NUM_DUPES'] - 1;
            $sql2 = "delete from ipneighbor where ip = '$dip' and email = '$demail' and uteam = '$dteam' order by id desc limit $dnum";
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
    $sql .= "UPDATE ipneighbor SET id = @count:= @count + 1";
    if (mysqli_multi_query($dbconn, $sql)) {
        echo 'Unique id\'s updated<br><br><br>';
    } else {
        die(mysqli_error($dbconn));
    }
    ?>
</div>

</body>
</html>
