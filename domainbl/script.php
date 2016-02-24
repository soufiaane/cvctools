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
    <center><h1 class="title">Domains Blacklists Check Tool</h1></center>
    <br><br>
</div>
<div class="col-md-8">
    <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Domain name</th>
            <th>Status</th>
            <th>List</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $dnsbl_lookup = array("dbl.spamhaus.org");

        $delist = array("https://www.spamhaus.org/dbl/removal/form");

        if (isset($_POST['domains']) && $_POST['domains'] != null) {
            $doms = explode("\n", $_POST['domains']);
            $doms = array_map('trim', $doms);
        } elseif (isset($_FILES['uploadedfile']) && $_FILES['uploadedfile'] != null) {

            $path = ($_FILES['uploadedfile']['tmp_name']);
            $fp = fopen($path, "r");
            $content = fread($fp, filesize($path));
            $doms = explode("\n", $content);
            $doms = array_map('trim', $doms);
            fclose($fp);
        } else {
            echo '<center><div class="alert alert-danger" role="alert">No domain name found</div></center>';
        }
        foreach ($doms as $dom) {
            cmpt($cmptips);
            echo '<tr>';
            echo '<td>' . $dom . '</td>';
            $cnt = -1;
            echo '<td>';
            foreach ($dnsbl_lookup as $host) {
                $cnt++;
                if (checkdnsrr($dom . "." . $host . ".", "A")) {
                    $listed = '<a href=' . $delist[$cnt] . '/' . $dom . ' target="_blank"><font color="red">Listed</font></a><br>';
                    $of = 'log/' . $host . '__' . $id . '.txt';
                    $log = $dom . PHP_EOL;
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
        }
        ?>
        </tr>
        </tbody>
    </table>
</div>
<div class="col-md-2">
    <br><br><br><br><br><br>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <center>Download blacklisted Domains</center>
            </h3>
        </div>
        <div class="panel-body">
            <a href="log/downloaddbl.php?id=<?php echo $id ?>" class="btn btn-primary btn-sm btn-block" role="button">Spamhaus
                DBL <span class="glyphicon glyphicon-download-alt"></span></a>
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