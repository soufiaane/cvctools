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
<div class="col-md-3 col-md-offset-3">
    <center><h1 class="title">Ip neighbors</h1></center>
    <br><br><br><br>
</div>
<div class="col-md-5 col-md-offset-2 well well-lg">
    <div class="col-md-10 col-md-offset-1">
        <?php
        $tid = $_GET['tid'];
        switch ($tid) {
            case 'opmya1':
                $team = 'OPM16 Yahoo Team1';
                break;
            case 'cvcya1':
                $team = 'CVC Yahoo Team1';
                break;
            case 'cvcya2':
                $team = 'CVC Yahoo Team2';
                break;
            case 'cvcya3':
                $team = 'CVC Yahoo Team3';
                break;
        }
        $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
        $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
        $data = mysqli_fetch_assoc($result);
        if (($data['team'] == $team && $data['p_teamleader'] == 1) || $data['p_ipcheck_admin'] == 1){
        ?>
        <center><h4>Data of <?php echo $team; ?></h4></center>
        <textarea class="form-control" rows="15" id="data"><?php
            $sql = "SELECT * FROM ipneighbor WHERE uteam='$team'";
            $result = mysqli_query($dbconn, $sql);
            while ($data = mysqli_fetch_assoc($result)) {
                echo $data['email'];
                echo '
';
            }
            ?></textarea>
        <br>
        <center>
            <button type="button" onclick="myFunction()" class="btn btn-success">Select all</button>
        </center>
    </div>
    <?php
    }
    else {
        echo 'Error! you don\'t have permission to get this informations';
    }
    ?>
</div>
</body>
</html>


<script type="text/javascript">
    function myFunction() {
        document.getElementById("data").select();
    }
</script>