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
include("../navbar-all.php");
$time = (int)date('Gis');
$date = date('Ymd');
?>
<div class="col-md-2">
    <?php include("../sidebar.php"); ?>
</div>
<div class="col-md-2 col-md-offset-4">
    <center><h1 class="title">Food Order</h1></center>
    <br><br><br>
</div>
<div class="col-md-8 col-md-offset-1">
    <div class="well well-lg col-md-12">
        <center>
            <?php
            $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
            if ($dbconn) {
                if ($time >= 50000 && $time <= 143000) {
                    echo '<div id="full-alert" class="alert alert-info"><b>Important !</b> Time interval to order request for Dinner : from 09h to 10h30 <span class="glyphicon glyphicon-time" aria-hidden="true"></span></div>';
                } else {
                    echo '<div id="full-alert" class="alert alert-info"><b>Important !</b> Time interval to order request for Dinner : from 15h to 17h <span class="glyphicon glyphicon-time" aria-hidden="true"></span></div>';
                }
                if (($time >= 90000 && $time <= 103000) || ($time >= 150000 && $time <= 170000)) {
                    if (isset($_POST['submit'])) {
                        if (!isset($_POST['order']) || $_POST['order'] == null) {
                            echo '<div class="alert alert-warning"><b>Warning !</b> Your Order empty <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></div>';
                        } else {
                            $order = $_POST['order'];
                            $sql = "SELECT * FROM users WHERE fullname='$sessionname'";
                            $result = mysqli_query($dbconn, $sql);
                            $data2 = mysqli_fetch_assoc($result);
                            $entity = $data2['entity'];
                            if (isset($entity) && $entity != '' && isset($sessionname) && $sessionname != '') {
                                $sql = "INSERT INTO foodorder(`id`, `order`, `user`, `time`, `date`, `entity`) VALUES ('', '$order', '$sessionname', '$time', '$date', '$entity')";
                                if (mysqli_query($dbconn, $sql)) {
                                    echo '<center><div class="alert alert-success" role="alert"><b>Note !</b> Your Order is Inserted <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></div></center><br>';
                                } else {
                                    echo '<center><div class="alert alert-danger" role="alert"><b>Error !</b> Can not insert your order</div></center>';
                                }
                            }
                        }
                    }
                    if ($time < 143000) {
                        $sql = "SELECT * FROM foodorder WHERE user='$sessionname' and date='$date' and time < '143000'";
                    } elseif ($time > 143000) {
                        $sql = "SELECT * FROM foodorder WHERE user='$sessionname' and date='$date' and time > '143000'";
                    }
                    $result = mysqli_query($dbconn, $sql);
                    $c = mysqli_num_rows($result);
                    $data = mysqli_fetch_assoc($result);
                    $order = $data['order'];
                    if (isset($_POST['delete'])) {
                        if ($c > 0) {
                            $sql = "DELETE FROM foodorder WHERE user='$sessionname'";
                            if (mysqli_query($dbconn, $sql)) {
                                echo '<center><div class="alert alert-danger" role="alert">Your order has been deleted</div></center>';
                            }
                        }
                    }
                    ?>
                    <div class="well col-md-12">
                        <label class="control-label col-md-1 col-md-offset-1" for="order">Order :</label>

                        <div class="col-md-8">
                            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                <textarea class="form-control" rows="10"
                                          name="order" <?php if ($c > 0) echo 'disabled=""'; ?>><?php echo $data['order'] ?></textarea>
                                <font color="grey" size="2"><i>Separate multiple orders by ( Entree ) New
                                        Line</i></font><br><br>
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                <!--<button type="submit" class="btn btn-success" name="edit">Edit</button>-->
                                <button class="btn btn-default" type="reset">Reset</button>
                                <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                            </form>
                        </div>
                        <div class="col-md-1"><br><br><br><br><br><font color="red" size=3>*</font></div>
                    </div>
                <?php
                } else {
                    echo '<div class="alert alert-danger"><b>Info !</b> Time Out <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></div>';
                }
            } else {
                echo '<center><div class="alert alert-danger" role="alert">Can not connect to the database</div></center>';
            }
            ?>
        </center>
    </div>
    <div class="col-md-3 col-md-offset-4">
        <a href="display.php" class="btn btn-default btn-sm btn-block" role="button">Display orders<span
                class="glyphicon glyphicon-import"></span></a>
    </div>
</div>
</body>
</html>