<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
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
                <center><h1 class="title">SPF Search</h1></center>
                <br><br><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-12">
                <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Domain</th>
                        <th>Spf</th>
                        <?php
                        if (isset($_POST['req2']) && $_POST['req2'] != '' && $_SESSION['name'] === 'Bendris Abderrahim') {
                            echo '<th>MX</th>';
                            echo '<th>pgrank</th>';
                            echo '<th>alexa rank</th>';
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
                    if ($dbconn) {
                        if (isset($_POST['req']) && $_POST['req'] != '') {
                            $req = $_POST['req'];
                            $sql = "SELECT domain, spf FROM spfbazi WHERE $req";
                            $result = mysqli_query($dbconn, $sql);
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . $data['domain'] . '</td>';
                                echo '<td>' . $data['spf'] . '</td>';
                                echo '</tr>';
                            }
                            mysqli_free_result($result);
                        }
                        if (isset($_POST['req2']) && $_POST['req2'] != '' && $_SESSION['name'] === 'Bendris Abderrahim') {
                            $req2 = $_POST['req2'];
                            $sql = "SELECT domain, record, mx, pgrank, rank FROM spf WHERE $req2";
                            $result = mysqli_query($dbconn, $sql);
                            while ($data = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . $data['domain'] . '</td>';
                                echo '<td>' . $data['record'] . '</td>';
                                echo '<td>' . $data['mx'] . '</td>';
                                echo '<td>' . $data['pgrank'] . '</td>';
                                echo '<td>' . $data['rank'] . '</td>';
                                echo '</tr>';
                            }
                            mysqli_free_result($result);
                        }
                    } else {
                        echo '<center><div class="alert alert-danger" role="alert">Can not connect to database</div></center>';
                    }
                    ?>
                    </tbody>
                </table>
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