<?php
session_start();
session_destroy();
?>
<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("includes/inc.php"); ?>
    <link rel="stylesheet" type="text/css" href="/includes/css/signin.css">
</head>

<body>
<?php include("navbar.php"); ?>
<?php include("navbar-end.php"); ?>
<div class="container">
    <form class="form-signin" method="post" action="">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="login" class="sr-only">Login</label>
        <input type="text" id="login" class="form-control" name="login" placeholder="Login" required autofocus><br>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
    <!--<center><a href="signup.php">Creat a New account</a></center>-->
</div>

<?php

$dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
if ($dbconn) {
    if (isset($_POST['login']) && isset($_POST['password'])) {

        $myusername = $_POST['login'];
        $mypassword = $_POST['password'];

        $myusername = stripslashes($myusername);
        $mypassword = stripslashes($mypassword);
        $myusername = mysqli_real_escape_string($dbconn, $myusername);
        $mypassword = mysqli_real_escape_string($dbconn, $mypassword);
        $sql = "SELECT * FROM users WHERE username='$myusername' and passwd='$mypassword'";
        $result = mysqli_query($dbconn, $sql);
        $data = mysqli_fetch_assoc($result);

        $count = mysqli_num_rows($result);

        if ($count == 1) {

            session_start();
            $_SESSION['name'] = $data['fullname'];
            $_SESSION['username'] = $data['username'];
            header("location:http://cvctools.com");
        } else {
            echo '<div class="alert alert-danger" role="alert"><b>Wrong Username Or Password, Please try again</b></div>';
        }
    }
}
?>

</body>
</html>