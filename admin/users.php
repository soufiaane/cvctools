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
                <center><h1 class="title">Users Permissions</h1></center>
                <br><br><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full name</th>
                        <th>Entity</th>
                        <th>Admin</th>
                        <th>Registration date</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
                    if ($dbconn) {
                        $sql = "SELECT * FROM users";
                        $result = mysqli_query($dbconn, $sql);
                        while ($data = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $data['username'] . '</td>';
                            echo '<td>' . $data['fullname'] . '</td>';
                            echo '<td>' . $data['entity'] . '</td>';
                            echo '<td>';
                            if ($data['p_admin'] == 1) {
                                echo '<font color="green">Yes</font>';
                            } else {
                                echo '<font color="red">No</font>';
                            }
                            echo '</td>';
                            echo '<td>' . $data['r_date'] . '</td>';
                            echo '<td>';
                            echo '<a href="useredit.php?u=' . $data['id'] . '">Edit <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        mysqli_free_result($result);
                    } else {
                        echo '<center><div class="alert alert-danger" role="alert">Can not connect to database</div></center>';
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