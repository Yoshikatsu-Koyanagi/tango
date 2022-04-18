<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $user_id = $_POST["user_id"];
    $table_id = $_POST["table_id"];
    $columnname = trim($_POST["columnname"]);
    $type = $_POST["type"];

    //カラム名が空のとき
    if (empty($columnname)) {
        echo("Column name can't be empty.");
        exit;
    }
    if (empty($type)) {
        echo("error: type is empty.");
        exit;
    }

    $SQL = "SELECT columnname FROM columns WHERE table_id = '{$table_id}' AND columnname = '{$columnname}'";
    $res = pg_query($con, $SQL);
    $num = pg_num_rows($res);

    //カラム名が使われている場合
    if ($num > 0) {
        echo("This column name is already used.");
        exit;
    }

    $SQL = "INSERT INTO columns (columnname, type, user_id, table_id, creation) VALUES ('{$columnname}', '{$type}', '{$user_id}', '{$table_id}', current_timestamp)";
    $res2 = pg_query($con, $SQL);
    if (!$res2) {
        //カラム追加失敗
        echo("error: ".$SQL);
        exit;
    }

    $SQL = "SELECT column_id FROM columns WHERE table_id = '{$table_id}' AND columnname = '{$columnname}'";
    $res3 = pg_query($con, $SQL);
    if (!$res3) {
        //SELECT失敗
        echo("error: ".$SQL);
        exit;
    }
    $row3 = pg_fetch_assoc($res3);
    $column_id = $row3["column_id"];

    $SQL = "UPDATE tables ";
    $SQL .= "SET column_order = " ;
    $SQL .= "CASE " ;
    $SQL .= "WHEN column_order IS NULL THEN '{$column_id}' ";
    $SQL .= "ELSE column_order || ',{$column_id}' ";
    $SQL .= "END ";
    $SQL .= "WHERE table_id = '{$table_id}'";

    $res3 = pg_query($con, $SQL);
    if (!$res3) {
        //UPDATE失敗
        echo("error: ".$SQL);
        echo("column_id: ".$column_id);
        exit;
    }
    
    echo(0);
    exit;




?>