<?php
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    if (!$con) {
        //エラー
        echo("DB connect error");
        exit;
    }
    else {
        //echo("connected successfly\n");
    }
    $user_id = $_POST["user_id"];
    $table_id = $_POST["table_id"];
    $column_id = $_POST["column_id"];
    $data = $_POST["data"];

    $SQL = "INSERT INTO data ";
    $SQL .= "(data, user_id, table_id, column_id, creation, update, row) ";
    $SQL .= "SELECT '{$data}', '{$user_id}', '{$table_id}', '{$column_id}', current_timestamp, current_timestamp, COALESCE(MAX(row) + 1, 1) ";
    $SQL .= "FROM data WHERE table_id = '{$table_id}'";
    //echo($SQL);
    $res = pg_query($con, $SQL);
    if (!$res) {
        //エラー
        echo("error: ".$SQL);
        exit;
    }
    else {
        echo(0);
        exit;
    }


?>
