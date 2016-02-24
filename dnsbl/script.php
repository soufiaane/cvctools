<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
} else {
    $sessionname = $_SESSION['name'];
}
error_reporting(0);
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("../includes/inc.php"); ?>
</head>
<body>
<?php include("../navbar-all.php");
$id = $_GET['id'];
$cmptips = 'log/cmptips.txt';
$cmptops = 'log/cmptops.txt';
cmpt($cmptops);
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">DNS Blacklists Check Tool</h1></center>
    <br><br>
</div>
<div class="col-md-8">
    <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>IP</th>
            <th>Status</th>
            <th>List</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $dnsbl_lookup = array("pbl.spamhaus.org",
            "sbl.spamhaus.org",
            "xbl.spamhaus.org",
            "b.barracudacentral.org",
            "spamsources.fabel.dk",
            "spam.dnsbl.sorbs.net",
            "dyna.spamrats.com",
            "all.spamrats.com",
            "bl.spamcannibal.org",
            "bl.spamcop.net");

        $delist = array("https://www.spamhaus.org/pbl/removal/form/",
            "http://www.spamhaus.org/css/removal/form/",
            "https://www.spamhaus.org/lookup/",
            "http://www.barracudacentral.org/rbl/removal-request",
            "http://www.spamsources.fabel.dk/delist",
            "http://www.sorbs.net/cgi-bin/support",
            "http://www.spamrats.com/removal.php",
            "http://www.spamrats.com/removal.php",
            "http://www.spamcannibal.org/cannibal.cgi",
            "http://dnsrbl.org/lookup/");

        $dbs = array("pbl",
            "sbl",
            "xbl",
            "bb",
            "fabel",
            "sorbs",
            "ratsdyna",
            "ratsall",
            "cannibal",
            "spamcop");

        $dbnames = array("Spamhaus PBL",
            "Spamhaus SBL",
            "Spamhaus XBL",
            "Barracuda",
            "Spam Source's",
            "SORBS Spam",
            "SpamRats Dyna",
            "SpamRats All",
            "SpamCannibal",
            "SpamCop");

        if (isset($_POST['ips']) && $_POST['ips'] != null) {
            $ips = explode("\n", $_POST['ips']);
            $ips = array_map('trim', $ips);
        } elseif (isset($_FILES['uploadedfile']) && $_FILES['uploadedfile'] != null) {

            $path = ($_FILES['uploadedfile']['tmp_name']);
            $fp = fopen($path, "r");
            $content = fread($fp, filesize($path));
            $ips = explode("\n", $content);
            $ips = array_map('trim', $ips);
            fclose($fp);
        } else {
            echo '<center><div class="alert alert-danger" role="alert">No ip found</div></center>';
        }
        foreach ($ips as $ip) {
            cmpt($cmptips);
            echo '<tr>';
            echo '<td>' . $ip . '</td>';
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
                $cnt = -1;
                echo '<td>';
                foreach ($dnsbl_lookup as $host) {
                    $cnt++;
                    if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
                        $listed = '<a href=' . $delist[$cnt] . ' target="_blank"><font color="red">Listed</font></a><br>';
                        $of = 'log/' . $dbs[$cnt] . '__' . $id . '.txt';
                        $log = $ip . PHP_EOL;
                        file_put_contents($of, $log, FILE_APPEND);
                        echo $listed;
                    } else {
                        echo '<font color="green">Clear</font><br>';
                    }
                }
                echo '</td>';

                echo '<td>';
                foreach ($dnsbl_lookup as $host) {
                    echo $host . '<br>';
                }
                echo '</td>';
            } else {
                echo '<td>' . $ip . 'not a valid ip</td>';
            }
        }
        ?>
        </tr>
        </tbody>
    </table>
</div>
<div class="col-md-2">
    <br><br>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Download Blacklisted Ip's</h3>
        </div>
        <div class="panel-body">
            <?php
            $i = -1;
            foreach ($dnsbl_lookup as $host) {
                $i++;
                echo '<a href="log/download.php?db=' . $dbs[$i] . '&id=' . $id . '" class="btn btn-primary btn-sm btn-block" role="button">' . $dbnames[$i] . ' <span class="glyphicon glyphicon-download-alt"></span></a>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
function cmpt($file)
{
    $file = fopen($file, 'r+');
    $v = fgets($file);
    $v += 1;
    fseek($file, 0);
    fputs($file, $v);
    fclose($file);

}

?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').DataTable({
            "pageLength": 100
        });

    });

</script>