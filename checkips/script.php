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
    <center><h1 class="title">Ip neighbors</h1></center>
    <br><br>
</div>
<div class="col-md-8">
    <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Local Ip</th>
            <th>From Email</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
        if ($dbconn){
        if (isset($_POST['ips']) && $_POST['ips'] != null && isset($_POST['octet'])) {
            $ips = explode("\n", $_POST['ips']);
            $ips = array_map('trim', $ips);
            $octet = $_POST['octet'];
            $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
            $result = mysqli_query($dbconn, $sql);
            $datausers = mysqli_fetch_assoc($result);
            $uteam = $datausers['team'];
        } else {
            echo '<center><div class="alert alert-danger" role="alert">Please fill all fields</div></center>';
        }
        $start = microtime(true);
        foreach ($ips as $ip) {
            if ($ip != null && $ip != '') {
                echo '<tr>';
                echo '<td>' . $ip . '</td>';
                echo '<td>';
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    if ($octet == 1) {
                        $exploded = explode(".", $ip, -3);
                        $sql = "SELECT * FROM ipneighbor WHERE uteam='$uteam' OR uteam='1' OR '$uteam'='1'";
                        $result = mysqli_query($dbconn, $sql);
                        while ($data = mysqli_fetch_assoc($result)) {
                            $rip = $data['ip'];
                            $xrip = explode(".", $rip, -3);
                            if ($exploded[0] == @$xrip[0]) {
                                echo $data['email'] . '|' . $data['ip'] . '<br>';
                            }
                        }
                    } elseif ($octet == 2) {
                        $exploded = explode(".", $ip, -2);
                        $sql = "SELECT * FROM ipneighbor WHERE uteam='$uteam' OR uteam='1' OR '$uteam'='1'";
                        $result = mysqli_query($dbconn, $sql);
                        while ($data = mysqli_fetch_assoc($result)) {
                            $rip = $data['ip'];
                            $xrip = explode(".", $rip, -2);
                            if (($exploded[0] == @$xrip[0]) && ($exploded[1] == $xrip[1])) {
                                echo $data['email'] . '|' . $data['ip'] . '<br>';
                            }
                        }
                    } elseif ($octet == 3) {
                        $exploded = explode(".", $ip, -1);
                        $sql = "SELECT * FROM ipneighbor WHERE uteam='$uteam' OR uteam='1' OR '$uteam'='1'";
                        $result = mysqli_query($dbconn, $sql);
                        while ($data = mysqli_fetch_assoc($result)) {
                            $rip = $data['ip'];
                            $xrip = explode(".", $rip, -1);
                            if (($exploded['0'] == @$xrip['0']) && ($exploded[1] == $xrip[1]) && ($exploded[2] == $xrip[2])) {
                                echo $data['email'] . '|' . $data['ip'] . '<br>';
                            }
                        }
                    }
                } else {
                    echo $ip . ' not a valid ip';
                }
            }
            echo '</td>';
        }
        ?>
        </tbody>
    </table>
</div>
<?php
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($dbconn, $sql);
$data = mysqli_fetch_assoc($result);
if ($data['p_ipcheck_admin'] == 1) {
    ?>
    <div class="col-md-2">
        <br><br>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Administration Panel</h3>
            </div>
            <div class="panel-body">
                <a href="addip.php" class="btn btn-default btn-sm btn-block" role="button">Add ip's to database <span
                        class="glyphicon glyphicon-import"></span></a>
                <br>
                <a href="deleteip.php" class="btn btn-default btn-sm btn-block" role="button">Delete ip's from database
                    <span class="glyphicon glyphicon-trash"></span></a>
            </div>
        </div>
        <center>
            <button class="btn btn-default" type="button">
                <?php
                $sql = "SELECT * FROM ipneighbor";
                $result = mysqli_query($dbconn, $sql);
                $num = mysqli_num_rows($result);
                echo 'Records in database <span class="badge">' . $num . '</span>';
                ?>
            </button>
        </center>
    </div>
<?php
}
}
$end = microtime(true);
$time = number_format(($end - $start), 2);
echo $time . ' seconds<br>';
?>
</body>
</html>


<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').DataTable({
            "pageLength": 100
        });

    });

</script>