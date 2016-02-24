<?php
session_start();
?>
<?php
include "dbconnect.php";
if ($_POST["what"] == "login") {
    $workers = $conn->prepare("SELECT * FROM workers where username='" . $_POST["username"] . "' AND password='" . $_POST["password"] . "'");
    $workers->execute();
    $w = $workers->fetchAll();
    foreach ($w as $row) {
        $rows[] = $row;
    }
    echo count($rows) . "|" . ((count($rows) > 0) ? $rows[0]["level"] : "-1") . "|" . ((count($rows) > 0) ? $rows[0]["name"] : "") . "|" . ((count($rows) > 0) ? $rows[0]["id"] : "");
    exit();
}
if ($_POST["what"] == "getshifts") {
    $workers = $conn->prepare("SELECT * FROM shifts order by `id`");
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    $rows = [];
    foreach ($t as $row) {
        $rows[] = $row;
    }
    $rows[] = Array("id" => "-1", "name" => "", "color" => "");
    echo json_encode($rows);

}
if ($_POST["what"] == "getworkers") {
    $rows = [];
    $workers = $conn->prepare("SELECT * FROM workers order by `level` ASC,`name` ASC");
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    $rows[] = Array("id" => "-1", "name" => "", "level" => "2", "team" => "0", "username" => "", "password" => "", "team_name" => "");
    echo json_encode($rows);

}
if ($_POST["what"] == "updateshift") {
    if ($_POST["id"] == "-1") {
        $workers = $conn->prepare("INSERT INTO shifts (name,color) VALUES('" . $_POST["name"] . "','" . $_POST["color"] . "')");
        $workers->execute();

    } else {
        $workers = $conn->prepare("UPDATE shifts SET name='" . $_POST["name"] . "',color='" . $_POST["color"] . "' WHERE id='" . $_POST["id"] . "'");
        $workers->execute();

    }
    $workers = $conn->prepare("SELECT * FROM shifts order by `id`");
    $rows = [];
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    $rows[] = Array("id" => "-1", "name" => "", "color" => "");
    echo json_encode($rows);
}
if ($_POST["what"] == "deleteshift") {
    $workers = $conn->prepare("DELETE FROM shifts WHERE id='" . $_POST["id"] . "'");
    $workers->execute();
    $rows = [];
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    $rows[] = Array("id" => "-1", "name" => "", "color" => "");
    echo json_encode($rows);
}
if ($_POST["what"] == "deleteworker") {
    $workers = $conn->prepare("DELETE FROM workers WHERE id='" . $_POST["id"] . "'");
    $workers->execute();
    $rows = [];
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    $rows[] = Array("id" => "-1", "name" => "", "level" => "2", "team" => "0", "username" => "", "password" => "", "team_name" => "");
    echo json_encode($rows);
}
if ($_POST["what"] == "executequery") {
    $workers = $conn->prepare("UPDATE " . $_POST["table"] . " SET " . $_POST["field"] . "='" . $_POST["vl"] . "'  WHERE id='" . $_POST["id"] . "'");
    $workers->execute();
    $_SESSION[$_POST["field"]] = $_POST["vl"];
}
if ($_POST["what"] == "updateworker") {
    if ($_POST["id"] == "-1") {
        $workers = $conn->prepare("INSERT INTO workers (name,level,team,username,password,team_name) VALUES('" . $_POST["name"] . "','" . $_POST["level"] . "','" . $_POST["team"] . "','" . $_POST["username"] . "','" . $_POST["password"] . "','" . $_POST["team_name"] . "')");
        $workers->execute();
    } else {
        $workers = $conn->prepare("UPDATE workers SET name='" . $_POST["name"] . "',level='" . $_POST["level"] . "',username='" . $_POST["username"] . "',team='" . $_POST["team"] . "',password='" . $_POST["password"] . "',team_name='" . $_POST["team_name"] . "' WHERE id='" . $_POST["id"] . "'");
        $workers->execute();
    }
    $workers = $conn->prepare("SELECT * FROM workers order by `level`,`name`");
    $rows = [];
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    $rows[] = Array("id" => "-1", "name" => "", "level" => "2", "team" => "0", "username" => "", "password" => "", "team_name" => "");
    echo json_encode($rows);
}
if ($_POST["what"] == "getteams") {
    $workers = $conn->prepare("SELECT * FROM workers WHERE team=id order by `id`");
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }

    echo json_encode($rows);
}
if ($_POST["what"] == "getmembers") {
    $workers = $conn->prepare("SELECT id,name,team,level FROM workers WHERE team='" . $_POST["team"] . "' order by `level` ASC,`name` ASC");
    $rows = [];
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}
if ($_POST["what"] == "getshft") {
    $workers = $conn->prepare("SELECT shift_id FROM time_table WHERE worker_id='" . $_POST["id"] . "' and date='" . $_POST["date"] . "'");
    $rows = [];
    $r = "0";
    $workers->execute();
    $t = $workers->fetchAll(PDO::FETCH_ASSOC);
    foreach ($t as $row) {
        $rows[] = $row;
    }
    if (count($rows) == 0) {
        $workers = $conn->prepare("INSERT into time_table (worker_id,date,shift_id) values ('" . $_POST["id"] . "','" . $_POST["date"] . "','0')");
        $workers->execute();
        $rows[] = Array("shift_id" => "0");
    }
    $r = $rows[0]["shift_id"];
    echo trim($r);
}
if ($_POST["what"] == "updshift") {
    $workers = $conn->prepare("UPDATE  time_table SET shift_id='" . $_POST["vl"] . "' WHERE worker_id='" . $_POST["id"] . "' and date='" . $_POST["date"] . "'");
    $workers->execute();

}
?>