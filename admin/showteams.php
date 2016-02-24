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
        if ($data['p_ad_u_permission'] == 0) {
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
                <center><h1 class="title">Users Teams</h1></center>
                <br><br><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Team</th>
                        <th>User Full name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
                    if ($dbconn) {
                        $sql = "SELECT team FROM users WHERE team IS NOT NULL and team!=1 and team!='' GROUP BY team";
                        $result = mysqli_query($dbconn, $sql);
                        $i = 0;
                        while ($data = mysqli_fetch_assoc($result)) {
                            $teams[$i] = $data['team'];
                            $i++;
                        }
                        foreach ($teams as $team) {
                            echo '<tr><td>' . $team . '</td><td>';
                            $sql = "SELECT fullname, p_teamleader FROM users WHERE team='$team'";
                            $result = mysqli_query($dbconn, $sql);
                            while ($data = mysqli_fetch_assoc($result)) {
                                if ($data['p_teamleader'] == 1) {
                                    echo '<font color="red"><li>' . $data['fullname'] . '</li></font>';
                                } else {
                                    echo '<li>' . $data['fullname'] . '</li>';
                                }
                            }
                            echo '</td></tr>';
                        }
                    }
                    ?>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>


<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').DataTable({});

    });
</script>