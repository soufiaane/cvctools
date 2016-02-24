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
$datedisp = date('l d/m/Y');
?>
<div class="col-md-2 col-md-offset-4">
    <center><h1 class="title">Food Order</h1></center>
    <br><br><br>
</div>
<div class="col-md-8 col-md-offset-1">
    <?php
    $dbconn = mysqli_connect("localhost", "c0cvcadmin", "wl8B#1JXnc", "c0cvctools");
    if ($dbconn){
    if ($time < 143000) {
        $sql = "SELECT * FROM foodorder WHERE date='$date' AND time < '143000'";
    } elseif ($time > 143000) {
        $sql = "SELECT * FROM foodorder WHERE date='$date' AND time > '143000'";
    }
    $result = mysqli_query($dbconn, $sql);
    echo '<table id="table_cvc1" class="table table-striped table-bordered" cellspacing="0" width="100%" style="" border="1" border-collapse="collapse" style="font-                            family: Helvetica Neue,Helvetica,Arial,sans-serif">';
    echo '<caption class="visible-print-block"><img src="../img/logo-min.jpg"></img><br><br><font color="#828282" size="5">Cap Value Consulting - Food                                 Order</font><br>' . $datedisp . '<br><br></caption>';
    ?>
    <caption class="hide-print"><font color="#828282" size="3">Cap Value Consulting - Food
            Order</font><br><?php echo $datedisp; ?><br></caption>
    <thead>
    <tr>
        <th>Username</th>
        <th>Order</th>
        <th>Entity</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <?php
    while ($data = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <?php echo '<td>' . $data['user'] . '</td>'; ?>
            <td><?php echo nl2br($data['order']); ?></td>
            <td><?php echo $data['entity']; ?></td>
            <td></td>
        </tr>

    <?php
    }
    echo '</tbody>';
    echo '</table>';
    }
    ?>
    <br>
    <center>
        <button class="btn btn-default" id="printbtn">Print Table</button>
    </center>
</div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        $('#table_cvc1').DataTable({
            "autoWidth": false,
            "searching": false,
            "paging": false,
            "order": [[2, "asc"]]
        });
    });


    function printData() {
        var cvc1 = document.getElementById("table_cvc1");
        newWin = window.open("");
        newWin.document.write(cvc1.outerHTML);
        newWin.print();
        newWin.close();
    }

    $('#printbtn').on('click', function () {
        printData();
    })
</script>