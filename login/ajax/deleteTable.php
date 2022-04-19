<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $table_id = $_POST["table_id"];
    
    echo(0);
    exit;

    $SQL = "DELETE FROM data WHERE table_id = '{$table_id}'";
    $res = pg_query($con, $SQL);
    if (!$res) {
        //DELETE失敗
        echo("error: ".$SQL);
        exit;
    }

    $SQL = "DELETE FROM columns WHERE table_id = '{$table_id}'";
    $res2 = pg_query($con, $SQL);
    if (!$res2) {
        //DELETE失敗
        echo("error: ".$SQL);
        exit;
    }

    $SQL = "DELETE FROM tables WHERE table_id = '{$table_id}'";
    $res3 = pg_query($con, $SQL);
    if (!$res3) {
        //DELETE失敗
        echo("error: ".$SQL);
        exit;
    }
    
    echo(0);
    exit;
?>