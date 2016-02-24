<?php
gc_disable();
error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
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
    //functions for substr for include
    function strafter($string, $substring)
    {
        $pos = strpos($string, $substring);
        if ($pos === false)
            return $string;
        else
            return (substr($string, $pos + strlen($substring)));
    }

    //---*---
    function strbefore($string, $substring)
    {
        $pos = strpos($string, $substring);
        if ($pos === false)
            return $string;
        else
            return (substr($string, 0, $pos));
    }

    //end of function
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
        echo '<center><div class="alert alert-danger" role="alert">No ip found</div></center>';
    }

    foreach ($doms as $dom) {
        $dom = trim($dom, ' ');
        if ($dom != '') {
            //get spf
            $rresult = @dns_get_record($dom, DNS_TXT);
            $cnt = count($rresult);
            if ($rresult == null) {
                $spf = 'No spf';
                $sql = "INSERT INTO spfbazi (domain, spf) VALUES ('$dom', '$spf')";
                $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
            } else {
                for ($i = 0; $i <= $cnt - 1; $i++) {
                    if (preg_match('/v=spf1/', @$rresult[$i]['txt'])) {
                        $spf = $rresult[$i]['txt'];
                        $sql = "INSERT INTO spfbazi (domain, spf) VALUES ('$dom', '$spf')";
                        $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                        $last_id = mysqli_insert_id($dbconn);
                        if (preg_match('/include/', $spf) || preg_match('/redirect/', $spf)) {
                            inc($spf);
                        }
                        break;
                    } else {
                        $spf = 'No spf';
                    }
                }
                if ($spf == 'No spf') {
                    $sql = "INSERT INTO spfbazi (domain, spf) VALUES ('$dom', '$spf')";
                    $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                }
            }
        }
        gc_collect_cycles();
    }
    mysqli_close($dbconn);
    unset($dbconn);
    ?>
    </tbody>
    </table>
</div>
</body>
</html>

<?php
// function for includes extraction ------------------------

function inc($x)
{
    $cmp = 0;
    static $loop = 0;
    $loop++;
    global $dom;
    global $dbconn;
    global $last_id;
    $flag = false;
    $in = substr_count($x, 'include:');
    $re = substr_count($x, 'redirect=');
    if ($cmp < $in) {
        $cmp += 1;
        $x = strafter($x, 'include:');
        $b = $x;
        $x = strbefore($x, ' ');
        if ($x !== $dom) {
            $spfincarr = @dns_get_record($x, DNS_TXT);
            $cntinc = count($spfincarr);
            for ($i = 0; $i <= $cntinc - 1; $i++) {
                if (preg_match('/v=spf1/', $spfincarr[$i]['txt'])) {
                    $spfincres = $spfincarr[$i]['txt'];
                    $sql = "UPDATE spfbazi SET spf = CONCAT(spf, '\r\n', '$spfincres') WHERE id='$last_id'";
                    $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                    if (@preg_match('/include/', $spfincres) || @preg_match('/redirect/', $spfincres)) {
                        if ($loop > 3) {
                            break;
                        }
                        inc($spfincres);
                    }
                    if ($loop > 3) {
                        break;
                    }
                    inc($b);
                }
            }
        }
    }
    if ($cmp < $re) {
        $cmp += 1;
        $x = strafter($x, 'redirect=');
        $b = $x;
        $x = strbefore($x, ' ');
        if ($x !== $dom) {
            $spfincarr = @dns_get_record($x, DNS_TXT);
            $cntinc = count($spfincarr);
            for ($i = 0; $i <= $cntinc - 1; $i++) {
                if (preg_match('/v=spf1/', $spfincarr[$i]['txt'])) {
                    $spfincres = $spfincarr[$i]['txt'];
                    $sql = "UPDATE spfbazi SET spf = CONCAT(spf, '\r\n', '$spfincres') WHERE id='$last_id'";
                    $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                    if (@preg_match('/include/', $spfincres) || @preg_match('/redirect/', $spfincres)) {
                        if ($loop > 3) {
                            break;
                        }
                        inc($spfincres);
                    }
                    if ($loop > 3) {
                        break;
                    }
                    inc($b);
                }
            }
        }
    }
    $loop = 0;
}

//---------end of function------------
$end = microtime(true);
$time = number_format(($end - $start), 2);
echo $time . ' seconds<br>';
?>

