<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();
    if (!$con) {
        //エラー
        echo("DB connect error");
        exit;
    }

    $table_id = $_POST["table_id"];
    $column_order = $_POST["column_order"];

    $SQL = "UPDATE tables SET column_order = '{$column_order}' ";
    $SQL .= "WHERE table_id = '{$table_id}'";

    $res = pg_query($con, $SQL);

    $res = pg_query($con, $SQL);
    if (!$res) {
        //UPDATE失敗
        echo("error: ".$SQL);
        exit;
    }

    echo(0);
    exit;
?>