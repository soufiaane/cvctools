<?php
session_start();
?>
<?php
if (!isset($_SESSION["lvl"])) {
    $logged = -1;
} else {
    $logged = $_SESSION["lvl"];
}
if ($logged == "-1") {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php
    include "dbconnect.php";
    include 'heaeder.php';
    ?>
</head>
<body>
<?php include "menu.php" ?>
<center><h2 id="mainname" style="margin-top:10px;">Welcome <?= $_SESSION["name"] ?></h2></center>

<hr/>

<?php
if ($logged == "0") {
    ?>
    <center><h2 style="margin-top:10px;">Edit shifts</h2>
        <hr/>
        <table>
            <thead>
            <tr>
                <th style='min-width:50px;width:50px;max-width:50px;'>Delete</th>
                <th style='min-width:300px;width:300px;max-width:300px;'>Shift name</th>
                <th style='min-width:100px;width:100px;max-width:100px;'>Color</th>
            </tr>
            </thead>
            <tbody id="shifts">
            </tbody>
        </table>
    </center>
<?php } ?>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        var lvl = "<?=$logged?>";
        var lgd = "<?=$logged?>";

        if (lvl != '0') {
            $("#admin_m").hide();
            $("#workers_m").hide();
        }

        if (lvl == "0") {
            collectShifts();
        }
    });
    function collectShifts() {
        document.getElementById("shifts").innerHTML = "";
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {'what': "getshifts"},
            success: function (result) {

                var r = jQuery.parseJSON(result);
                $.each(r, function () {
                    createShiftRow(this);
                });
                setTimeout(function () {

                    $('.simple_color').simpleColor({
                        cellWidth: 15,
                        cellHeight: 15
                    }, 500);
                    $('.simple_color').css("border", "none");
                });
            }
        });
    }
    function createShiftRow(r) {
        var ntr = document.createElement("TR");
        ntr.setAttribute("id", r.id);

        var ntd = document.createElement("TD");
        ntd.setAttribute("id", r.id);
        ntd.innerHTML = "";
        ntd.style.cursor = "pointer";
        $(ntd).bind("click", function () {
            this.parentNode.style.backgroundColor = "red";
            var ths = this;
            swal({
                title: "Are you sure? Shift will be deleted?",
                text: "You will not be able to undo this!",
                type: "warning", showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {"what": "deleteshift", "id": ths.getAttribute("id")},
                        success: function (result) {
                            var r = jQuery.parseJSON(result);
                            document.getElementById("shifts").innerHTML = "";
                            $.each(r, function () {
                                createShiftRow(this);
                            });
                            setTimeout(function () {

                                $('.simple_color').simpleColor({
                                    cellWidth: 15,
                                    cellHeight: 15
                                }, 500);
                                $('.simple_color').css("border", "none");
                            });
                        }
                    });
                    swal("Deleted!", "Shift has been deleted.", "success");
                } else {
                    $("#shifts tr").css("background", "white");
                    swal("Cancelled", "Shift is safe!", "error");
                }
            });
        });
        ntr.appendChild(ntd);

        var ntd = document.createElement("TD");
        ntd.setAttribute("id", r.id);
        ntd.innerHTML = "<input style='width:100%; border:none; padding:0 10px;' onchange='updateName(this);' type='text' id='name" + r.id + "' value='" + r.name + "' placeholder='Shift name'></input>";
        ntr.appendChild(ntd);

        var ntd = document.createElement("TD");
        ntd.setAttribute("id", r.id);
        ntd.style.width = "50px";
        ntd.style.maxWidth = "50px";
        ntd.style.minWidth = "50px";
        ntd.innerHTML = "<input style='width:100%;' onchange='changedColor(this);' class='simple_color' style='dicursor:pointer;border:none;background-color:transparent;width:50px;' type='text' id='color" + r.id + "' value='" + r.color + "' placeholder='Shift color'></input>";
        ntd.style.backgroundColor = r.color;
        ntr.appendChild(ntd);

        document.getElementById("shifts").appendChild(ntr);

    }
    function updateName(obj) {
        updateRow(obj.parentNode.parentNode);
    }
    function changedColor(obj) {
        obj.parentNode.style.backgroundColor = obj.value;
        updateRow(obj.parentNode.parentNode);
    }
    function updateShiftRow(row, index) {
        try {
            var rw = $($("#shifts")).children("tr")[index];
            rw.setAttribute("id", row.id);
            $($(rw).children("td")[1]).attr("id", row.id);
            $($(rw).children("td")[0]).attr("id", row.id);
            $($(rw).children("td")[2]).attr("id", row.id);
            $($(rw).children("td")[1]).children("input")[0].value = row.name;
            $($(rw).children("td")[2]).children("input")[0].value = row.color;

        } catch (err) {
            document.getElementById("shifts").appendChild($("#shifts tr:last")[0].cloneNode(true));
            var rw = $($("#shifts")).children("tr")[index];
            rw.setAttribute("id", row.id);
            $($(rw).children("td")[1]).attr("id", row.id);
            $($(rw).children("td")[0]).attr("id", row.id);
            $($(rw).children("td")[2]).attr("id", row.id);
            $($(rw).children("td")[1]).children("input")[0].value = "";
            $($(rw).children("td")[1]).children("input")[0].setAttribute("placeholder", "Shift name");
            $($(rw).children("td")[2]).children("input")[0].value = "";
            $($(rw).children("td")[0]).bind("click", function () {
                if (this.getAttribute("id") != "-1") {
                    this.parentNode.style.backgroundColor = "red";
                    var ths = this;
                    swal({
                        title: "Are you sure? Shift will be deleted?",
                        text: "You will not be able to undo this!",
                        type: "warning", showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                type: "POST",
                                url: "ajax.php",
                                data: {"what": "deleteshift", "id": ths.getAttribute("id")},
                                success: function (result) {
                                    var r = jQuery.parseJSON(result);
                                    document.getElementById("shifts").innerHTML = "";
                                    $.each(r, function () {
                                        createShiftRow(this);
                                    });
                                    setTimeout(function () {

                                        $('.simple_color').simpleColor({
                                            cellWidth: 15,
                                            cellHeight: 15
                                        }, 500);
                                        $('.simple_color').css("border", "none");
                                    });
                                }
                            });
                            swal("Deleted!", "Shift has been deleted.", "success");
                        } else {
                            $("#shifts tr").css("background", "white");
                            swal("Cancelled", "Shift is safe!", "error");
                        }
                    });
                }
            });
        }
    }
    function updateRow(r) {
        var tid = r.id;

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                'what': "updateshift",
                "id": r.getAttribute("id"),
                "name": r.getElementsByTagName("INPUT")[0].value,
                "color": r.getElementsByTagName("INPUT")[1].value
            },
            success: function (result) {
                var r = jQuery.parseJSON(result);
                $.each(r, function (index) {
                    updateShiftRow(this, index);
                });


            }
        });
    }
</script>
</html>