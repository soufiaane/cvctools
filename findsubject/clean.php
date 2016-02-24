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

<?php
error_reporting(E_ALL & ~E_NOTICE);
include("../navbar-all.php");
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-3 col-md-offset-2">
    <center><h1 class="title">Yahoo Find Subject</h1></center>
    <br><br>



    <?php
    $start = microtime(true);
    if ((!isset($_POST['data']) || $_POST['data'] == null) && (!isset($_POST['cfolder']) || $_POST['cfolder'] == null)) {
        echo 'fail';
    } else {
        cmpt('log/cmptitemscln.txt');
        $cmptextract = 0;
        $data = $_POST['data'];
        $data = explode("\n", str_replace("\r", "", $data));
        $nbdata = count($data);
        $cfolder = $_POST['cfolder'];
        $mailboxPath = '{imap.mail.yahoo.com:993/imap/ssl}' . $cfolder;
        echo '<center><h4>Extract of ' . $nbdata . ' Mailboxes</h4>';
        echo '</div>';
        echo '<div class="col-md-6">';
        foreach ($data as $dt) {
            $cols = preg_split('/\s+|,/', $dt);
            $username = $cols[0];
            $password = $cols[1];
            $imap = @imap_open($mailboxPath, $username, $password, null, 1);
            $error = imap_errors();
            if (@in_array('Too many login failures', $error)) {
                echo '<p class="bg-warning">Can not connect to ' . $username . ': Too many login failures</p><br>';
            } else {
                cmpt('log/cmptopcln.txt');
                $numMessages = imap_num_msg($imap);
                for ($i = 0; $i <= $numMessages; $i++) {
                    imap_delete($imap, $i);
                }
                imap_expunge($imap);
                echo '<p class="bg-info"> The mailbox ' . $username . ' is cleaned</p><br>';
                imap_close($imap);
            }
        }
    }
    $end = microtime(true);
    $time = number_format(($end - $start), 2);
    echo $time . ' seconds<br>';

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
</div>
</body>
</html>