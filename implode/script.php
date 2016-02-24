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
$cmptips = 'log/cmptips.txt';
$cmptops = 'log/cmptops.txt';
cmpt($cmptops);
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">Ip's Implode Tool</h1></center>
    <br><br>
</div>
<div class="col-md-8">

    <?php


    if (isset($_POST['ips']) && $_POST['ips'] != null) {
        $ips = explode("\n", $_POST['ips']);
        $ips = array_map('trim', $ips);
        $ips = implode(";", $ips);
    } elseif (isset($_FILES['uploadedfile']) && $_FILES['uploadedfile'] != null) {

        $path = ($_FILES['uploadedfile']['tmp_name']);
        $fp = fopen($path, "r");
        $content = fread($fp, filesize($path));
        $ips = explode("\n", $content);
        $ips = array_map('trim', $ips);
        $ips = implode(";", $ips);
        fclose($fp);
    }
    if (isset($ips) && $ips != null) {
        echo '<textarea class="form-control" rows="3" id="imploded">' . $ips . '</textarea>';
    } else {
        echo '<textarea class="form-control" rows="3" id="imploded">No Ip Found</textarea>';
    }
    ?>
    <br><br>
    <center>
        <button type="button" onclick="myFunction()" class="btn btn-success">Select</button>
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

    function myFunction() {
        document.getElementById("imploded").select();
    }
</script>