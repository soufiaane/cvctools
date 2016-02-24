<?php
session_start();
?>
<?php
if (!isset($_SESSION["lvl"])) {
    $logged = -1;
} else {
    $logged = $_SESSION["lvl"];
}
if ($logged == "-1" || $logged == "2") {
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
<center><h2 style="margin-top:10px;">Edit workers</h2>
    <table>
        <thead>
        <tr>
            <th style='min-width:50px;width:50px;max-width:50px;'>Delete</th>
            <th style='min-width:250px;width:250px;max-width:250px;'>Full name</th>
            <th style='min-width:90px;width:90px;max-width:90px;'>Position</th>
            <th style='min-width:300px;width:300px;max-width:300px;'>Team name</th>
            <th style='min-width:150px;width:150px;max-width:150px;'>Username</th>
            <th style='min-width:100px;width:100px;max-width:100px;'>Password</th>
            <th style='min-width:300px;width:300px;max-width:300px;'>Set team name</th>
        </tr>
        <tbody id="workers">
        </tbody>
    </table>
</center>
</body>
<script type="text/javascript">
$(document).ready(function () {
    collectWorkers();

});
function collectWorkers() {
    document.getElementById("workers").innerHTML = "";
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {'what': "getworkers"},
        success: function (result) {
            var r = jQuery.parseJSON(result);
            $.each(r, function () {
                createWorkerRow(this);
            });
            setTeams();
        }
    });
}
function createWorkerRow(r) {
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
            title: "Are you sure? Worker will be deleted?",
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
                    data: {"what": "deleteworker", "id": ths.getAttribute("id")},
                    success: function (result) {
                        var r = jQuery.parseJSON(result);
                        document.getElementById("workers").innerHTML = "";
                        $.each(r, function () {
                            createWorkerRow(this);
                        });
                        setTeams();
                    }
                });
                swal("Deleted!", "Worker has been deleted.", "success");
            } else {

                $("#workers tr").css("background", "white");
                swal("Cancelled", "Worker is safe!", "error");
            }
        });
    });
    ntr.appendChild(ntd);

    var ntd = document.createElement("TD");
    ntd.setAttribute("id", r.id);
    ntd.innerHTML = "<input style='width:100%; border:none; padding:0 10px;' onchange='updateName(this);' type='text' id='name" + r.id + "' value='" + r.name + "' placeholder='Full name'></input>";
    ntr.appendChild(ntd);

    var ntd = document.createElement("TD");
    ntd.setAttribute("id", r.id);
    ntd.innerHTML = "<input style='width:100%; border:none; text-align:center;' onchange='updateName(this);' type='text' id='name" + r.id + "' value='" + r.level + "' placeholder='Level'></input>";
    ntr.appendChild(ntd);

    var ntd = document.createElement("TD");
    ntd.setAttribute("id", r.id);
    ntd.innerHTML = "<input style='display:none;'  type='text' id='name" + r.id + "' value='" + r.team + "'></input>";
    ntr.appendChild(ntd);

    var ntd = document.createElement("TD");
    ntd.setAttribute("id", r.id);
    ntd.innerHTML = "<input style='width:100%; border:none; padding:0 10px;' onchange='updateName(this);' type='text' id='name" + r.id + "' value='" + r.username + "' placeholder='Username'></input>";
    ntr.appendChild(ntd);

    var ntd = document.createElement("TD");
    ntd.setAttribute("id", r.id);
    ntd.innerHTML = "<input style='width:100%; border:none; padding:0 10px;' onchange='updateName(this);' type='text' id='name" + r.id + "' value='" + r.password + "' placeholder='Password'></input>";
    ntr.appendChild(ntd);

    var ntd = document.createElement("TD");
    ntd.setAttribute("id", r.id);
    if (r.level != "1") {
        var dsbld = "disabled";
    } else {
        var dslbd = "";
    }
    ntd.innerHTML = "<input " + dsbld + " style='width:100%; border:none; padding:0 10px;' onchange='updateName(this);' type='text' id='name" + r.id + "' value='" + r.team_name + "' placeholder='Set team name'></input>";
    ntr.appendChild(ntd);

    document.getElementById("workers").appendChild(ntr);

}
function updateName(obj) {
    updateRow(obj.parentNode.parentNode);
}
function setTeams() {
    var wrk = "<option value='0'>Select team</option>";
    $.each($("#workers tr"), function () {
        if (this.getAttribute("id") != "-1") {
            if ($($(this).children("td")[2])[0].getElementsByTagName("INPUT")[0].value == "1") {
                wrk += "<option value='" + this.getAttribute("id") + "'>" + $($(this).children("td")[6])[0].getElementsByTagName("INPUT")[0].value + "</option>"
            }
        }
    });

    $.each($("#workers tr"), function () {
        var ntd = $(this).children("TD")[3];
        try {
            ntd.removeChild(ntd.getElementsByTagName("SELECT")[0]);
        } catch (err) {

        }
        var slc = document.createElement("SELECT");
        slc.id = "select" + this.getAttribute("id");
        slc.onchange = function () {
            updateName(this);
        };
        slc.value = ntd.getElementsByTagName("INPUT")[0].value;
        slc.innerHTML = wrk;
        ntd.appendChild(slc);

        if ($($(this).children("TD")[2]).children("input")[0].value == "1") {
            slc.value = this.getAttribute("id");
            slc.disabled = true;
            slc.style.backgroundColor = "#d3d3d3";
            slc.style.fontWeight = "bold";
        } else {
            slc.value = ntd.getElementsByTagName("INPUT")[0].value;
        }
    });

}
function updateRow(r) {
    var tid = r.id;

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            'what': "updateworker",
            "id": r.getAttribute("id"),
            "name": r.getElementsByTagName("INPUT")[0].value,
            "level": r.getElementsByTagName("INPUT")[1].value,
            "team": r.getElementsByTagName("SELECT")[0].value,
            "username": r.getElementsByTagName("INPUT")[3].value,
            "password": r.getElementsByTagName("INPUT")[4].value,
            "team_name": r.getElementsByTagName("INPUT")[5].value
        },
        success: function (result) {

            var r = jQuery.parseJSON(result);
            $.each(r, function (index) {
                updateWorkerRow(this, index);
            });

            setTeams();
        }
    });
}
function updateWorkerRow(row, index) {
    try {
        var rw = $($("#workers")).children("tr")[index];
        rw.setAttribute("id", row.id);
        $($(rw).children("td")[1]).attr("id", row.id);
        $($(rw).children("td")[0]).attr("id", row.id);
        $($(rw).children("td")[2]).attr("id", row.id);
        $($(rw).children("td")[3]).attr("id", row.id);
        $($(rw).children("td")[4]).attr("id", row.id);
        $($(rw).children("td")[5]).attr("id", row.id);
        $($(rw).children("td")[6]).attr("id", row.id);
        $($(rw).children("td")[2]).attr("id", row.id);
        $($(rw).children("td")[1]).children("input")[0].value = row.name;
        $($(rw).children("td")[2]).children("input")[0].value = row.level;
        $($(rw).children("td")[3]).children("input")[0].value = row.team;
        $($(rw).children("td")[4]).children("input")[0].value = row.username;
        $($(rw).children("td")[5]).children("input")[0].value = row.password;
        $($(rw).children("td")[6]).children("input")[0].value = row.team_name;

    } catch (err) {

        document.getElementById("workers").appendChild($("#workers tr:last")[0].cloneNode(true));
        var rw = $($("#workers")).children("tr")[index];
        rw.setAttribute("id", row.id);
        $($(rw).children("td")[1]).attr("id", row.id);
        $($(rw).children("td")[0]).attr("id", row.id);
        $($(rw).children("td")[2]).attr("id", row.id);
        $($(rw).children("td")[3]).attr("id", row.id);
        $($(rw).children("td")[4]).attr("id", row.id);
        $($(rw).children("td")[5]).attr("id", row.id);
        $($(rw).children("td")[1]).children("input")[0].value = "";
        $($(rw).children("td")[1]).children("input")[0].setAttribute("placeholder", "Full name");

        $($(rw).children("td")[2]).children("input")[0].value = "";
        $($(rw).children("td")[2]).children("input")[0].setAttribute("placeholder", "Level");

        $($(rw).children("td")[3]).children("input")[0].value = "";
        $($(rw).children("td")[3]).children("input")[0].setAttribute("placeholder", "Select team");

        $($(rw).children("td")[4]).children("input")[0].value = "";
        $($(rw).children("td")[4]).children("input")[0].setAttribute("placeholder", "Usename");

        $($(rw).children("td")[5]).children("input")[0].value = "";
        $($(rw).children("td")[5]).children("input")[0].setAttribute("placeholder", "Password");

        $($(rw).children("td")[6]).children("input")[0].value = "";
        $($(rw).children("td")[6]).children("input")[0].setAttribute("placeholder", "Set team name");

        $($(rw).children("td")[2]).children("input")[0].value = "";
        $($(rw).children("td")[0]).bind("click", function () {
            if (this.getAttribute("id") != "-1") {
                this.parentNode.style.backgroundColor = "red";
                var ths = this;
                swal({
                    title: "Are you sure? Worker will be deleted?",
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
                            data: {"what": "deleteworker", "id": ths.getAttribute("id")},
                            success: function (result) {

                                var r = jQuery.parseJSON(result);
                                document.getElementById("workers").innerHTML = "";
                                $.each(r, function () {
                                    createWorkerRow(this);
                                });
                                setTeams();

                            }
                        });
                        swal("Deleted!", "Worker has been deleted.", "success");
                    } else {
                        $("#workers tr").css("background", "white");
                        swal("Cancelled", "Worker is safe!", "error");
                    }
                });
            }
        });
    }
}
</script>
</html>