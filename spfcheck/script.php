<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
} else {
    $sessionname = $_SESSION['name'];
}
$_SESSION['doms'] = $_POST['dms'];
error_reporting(0);
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php
    include("../includes/inc.php");
    require("../includes/class/PRclass.php");
    $cmptdom = 'log/cmptdom.txt';
    $cmptop = 'log/cmptop.txt';
    ?>
</head>
<body>
<?php include("../navbar-all.php"); ?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-3">
    <center><h1 class="title">SPF Bulk Check Tool</h1></center>
    <br><br>
</div>
<div class="col-md-10">
    <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Domain name</th>
            <th>SPF Record</th>
            <th>MX Record</th>
            <th>Page Rank</th>
            <th>Alexa Rank</th>
            <th>Alexa Country</th>
        </tr>
        </thead>
        <tbody>
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
        $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
        $result = mysqli_query($dbconn, $sql);
        $data = mysqli_fetch_assoc($result);
        $uteam = $data['team'];
        $uid = $data['id'];
        $sql = "SELECT MAX(gid) FROM spf";
        $result = mysqli_query($dbconn, $sql);
        $data = mysqli_fetch_row($result);
        $maxgid = $data[0];
        $doms = $_POST['dms'];
        $doms = str_replace("\r", "\n", str_replace("\r\n", "\n", $doms));
        $doms = explode("\n", $doms);
        foreach ($doms as $dom) {
            $dom = trim($dom, ' ');
            if ($dom != '') {
                cmpt($cmptdom);
                //get spf & mx
                $rresult = @dns_get_record($dom, DNS_TXT + DNS_MX);
                $cnt = count($rresult);
                echo '<tr>';
                echo '<td>' . $dom . '</td>';
                echo '<td>';
                if ($rresult == null) {
                    $spf = 'No spf';
                    $maxgid += 1;
                    $sql = "INSERT INTO spf (id, domain, record, level, gid, uteam, uid) VALUES ('', '$dom', '$spf', 'A', '$maxgid', '$uteam', '$uid')";
                    $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                    echo $spf;
                } else {
                    for ($i = 0; $i <= $cnt - 1; $i++) {
                        if (preg_match('/v=spf1/', @$rresult[$i]['txt'])) {
                            $maxgid += 1;
                            $spf = $rresult[$i]['txt'];
                            $sql = "INSERT INTO spf (id, domain, record, level, gid, uteam, uid) VALUES ('', '$dom', '$spf', 'A', '$maxgid', '$uteam', '$uid')";
                            $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                            echo '<ul class="collapsibleList"><li><p>' . $spf . '</p>';
                            if (preg_match('/include/', $spf)) {
                                inc($spf);
                            }
                            break;
                        } else {
                            $spf = 'No spf';
                        }
                    }
                    if ($spf == 'No spf') {
                        $maxgid += 1;
                        $sql = "INSERT INTO spf (id, domain, record, level, gid, uteam, uid) VALUES ('', '$dom', '$spf', 'A', '$maxgid', '$uteam', '$uid')";
                        $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                        echo $spf;
                    }
                }
                echo '</td>';
                echo '<td>';
                $mx = '';
                for ($i = 0; $i <= $cnt - 1; $i++) {
                    if ($rresult[$i]['type'] == 'MX') {
                        $mx .= $rresult[$i]['target'] . '<br>';
                    }
                }
                $sql = "UPDATE spf SET mx='$mx' WHERE domain='$dom'";
                $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
                echo $mx;


                echo '</td>';
                //get pagerank from PRclass.php
                $pr = new PR();
                $prank = @$pr->get_google_pagerank($dom);
                echo '<td>' . $prank . '</td>';
                $sql = "UPDATE spf SET pgrank='$prank' WHERE domain='$dom'";
                $result = mysqli_query($dbconn, $sql) or die(mysqli_error($dbconn));

                //get alexa rank from xml
                $xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url=' . $dom);
                $rank = isset($xml->SD[1]->POPULARITY) ? $xml->SD[1]->POPULARITY->attributes()->TEXT : 0;
                $bestcountry = isset($xml->SD[1]->COUNTRY) ? $xml->SD[1]->COUNTRY->attributes()->NAME : 0;
                $countryrank = isset($xml->SD[1]->COUNTRY) ? $xml->SD[1]->COUNTRY->attributes()->RANK : 0;
                $sql = "UPDATE spf SET rank='$rank' WHERE domain='$dom'";
                $result = mysqli_query($dbconn, $sql) or die(mysqli_error($dbconn));
                echo '<td>' . $rank . '</td>';
                echo '<td>' . $bestcountry . ': ' . $countryrank . '</td>';
                echo '</tr>';
            }
        }
        cmpt($cmptop);
        ?>
        </tbody>
    </table>

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


// function for includes extraction ------------------------

function inc($x)
{
    static $cmp = 0;
    global $dom;
    global $dbconn;
    global $maxgid;
    global $uteam;
    global $uid;
    $flag = false;
    $y = substr_count($x, 'include:');
    if ($cmp < $y) {
        $cmp += 1;
        $x = strafter($x, 'include:');
        $b = $x;
        $x = strbefore($x, ' ');
        $spfincarr = @dns_get_record($x, DNS_TXT);
        $cntinc = count($spfincarr);
        for ($i = 0; $i <= $cntinc - 1; $i++) {
            if (preg_match('/v=spf1/', $spfincarr[$i]['txt'])) {
                $spfincres = $spfincarr[$i]['txt'];
                if (!preg_match('/' . $x . '/', $spfincres)) {
                    echo '<ul><li>' . $spfincres;
                    $cmp -= 1;
                    $flag = true;
                }
            }
        }
        if ($flag == true) {
            $sql = "INSERT INTO spf (id, domain, record, level, gid, uteam, uid) VALUES ('', '$dom', '$spfincres', 'B', '$maxgid', '$uteam', '$uid')";
            $result = mysqli_query($dbconn, $sql) or die (mysqli_error($dbconn));
            if (@preg_match('/include/', $spfincres)) {
                inc($spfincres);
            }
            echo '</li></ul>';
            inc($b);
        }
    }
}

//---------end of function------------
$end = microtime(true);
$time = number_format(($end - $start), 2);
echo $time . ' seconds<br>';
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').DataTable({
            "searching": true
        });
        $('#table_id tr').css('font-size', "0.85em");

    });

    CollapsibleLists.apply();
</script>

