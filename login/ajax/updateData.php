<?php
    require_once("/var/www/html/tango/func.php");

    $con = connectDB();
    if (!$con) {
        //エラー
        echo("DB connect error");
        exit;
    }
    else {
        echo("connected successfly\n");
    }
    $user_id = $_POST["user_id"];
    $table_id = $_POST["table_id"];
    $column_id = $_POST["column_id"];
    $data_id = $_POST["data_id"];
    $data = $_POST["data"];
    $row = $_POST["row"];    

    if ($data_id == "-1") {
        $SQL = "INSERT INTO data ";
        $SQL .= "(data, user_id, table_id, column_id, creation, update, row) ";
        $SQL .= "VALUES ('{$data}', '{$user_id}', '{$table_id}', '{$column_id}', current_timestamp, current_timestamp, '{$row}') ";
        //echo($SQL);
        $res = pg_query($con, $SQL);
        if (!$res) {
            //エラー
            echo("error: ".$SQL);
            exit;
        }
        else {
            echo("inserted successfly\n");
        }
    }
    else {
        $SQL = "UPDATE data SET (data, update) = ('{$data}', current_timestamp) WHERE data_id = '{$data_id}'";
        $res = pg_query($con, $SQL);
        if (!$res) {
            //エラー
            echo("error: ".$SQL);
            exit;
        }
        else {
            echo("updated successfly\n");
        }
    }


?>
