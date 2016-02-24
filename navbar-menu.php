<?php
$username = @$_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($dbconn, $sql);
$data = mysqli_fetch_assoc($result);
?>

<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <?php
        if ($data['p_admin'] == 1) {
            ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false"><span class="glyphicon glyphicon-lock"></span> Admin Panel<span
                        class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php if ($data['p_ad_u_permission'] == 1) { ?>
                        <li><a href="http://cvctools.com/admin/users.php">User permissions</a></li>
                        <li><a href="http://cvctools.com/signup2.php">Add User</a></li>
                    <?php } ?>
                    <?php if ($data['p_ad_u_teams'] == 1) { ?>
                        <li><a href="http://cvctools.com/admin/teams.php">Users Teams</a></li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
    ';
</div>