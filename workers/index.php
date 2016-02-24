<?php
session_start();
if (!isset($_POST['logout'])) {
    session_destroy();
}
?>
<?php
if (!isset($_SESSION['lvl'])) {
    $logged = -1;
} else {
    $logged = $_SESSION['lvl'];
}
?>
<!DOCTYPE html>
<head>
    <?php
    include "dbconnect.php";
    include 'heaeder.php';
    ?>
</head>
<body>
<?php include "menu.php" ?>

<!-- Modal -->
<div id="login" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Login</h4>
            </div>
            <div class="modal-body" id="m">
                <center>
                    Username: <input placeholder="Username" type="text" required id="username"></input><br/><br/>
                    Password: <input placeholder="Password" type="password" required id="password"></input>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="login();">Login</button>
                <button type="button" class="btn btn-danger"
                        onclick="swal('Error!', 'You must be logged to access timetable!', 'error');">Cancel
                </button>
            </div>
        </div>

    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        var lgd = "<?=$logged?>";

        if (lgd == -1) {
            $("#login").modal();
        } else {
            if (lgd != '0') {
                $("#admin_m").hide();
                $("#workers_m").hide();
            }
            window.location.href = "time_table.php";
        }
    });
    function login() {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {'what': "login", "username": $("#username").val(), "password": $("#password").val()},
            success: function (result) {

                var lvl = result.split("|")[1];
                if (lvl == -1) {
                    swal('Error!', 'Wrong login data!', 'error');
                } else {
                    swal({
                            title: "Success!",
                            text: "Welcome " + result.split("|")[2],
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Continue",
                            closeOnConfirm: true
                        },
                        function () {
                            window.location.href = "setSession.php?lvl=" + result.split("|")[1] + "&id=" + result.split("|")[3] + "&name=" + result.split("|")[2];
                        });
                }
            }
        });
    }
</script>
</html>