<?php
gc_disable();
error_reporting(-1); // reports all errors
session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
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
    ?>
</head>
<body>
<?php include("../navbar-all.php"); ?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-3">
    <center><h1 class="title">SPF script</h1></center>
    <br><br>
</div>
<div class="col-md-10">
    <?php
    $start = microtime(true);
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");

    if (isset($_FILES['uploadedfile']) && $_FILES['uploadedfile'] != null) {
        $path = ($_FILES['uploadedfile']['tmp_name']);
        $fp = fopen($path, "r");
        $content = fread($fp, filesize($path));
        $doms = explode("\n", $content);
        $doms = array_map('trim', $doms);
        fclose($fp);
    } else {
        echo '<center><div class="alert alert-danger" role="alert">No domain found</div></center>';
    }
    $x = 0;
    foreach ($doms as $dom) {
        $sql = "SELECT domain FROM spfbazi WHERE domain='$dom'";
        $result = mysqli_query($dbconn, $sql);
        $data = mysqli_fetch_assoc($result);
        if ($data !== null) {
            echo $dom . '<br>';
            $x++;
        } else {
            break;
        }

    }
    echo '<br>' . $x . ' Domains Checked<br>';
    gc_collect_cycles();
    mysqli_close($dbconn);
    unset($dbconn);
    ?>
    </tbody>
    </table>
</div>
</body>
</html>

<?php
$end = microtime(true);
$time = number_format(($end - $start), 2);
echo $time . ' seconds<br>';
?>
