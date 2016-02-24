<?php
session_start();
?>
<?php
if (!isset($_SESSION["lvl"]) && !isset($_SESSION["name"])) {
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
    include 'heaeder.php';
    ?>
</head>
<body>
<?php include "menu.php" ?>
</body>
<center><h2 id="mainname" style="margin-top:10px;">Welcome <?= $_SESSION["name"] ?></h2></center>
<hr/>

<center>
    <h2 style="margin-top:10px;">Time tabes</h2>

    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            Choose week: <select class="form-control" onchange="startDrawing();" id="week"></select>
        </div>
    </div>
</center>
<hr/>
</body>
<script type="text/javascript">

    var end = new Date();
    var ss = document.createElement("SELECT");
    var shft = "";
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    shft += "<option  value='" + "0" + "' style='font-size:14px;padding:3px;'><span style='font-size:15px;padding:3px;'>" + "" + "</span></option>";
    $(document).ready(function () {
        var lvl = "<?=$_SESSION["lvl"]?>";
        if (lvl != '0') {
            $("#admin_m").hide();
            $("#workers_m").hide();
        }

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {'what': "getshifts"},
            success: function (result) {

                var r = jQuery.parseJSON(result);
                $.each(r, function () {
                    if (this.id != "-1") {
                        shft += "<option  value='" + this.id + "' style='cursor:pointer;background-color:" + this.color + ";font-size:14px;padding:3px;'><span style='font-size:15px;padding:3px;'>" + this.name + "</span></option>";
                    }
                });


                ss.className = "shifts";
                ss.innerHTML = shft;

            }
        });
        populate_week_range_options();
        startDrawing();
    });
    function startDrawing() {
        var start = new Date($("#week").val());
        end = end.setDate(start.getDate() + 7);
        end = new Date(end);

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {'what': "getteams"},
            success: function (result) {
                var r = jQuery.parseJSON(result);
                $.each(r, function () {
                    var dv = document.createElement("DIV");
                    dv.id = "div" + this.team;
                    document.body.appendChild(dv);
                    var ths = this;
                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {'what': "getmembers", "team": this.team},
                        success: function (result) {
                            var usrid = "<?=$_SESSION["id"]?>";
                            var lvl = "<?=$_SESSION["lvl"]?>";
                            var inr = "<center><h3  style='margin-top:5px;'>" + ths.team_name + "</h3></center><hr />";
                            inr += "<center><table id='table" + ths.id + "'><thead><tr>";
                            inr += "<th style='min-width:300px;width:300px;max-width:300px;'>Name</th>";

                            for (i = 0; i < 7; i++) {
                                var curr = new Date();
                                curr = curr.setDate(start.getDate() + i);
                                curr = addDays(start, i);

                                var sqldate = curr.getFullYear() + "-" + (curr.getMonth() + 1) + "-" + curr.getDate();
                                var dd = curr.getDay();
                                curr = curr.toLocaleDateString();
                                inr += "<th style='min-width:100px;width:100px;max-width:100px;'sqldate='" + sqldate + "'><center>" + days[dd] + "<br />" + curr + "</th>";
                            }
                            inr += "</tr></thead><tbody>";
                            var m = jQuery.parseJSON(result);
                            $.each(m, function () {

                                inr += "<tr " + ((this.level == "1") ? "style='font-weight:bold;'" : "") + "><td>" + this.name + "</td>";
                                for (i = 0; i < 7; i++) {
                                    curr = addDays(start, i);
                                    var nd = addDays(new Date(), -1);
                                    var sqldate = curr.getFullYear() + "-" + (curr.getMonth() + 1) + "-" + curr.getDate();
                                    var lck = ((usrid != this.team) && (lvl != "0")) ? "disabled" : "";

                                    if (curr < nd) {
                                        lck = "disabled";
                                    }
                                    inr += "<td wid='" + this.id + "' sqldate='" + sqldate + "'><select " + lck + " id='s" + this.id + i + "' wid='" + this.id + "' sqldate='" + sqldate + "' onchange='selectChanged(this);'>" + shft + "</select></td>";

                                }
                                inr += "</tr>";
                            });
                            inr += "</tbody></table></center><hr />";
                            $("#div" + ths.id).html(inr);
                        }
                    });

                });

            }

        });
        setTimeout(function () {
            collectShifts();
        }, 2000);
    }
    function addDays(date, amount) {
        var tzOff = date.getTimezoneOffset() * 60 * 1000,
            t = date.getTime(),
            d = new Date(),
            tzOff2;

        t += (1000 * 60 * 60 * 24) * amount;
        d.setTime(t);

        tzOff2 = d.getTimezoneOffset() * 60 * 1000;
        if (tzOff != tzOff2) {
            var diff = tzOff2 - tzOff;
            t += diff;
            d.setTime(t);
        }

        return d;
    }
    function collectShifts() {
        $.each($("select"), function () {
            var elid = this.id;
            var ths = this;
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {'what': "getshft", "id": ths.getAttribute("wid"), "date": ths.getAttribute("sqldate")},
                success: function (result) {
                    $.each($(document.getElementById(elid)).children("option"), function () {

                        if (parseInt($(this).attr("value")) == parseInt(result)) {
                            ths.style.backgroundColor = this.style.backgroundColor;
                            this.selected = true;
                        }
                    });
                }
            });
        });
    }
    function selectChanged(obj) {
        $.each($(obj).children("option"), function () {
            if ($(this).attr("value") == obj.value) {
                obj.style.backgroundColor = this.style.backgroundColor;
            }
        });
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                'what': "updshift",
                "id": obj.getAttribute("wid"),
                "date": obj.getAttribute("sqldate"),
                "vl": obj.value
            },
            success: function (result) {

            }
        });
    }
    function populate_week_range_options() {
        var tdy = new Date();
        var start_week_date = new Date(tdy.getFullYear() - 1, 11, 25); // no queries exist before this
        //console.log(start_week_date);
        var todays_date = new Date(tdy.getFullYear() + 1, 0, 7);
        var op = "<option value='" + start_week_date + "'>Select week</option>";
        document.getElementById("week").innerHTML += op;
        // array to hold week commencing dates
        var week_commencing_dates = new Array();
        week_commencing_dates.push(start_week_date);
        var poc = null;
        while (start_week_date < todays_date) {
            var next_date = start_week_date.setDate(start_week_date.getDate() + 1);

            start_week_date = new Date(next_date);
            //console.log(start_week_date);

            if (start_week_date.getDay() == 1) {
                var op = "<option value='" + start_week_date + "'>" + start_week_date.toLocaleDateString() + "</option>";
                document.getElementById("week").innerHTML += op;
            }

            //
        }
        var dd = new Date();
        $.each($("#week option"), function () {
            if (dd >= new Date(this.getAttribute("value"))) {
                $("#week").val(this.getAttribute("value"));
            }
        });
        return week_commencing_dates;
    }
</script>
</html>