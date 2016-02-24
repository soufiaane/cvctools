<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:/login.php");
} else {
    $sessionname = $_SESSION['name'];
}
//error_reporting(0);
?>
<html>
<head>
    <?php
    require("/includes/class/PRclass.php");
    include("/includes/inc.php");
    ?>
    <script type="text/javascript" src="/includes/js/tableExport.js"></script>
    <script type="text/javascript" src="/includes/js/jquery.base64.js"></script>
</head>
<body>
<a href="#" onClick="$('#tab').tableExport({type:'excel',escape:'false',tableName:'yourTableName'});">XLS</a>

<table border="1" id="tab">
    <tr>
        <th>Domain name</th>
        <th>SPF Record</th>
        <th>Page Rank</th>
        <th>Alexa Rank</th>
    </tr>
    <?php
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    $sql = "SELECT team, p_teamleader, p_admin FROM users WHERE fullname = '$sessionname'";
    $result = mysqli_query($dbconn, $sql);
    $data = mysqli_fetch_assoc($result);
    $team = $data['team'];
    $tl = $data['p_teamleader'];
    $admin = $data['p_admin'];
    mysqli_free_result($result);
    $sql = "SELECT domain, gid, pgrank, rank from spf WHERE level='A' and uteam='$team' GROUP BY gid";
    $result = mysqli_query($dbconn, $sql) or die(mysqli_error($dbconn));
    $total = mysqli_num_rows($result);
    $i = 0;
    while ($data = mysqli_fetch_assoc($result)) {
        $domain = $data['domain'];
        $gid = $data['gid'];
        $pr = $data['pgrank'];
        $rank = $data['rank'];
        echo '<tr>';
        echo '<td>';
        echo $domain;
        echo '</td>';
        echo '<td>';
        $sql2 = "SELECT record FROM spf WHERE uteam='$team' and gid='$gid'";
        $result2 = mysqli_query($dbconn, $sql2);
        while ($data2 = mysqli_fetch_assoc($result2)) {
            echo $data2['record'];
            echo '<br>';
        }
        echo '</td>';
        echo '<td>' . $pr . '</td>';
        echo '<td>' . $rank . '</td>';
        echo '</tr>';
    }
    ?>
</table>
</body>
</html>

