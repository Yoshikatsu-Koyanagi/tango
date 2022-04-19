<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    $SQL = "SELECT password FROM users WHERE user_id = '{$user_id}'";
    $res = pg_query($con, $SQL);
    if (!$res) {
        //SELECT失敗
        echo("error: ".$SQL);
        exit;
    }
    $row = pg_fetch_assoc($res);
    $correct_password = $row["password"];
    if ($password == $correct_password) {
        echo(0);
        exit;
    }
    else {
        echo(-1);
        exit;
    }
    

?>