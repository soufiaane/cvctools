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
    <center><h1 class="title">Bulk rDNS Check Tool</h1></center>
    <br><br>
</div>
<div class="col-md-8">
    <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>IP</th>
            <th>Reverse DNS</th>
        </tr>
        </thead>
        <tbody>

        <?php
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
            echo '<center><div class="alert alert-danger" role="alert">No IP found</div></center>';
        }
        foreach ($ips as $ip) {
            cmpt($cmptips);
            echo '<tr>';
            echo '<td>' . $ip . '</td>';
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $rdns = gethostbyaddr($ip);
                $of = 'log/download__' . $id . '.txt';
                if ($rdns == $ip) {
                    echo '<td><font color="red">No rDNS Found</font></td>';
                    $log = $ip . ';' . 'No rDNS' . PHP_EOL;
                } else {
                    echo '<td>' . $rdns . '</td>';
                    $log = $ip . ';' . $rdns . PHP_EOL;
                }
            } else {
                echo '<td>Failed</td>';
                $log = $ip . ';' . 'Failed' . PHP_EOL;
            }
            file_put_contents($of, $log, FILE_APPEND);
        }
        ?>
        </tr>
        <a href="ljkhgjldhfg" class="kjdfhkjd" role="nhdfjh"
        </tbody>
    </table>
    <br><br>
    <a href="log/download.php?id=<?php echo $id ?>" class="btn btn-primary btn-sm btn-block" role="button">Download<span
            class="glyphicon glyphicon-download-alt"></span></a>
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