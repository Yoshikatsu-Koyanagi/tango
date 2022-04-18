<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $user_id = $_POST["user_id"];
    $tablename = trim($_POST["tablename"]);
    $explanation = $_POST["explanation"];



    //テーブル名が空のとき
    if (empty($tablename)) {
        echo("The tablename can't be empty.");
        exit;
    }
    $explanation = $_POST["explanation"];

    $SQL = "SELECT tablename FROM tables WHERE user_id = '{$user_id}' AND tablename = '{$tablename}'";
    $res = pg_query($con, $SQL);
    $num = pg_num_rows($res);

    //テーブル名が使われている場合
    if ($num > 0) {
        echo("This table name is already used.");
        exit;
    }

    $SQL = "INSERT INTO tables (tablename, explanation, user_id, creation) VALUES ('{$tablename}', '{$explanation}', '{$user_id}', current_timestamp)";
    $res = pg_query($con, $SQL);
    if (!$res) {
        echo("err: ".$SQL);
    }
    
    echo(0);
    exit;


?>