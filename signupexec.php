<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("includes/inc.php"); ?>
</head>

<body>
<?php include("navbar-all.php"); ?>
<center><h1><span class="label label-default">Creat new account</span></h1></center>
<br><br><br>
<?php
$dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
if ($dbconn) {
    if ((!isset($_POST['username']) || $_POST['username'] == null) || (!isset($_POST['password']) || $_POST['password'] == null) || (!isset($_POST['password2']) || $_POST['password2'] == null) || (!isset($_POST['fullname']) || $_POST['fullname'] == null) || (!isset($_POST['entity']) || $_POST['entity'] == null)) {
        echo '<center><div class="alert alert-danger" role="alert">Please fill all required fields (*)</div></center>';
    } else {
        $username = $_POST['username'];
        $username = stripslashes($username);
        $username = mysqli_real_escape_string($dbconn, $username);

        $passwd = $_POST['password'];
        $passwd = stripslashes($passwd);
        $passwd = mysqli_real_escape_string($dbconn, $passwd);

        $passwd2 = $_POST['password2'];
        $passwd2 = stripslashes($passwd2);
        $passwd2 = mysqli_real_escape_string($dbconn, $passwd2);

        $fullname = $_POST['fullname'];
        $fullname = stripslashes($fullname);
        $fullname = mysqli_real_escape_string($dbconn, $fullname);

        $entity = $_POST['entity'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($dbconn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<center><div class="alert alert-danger" role="alert">The username has been used, please choose another username</div></center>';
        } else {
            if ($passwd != $passwd2) {
                echo '<center><div class="alert alert-danger" role="alert">The passwords not match</div></center>';
            } else {
                $sql = "INSERT INTO users (id, username, passwd, fullname, entity, p_implode, p_dnsbl, p_dbl, p_spf, p_rdns, p_findsub, p_mbc, p_ipcheck, p_admin, r_date) VALUES ('', '$username', '$passwd', '$fullname', '$entity', 1, 1, 1, 1, 1, 1, 1, 1, 0, NOW())";
                if (mysqli_query($dbconn, $sql)) {
                    echo '<center><div class="alert alert-success" role="alert">Account Created Succefully</div></center><br>';
                    echo '<div class="col-sm-4 col-sm-offset-4"><div class="well well-lg"><center>Return to Login page  <a href="http://cvctools.com/login.php"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span></a></center></div></div>';
                } else {
                    echo mysqli_error($dbconn);
                }
            }
        }
    }
} else {
    echo '<center><div class="alert alert-danger" role="alert">Can not connect to the database</div></center>';
}
?>
</body>
</html>