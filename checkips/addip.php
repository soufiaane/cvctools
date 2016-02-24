<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
} else {
    $sessionname = $_SESSION['name'];
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    if ($dbconn) {
        $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
        $result = mysqli_query($dbconn, $sql);
        $data = mysqli_fetch_assoc($result);
        if ($data['p_ipcheck_admin'] == 0) {
            header("location:../noperm.php");
        }
        mysqli_free_result($result);
    }
    mysqli_close($dbconn);
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
    <center><h1 class="title">Ip neighbors</h1></center>
    <br>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <center><h3>Put your Data</h3></center>
        <center><font color="grey">(Email,Ip)</font></center>
        <textarea class="form-control" rows="15" name="ips"></textarea>
        <br>
        <center>
            <button type="submit" class="btn btn-primary">Add data to database <span
                    class="glyphicon glyphicon-import"></span></button>
        </center>
    </form>
    <?php
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    if ($dbconn) {
        if (isset($_POST['ips']) && $_POST['ips'] != null) {
            $ips = explode("\n", $_POST['ips']);
            $ips = array_map('trim', $ips);
            echo '<div class="col-md-12">';
            echo '<div class="well">';
            $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
            $result = mysqli_query($dbconn, $sql);
            $datausers = mysqli_fetch_assoc($result);
            $uteam = $datausers['team'];
            foreach ($ips as $ip) {
                $cols = preg_split('/\s+|,/', $ip);
                $email = $cols[0];
                $ip = $cols[1];
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    $sql = "INSERT INTO ipneighbor (id, ip, email, uteam) VALUES ('', '$ip', '$email', '$uteam')";
                    if (mysqli_query($dbconn, $sql)) {
                        echo $ip . ' has been <font color="green">inserted</font><br>';
                    } else {
                        echo $ip . ' <font color="red">Not inserted. </font>' . mysqli_error();
                    }
                } else {
                    echo $ip . ' <font color="red">Not a valid V4 Ip adress</font><br>';
                }
            }
            echo '</div></div>';
        }
    } else {
        echo '<center><div class="alert alert-danger" role="alert">Can not connect to database</div></center>';
    }
    ?>
</div>
</body>
</html>