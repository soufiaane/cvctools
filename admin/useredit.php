<?php session_start();
if (!isset($_SESSION['name'])) {
    header("location:../login.php");
} else {
    $sessionname = $_SESSION['name'];
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    if ($dbconn) {
        $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
        $result = mysqli_query($dbconn, $sql);
        $data = mysqli_fetch_assoc($result);
        if ($data['p_ad_u_permission'] == 0) {
            header("location:../noperm.php");
        }
        mysqli_free_result($result);
    }
    mysqli_close($dbconn);
}
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
                <center><h1 class="title">Users Permissions</h1></center>
                <br><br>
                <center><h3><font color="b8b8b8">Edit account</font></h3></center>
                <br><br>
            </div>
        </div>
        <?php
        $u = $_GET['u'];
        $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
        if ($dbconn) {
            $sql = "SELECT * FROM users WHERE id='$u'";
            $result = mysqli_query($dbconn, $sql);
            $data = mysqli_fetch_assoc($result);
            $username = $data['username'];
            $passwd = $data['passwd'];
            $fullname = $data['fullname'];
            $entity = $data['entity'];
            $implode = $data['p_implode'];
            $dnsbl = $data['p_dnsbl'];
            $dbl = $data['p_dbl'];
            $spf = $data['p_spf'];
            $rdns = $data['p_rdns'];
            $findsub = $data['p_findsub'];
            $mbc = $data['p_mbc'];
            $ipcheck = $data['p_ipcheck'];
            $ipcheck_admin = $data['p_ipcheck_admin'];
            $admin = $data['p_admin'];
            $ad_u_perm = $data['p_ad_u_permission'];
            $ad_u_teams = $data['p_ad_u_teams'];
            $teamleader = $data['p_teamleader'];

        } else {
            echo '<div class="alert alert-danger" role="alert"><b>Can not Connect to database</b></div>';
        }
        ?>
        <div class="row">
            <div class="col-md-2"></div>
            <form action="<?php echo $_SERVER["PHP_SELF"] . '?u=' . $u; ?>" method="POST">
                <div class="col-md-8 well">
                    <div class="col-md-3 form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="imp" value="implode"
                                          id="cuser" <?php if ($implode == 1) echo 'checked' ?>></input>

                                <p>Ip's implode Tool</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="dnsbl" value="dnsbl"
                                          id="cuser" <?php if ($dnsbl == 1) echo 'checked' ?>>

                                <p>Check DNSBL's Blocklist</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="dbl" value="dbl"
                                          id="cuser" <?php if ($dbl == 1) echo 'checked' ?>>

                                <p>Check Domains Blacklist</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="spf" value="spf"
                                          id="cuser" <?php if ($spf == 1) echo 'checked' ?>>

                                <p>SPF Bulk Check Tool</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="rdns" value="rdns"
                                          id="cuser" <?php if ($rdns == 1) echo 'checked' ?>>

                                <p>Bulk rDNS Check Tool</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="finds" value="findsub"
                                          id="cuser" <?php if ($findsub == 1) echo 'checked' ?>>

                                <p>Yahoo Find Subject</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="mbc" value="mbc"
                                          id="cuser" <?php if ($mbc == 1) echo 'checked' ?>>

                                <p>Mailboxes bulk checker</p></label>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="ipcheck" value="ipcheck"
                                          id="cuser" <?php if ($ipcheck == 1) echo 'checked' ?>>

                                <p>Ip neighbors</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="ipcheck_admin" value="ipcheck_admin"
                                          id="cuser" <?php if ($ipcheck_admin == 1) echo 'checked' ?>>

                                <p class="text-warning">Ip neighbors add/delete</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="teamleader" value="teamleader"
                                          id="cuser" <?php if ($teamleader == 1) echo 'checked' ?>>

                                <p class="text-warning">Is Team Leader</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="admin" value="admin"
                                          id="cuser" <?php if ($admin == 1) echo 'checked' ?>>

                                <p class="text-danger">Administration panel</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="ad_u_perm" value="ad_u_perm"
                                          id="cuser" <?php if ($ad_u_perm == 1) echo 'checked' ?>>

                                <p class="text-danger">Manage Permissions</p></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="ad_u_teams" value="ad_u_teams"
                                          id="cuser" <?php if ($ad_u_teams == 1) echo 'checked' ?>>

                                <p class="text-danger">Manage users teams</p></label>
                        </div>
                        <!--<div class="checkbox">
                            <label><input type="checkbox" onClick="toggle(this)" /><p>Toggle All</p></label>
                        </div>!-->
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="login" class="col-md-4 control-label">Login:</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="login" value="<?php echo $username ?>">
                        </div>
                        <br><br><br>
                        <label for="fullname" class="col-md-4 control-label">Full Name:</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="fullname" value="<?php echo $fullname ?>">
                        </div>
                        <br><br><br>
                        <label for="password" class="col-md-4 control-label">Password:</label>

                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password" value="<?php echo $passwd ?>">
                        </div>
                        <br><br><br>
                        <label for="entite" class="col-md-4 control-label">entity:</label>

                        <div class="col-md-8">
                            <label class="radio-inline"><input type="radio" name="entity"
                                                               value="cvc1" <?php if ($entity == 'cvc1') echo 'checked' ?>>cvc
                                1</label>
                            <label class="radio-inline"><input type="radio" name="entity"
                                                               value="cvc2" <?php if ($entity == 'cvc2') echo 'checked' ?>>cvc
                                2</label>
                            <label class="radio-inline"><input type="radio" name="entity"
                                                               value="cvc3" <?php if ($entity == 'cvc3') echo 'checked' ?>>cvc
                                3</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-5">
                    <center>
                        <button class="btn btn-primary" type="submit" name="save">Save Changes</button>
                    </center>
                </div>
            </form>
            <?php
            if (isset($_POST['save'])) {
                $nusername = ($_POST['login'] == $username ? $username : $_POST['login']);
                $nfullname = ($_POST['fullname'] == $fullname ? $fullname : $_POST['fullname']);
                $npassword = ($_POST['password'] == $passwd ? $passwd : $_POST['password']);
                $nentity = ($_POST['entity'] == $entity ? $entity : $_POST['entity']);
                $nimp = (!isset($_POST['imp']) ? 0 : 1);
                $ndnsbl = (!isset($_POST['dnsbl']) ? 0 : 1);
                $ndbl = (!isset($_POST['dbl']) ? 0 : 1);
                $nspf = (!isset($_POST['spf']) ? 0 : 1);
                $nrdns = (!isset($_POST['rdns']) ? 0 : 1);
                $nfinds = (!isset($_POST['finds']) ? 0 : 1);
                $nmbc = (!isset($_POST['mbc']) ? 0 : 1);
                $nipcheck = (!isset($_POST['ipcheck']) ? 0 : 1);
                $nipcheck_admin = (!isset($_POST['ipcheck_admin']) ? 0 : 1);
                $nadmin = (!isset($_POST['admin']) ? 0 : 1);
                $nad_u_perm = (!isset($_POST['ad_u_perm']) ? 0 : 1);
                $nad_u_teams = (!isset($_POST['ad_u_teams']) ? 0 : 1);
                $teamleader = (!isset($_POST['teamleader']) ? 0 : 1);

                $nsql = "UPDATE users SET username='$nusername', passwd='$npassword', fullname='$nfullname', entity='$nentity', p_implode=$nimp, p_dnsbl=$ndnsbl, p_dbl=$ndbl, p_spf=$nspf, p_rdns=$nrdns, p_findsub=$nfinds, p_mbc=$nmbc, p_ipcheck=$nipcheck, p_ipcheck_admin=$nipcheck_admin, p_admin=$nadmin, p_ad_u_permission=$nad_u_perm, p_ad_u_teams=$nad_u_teams, p_teamleader=$teamleader WHERE id=$u";
                $nresult = mysqli_query($dbconn, $nsql);
                if (!$nresult) {
                    echo '<div class="alert alert-danger" role="alert"><b>Can not update database</b></div>';
                    die (mysqli_error($dbconn));
                } else {
                    echo '<div class="alert alert-success" role="alert"><b>User details has been updated</b></div>';
                }
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>


<!--<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsById("cuser");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>!-->