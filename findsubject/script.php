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
if ((!isset($_POST['data']) || $_POST['data'] == null) || (!isset($_POST['keyword']) || $_POST['keyword'] == null) || (!isset($_POST['find']) || $_POST['find'] == null) || (!isset($_POST['folder']) || $_POST['folder'] == null)) {
    echo '<center><div class="alert alert-danger" role="alert">Please fill the required fields (*)</div></center>';
} else {
    $cmptextract = 0;
    $data = $_POST['data'];
    $data = explode("\n", str_replace("\r", "", $data));
    $nbdata = count($data);
    $find = $_POST['find'];

    switch ($find) {
        case 'fromemail':
            $extract = 'From Emails';
            break;
        case 'messageid':
            $extract = 'Message-Ids';
            break;
        case 'ips':
            $extract = 'Ips';
            break;
        case 'from_ips':
            $extract = "From emails & Ips";
            break;
        case 'listid':
            $extract = 'List-Ids';
            break;
        case 'subject':
            $extract = 'Subjects';
            break;
        case 'fulllist_id':
            $extract = 'Full List-Id';
            break;
        case 'reply_to':
            $extract = 'Reply To';
            break;
        case 'sender':
            $extract = 'Sender\'s';
            break;
        case 'listid_ip':
            $extract = 'List-Id + IP';
            break;
        case 'from_ip':
            $extract = 'From Email + IP';
            break;
        case 'from_fulllist_id':
            $extract = 'From + Full List-ID';
            break;
    }
    $keyword = $_POST['keyword'];
    $folder = $_POST['folder'];
    $mailboxPath = '{imap.mail.yahoo.com:993/imap/ssl}' . $folder;
    echo '<center><h4>Extract of ' . $extract . ' from ' . $nbdata . ' Mailboxes</h4>';
    echo '</div>';
    echo '<div class="col-md-6">';
    echo '<textarea class="form-control" rows="20" id="imploded">';
    foreach ($data as $dt) {
        $cols = preg_split('/\s+|,/', $dt);
        $username = $cols[0];
        $password = $cols[1];
        $imap = @imap_open($mailboxPath, $username, $password, null, 1);
        $error = imap_errors();
        if ($error != false) {
            echo 'Can not connect to ' . $username . ' :' . $error[0];
            echo '
';
        } else {
            cmpt('log/cmptopfind.txt');
            $numMessages = imap_num_msg($imap);
            if (isset($_POST['limit']) && $_POST['limit'] != null && $_POST['limit'] <= $numMessages) {
                $limit = $_POST['limit'];
            } else {
                $limit = $numMessages;
            }
            if ($_POST['sfrom'] == 0) {
                $from = 0;
            } else {
                $from = $_POST['sfrom'] - 1;
            }
            $istart = $numMessages - $from;
            $iend = $numMessages - $limit - $from;
            for ($i = $istart; $i > $iend; $i--) {
                $header = imap_header($imap, $i);
                $headerfull = imap_fetchheader($imap, $i);
                $arraytrimed = array('<', '>');
                $regExp = '/X-Originating-IP: .*((?:\d+\.){3}\d+)/';
                preg_match_all($regExp, $headerfull, $matches);
                $ip = @substr($matches[0][0], 19);
                $message_id = @str_replace($arraytrimed, '', $header->message_id);
                $list_id = strafter($headerfull, 'List-Id: ');
                $list_id = strbefore($list_id, "\n");
                $list_id = str_replace("\r", '', $list_id);
                $fulllist_id = $list_id;
                $list_id = @str_replace($arraytrimed, '', $list_id);
                $subject = $header->subject;
                $fromInfo = $header->from[0];
                $replyInfo = $header->reply_to[0];
                $senderInfo = $header->sender[0];
                $seen = $header->Unseen;
                $details = array(
                    "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host)) ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
                    "fromName" => (isset($fromInfo->personal)) ? $fromInfo->personal : "",
                    "replyAddr" => (isset($replyInfo->mailbox) && isset($replyInfo->host)) ? $replyInfo->mailbox . "@" . $replyInfo->host : "",
                    "senderAddr" => (isset($senderInfo->mailbox) && isset($senderInfo->host)) ? $senderInfo->mailbox . "@" . $senderInfo->host : "",
                );
                $fromaddr = $details["fromAddr"];
                $reply_to = $details["replyAddr"];
                $sender = $details["senderAddr"];
                if ($keyword != '*') {
                    if (@preg_match('/' . $keyword . '/', $subject)) {
                        if ($find == 'ips') {
                            echo $ip;
                            echo '
';
                        }
                        if ($find == 'fromemail') {
                            echo $fromaddr;
                            echo '
';
                        }
                        if ($find == 'messageid') {
                            echo $message_id;
                            echo '
';
                        }
                        if ($find == 'from_ips') {
                            echo $fromaddr . ';' . $ip;
                            echo '
';
                        }
                        if ($find == 'listid' && $list_id !== 'false') {
                            echo $list_id;
                            echo '
';
                        }
                        if ($find == 'subject') {
                            echo $subject;
                            echo '
';
                        }
                        if ($find == 'fulllist_id') {
                            echo $fulllist_id;
                            echo '
';
                        }
                        if ($find == 'reply_to') {
                            echo $reply_to;
                            echo '
';
                        }
                        if ($find == 'sender') {
                            echo $sender;
                            echo '
';
                        }
                        if ($find == 'listid_ip' && $list_id !== 'false') {
                            echo $list_id . ',' . $ip;
                            echo '
';
                        }
                        if ($find == 'from_ip') {
                            echo $fromaddr . ',' . $ip;
                            echo '
';
                        }
                        if ($find == 'from_fulllist_id') {
                            echo $fromaddr . ',' . $fulllist_id;
                            echo '
';
                        }
                    }
                } else {
                    if ($find == 'ips') {
                        echo $ip;
                        echo '
';
                    }
                    if ($find == 'fromemail') {
                        echo $fromaddr;
                        echo '
';
                    }
                    if ($find == 'messageid') {
                        echo $message_id;
                        echo '
';
                    }
                    if ($find == 'from_ips') {
                        echo $fromaddr . ';' . $ip;
                        echo '
';
                    }
                    if ($find == 'listid' && $list_id !== 'false') {
                        echo $list_id;
                    }
                    if ($find == 'subject') {
                        echo $subject;
                        echo '
';
                    }
                    if ($find == 'fulllist_id') {
                        echo $fulllist_id;
                    }
                    if ($find == 'reply_to') {
                        echo $reply_to;
                        echo '
';
                    }
                    if ($find == 'sender') {
                        echo $sender;
                        echo '
';
                    }
                    if ($find == 'listid_ip' && $list_id !== 'false') {
                        echo $list_id . ',' . $ip;
                        echo '
';
                    }
                    if ($find == 'from_ip') {
                        echo $fromaddr . ',' . $ip;
                        echo '
';
                    }
                    if ($find == 'from_fulllist_id') {
                        echo $fromaddr . ',' . $fulllist_id;
                        echo '
';
                    }
                }
                $cmptextract++;
                cmpt('log/cmptitemsfind.txt');
                $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
                $result = mysqli_query($dbconn, $sql);
                $data = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                $uteam = $data['team'];
                $uid = $data['id'];
                if (isset($ip) && $ip != '' && isset($fromaddr) && $fromaddr != '') {
                    $sql = "INSERT INTO ipneighbor(id, ip, email, uteam, uid) VALUES ('', '$ip', '$fromaddr', '$uteam', '$uid')";
                    $result = mysqli_query($dbconn, $sql) or die(mysqli_error($dbconn));
                }
            }
            imap_close($imap);
        }
    }
    echo '</textarea>';
    echo '<br>';
    echo '<center><button class="btn btn-primary" type="button">
                Find <span class="badge">' . $cmptextract . '</span> Results
                </button><center>';
    echo '<br>';
    echo '<center><button type="button" onclick="myFunction()" class="btn btn-success">Select</button></center>';
}


//functions for substr for include
function strafter($string, $substring)
{
    $pos = strpos($string, $substring);
    if ($pos === false)
        return 'false';
    else
        return (substr($string, $pos + strlen($substring)));
}

//---*---
function strbefore($string, $substring)
{
    $pos = strpos($string, $substring);
    if ($pos === false)
        return 'false';
    else
        return (substr($string, 0, $pos));
}

//end of function

function cmpt($file)
{
    $file = fopen($file, 'r+');
    $v = fgets($file);
    $v += 1;
    fseek($file, 0);
    fputs($file, $v);
    fclose($file);

}

$end = microtime(true);
$time = number_format(($end - $start), 2);
echo $time . ' seconds<br>';
?>
</div>
</body>
</html>


<script type="text/javascript">

    function myFunction() {
        document.getElementById("imploded").select();
    }
</script>