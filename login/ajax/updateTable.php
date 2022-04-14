<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $user_id = $_POST["user_id"];
    $table_id = $_POST["table_id"];
    $tablename = $_POST["tablename"];
    $explanation = $_POST["explanation"];
    $public = $_POST["public"];

    //テーブル名が空のとき
    if (empty($tablename)) {
        echo("Tablename name can't be empty.");
        exit;
    }
    //テーブル名が使われているとき
    $SQL = "SELECT table_id ";
    $SQL .= "FROM tables ";
    $SQL .= "WHERE user_id = '{$user_id}' AND tablename = '{$tablename}' AND table_id != '{$table_id}'";
    $res = pg_query($con, $SQL);
    $num = pg_num_rows($res);
    if ($num > 0) {
        echo("This tablename is already used.");
        echo($SQL);
        exit;
    }

    $SQL = "UPDATE tables ";
    $SQL .= "SET (tablename, explanation, public) ";
    $SQL .= "= ('{$tablename}', '{$explanation}', '{$public}') ";
    $SQL .= "WHERE table_id = '{$table_id}'";

    $res2 = pg_query($con, $SQL);
    if (!$res2) {
        //UPDATE失敗
        echo("error: ".$SQL);
        exit;
    }
    else {
        echo(0);
        exit;
    }
?>