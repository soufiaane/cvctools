<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:/login.php");
} else {
    $sessionname = $_SESSION['name'];
}
//error_reporting(0);
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php
    include("../includes/inc.php");
    require("../includes/class/PRclass.php");
    //$cmptdom = 'log/cmptdom.txt';
    //$cmptop = 'log/cmptop.txt';
    ?>
</head>
<body>
<?php include("../navbar-all.php"); ?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-3">
    <center><h1 class="title">SPF Search Tool</h1></center>
    <br><br>
</div>
</div>
<div class="col-md-10">

    <?php
    $sql = "SELECT team, p_admin FROM users WHERE fullname = '$sessionname'";
    $result = mysqli_query($dbconn, $sql);
    $data = mysqli_fetch_assoc($result);
    $team = $data['team'];
    $admin = $data['p_admin'];
    mysqli_free_result($result);
    ?>
    </table>
    <?php
    if (($team == 'OPM16 Gmail Team1') || $admin == 1) {
        echo '<center><button class="btn btn-default" type="button">';
        $sql = "SELECT * FROM spf WHERE uteam='OPM16 Gmail Team1' group by gid";
        $result = mysqli_query($dbconn, $sql);
        $num = mysqli_num_rows($result);
        echo 'OPM16 Gmail Team1 <span class="badge">' . $num . '</span>';
        mysqli_free_result($result);
        echo '</button></center>';
    }
    if (($team == 'CVC Gmail Team1') || $admin == 1) {
        echo '<center><button class="btn btn-default" type="button">';
        $sql = "SELECT * FROM spf WHERE uteam='CVC Gmail Team1' group by gid";
        $result = mysqli_query($dbconn, $sql);
        $num = mysqli_num_rows($result);
        echo 'CVC Gmail Team1 <span class="badge">' . $num . '</span>';
        mysqli_free_result($result);
        echo '</button></center>';
    }
    if (($team == 'CVC Gmail Team2') || $admin == 1) {
        echo '<center><button class="btn btn-default" type="button">';
        $sql = "SELECT * FROM spf WHERE uteam='CVC Gmail Team2' group by gid";
        $result = mysqli_query($dbconn, $sql);
        $num = mysqli_num_rows($result);
        echo 'CVC Gmail Team2 <span class="badge">' . $num . '</span>';
        mysqli_free_result($result);
        echo '</button></center>';
    }
    ?>
</div>
</body>
</html>


<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').DataTable({
            "searching": true
        });
        $('#table_id tr').css('font-size', "0.85em");

    });

</script>

