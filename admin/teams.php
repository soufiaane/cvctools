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
        if ($data['p_ad_u_teams'] == 0) {
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
?>
<div class="row">
    <div class="col-md-2">
        <?php include("../sidebar.php"); ?>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <center><h1 class="title">Manage teams</h1></center>
                <br><br>
            </div>
        </div>
        <?php
        $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
        if ($dbconn){
        ?>
        <div class="row">
            <div class="col-md-2"></div>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <div class="col-md-8 well">
                    <div class="col-md-4 form-group">
                        <select multiple class="form-control" size="15" name="users[]">
                            <?php
                            $sql = "SELECT * FROM users";
                            $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo '<option>' . $data['fullname'] . '</option>';
                            }
                            mysqli_free_result($result);
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <br><br><br><br><br>
                        <select class="form-control" name="teams">
                            <option><?php echo NULL ?> </option>
                            <option>CVC Yahoo Team1</option>
                            <option>CVC Yahoo Team2</option>
                            <option>CVC Yahoo Team3</option>
                            <option>CVC3 Yahoo Team1</option>
                            <option>OPM16 Yahoo Team1</option>
                            <option>CVC Gmail Team1</option>
                            <option>CVC Gmail Team2</option>
                            <option>OPM16 Gmail Team1</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <br><br><br><br><br>
                        <center>
                            <button class="btn btn-primary" type="submit" name="save">Submite</button>
                        </center>
                    </div>
                </div>
            </form>
            <div class="col-md-2 col-md-offset-5">
                <center><a href="showteams.php" class="btn btn-default btn-sm" role="button">Show Teams<span
                            class="glyphicon glyphicon-import"></span></a></center>
            </div>
            <?php
            if (isset($_POST['save'])) {
                $team = $_POST['teams'];
                $users = $_POST['users'];
                foreach ($users as $user) {
                    $sql = "UPDATE users SET team='$team' WHERE fullname='$user'";
                    $result = mysqli_query($dbconn, $sql);
                    if (!$result) {
                        echo '<div class="alert alert-danger" role="alert"><b>Can not update database</b></div>';
                        die (mysqli_error($dbconn));
                    } else {
                        echo '<div class="alert alert-success" role="alert"><b>User details has been updated</b></div>';
                    }
                }
            }
            }
            else {
                echo '<div class="alert alert-danger" role="alert"><b>Can not Connect to database</b></div>';
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>