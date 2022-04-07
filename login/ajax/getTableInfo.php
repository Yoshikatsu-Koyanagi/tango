<?php

    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $table_id = $_POST["table_id"];

    $SQL = "SELECT * FROM tables WHERE table_id = '{$table_id}'";
    $res = pg_query($con, $SQL);
    if (!$res) {
        echo("error: ".$SQL);
        exit;
    }
    $row = pg_fetch_assoc($res);

    $array = array(
        "tablename" => $row["tablename"],
        "explanation" => $row["explanation"],
        "public" => $row["public"],
        "creation" => date("F d, Y H:i", strtotime($row["creation"])),
        "update" => date("F d, Y H:i", strtotime($row["update"])),
        //"rows" => $row["rows"],
    );

    $json = json_encode($array);
    echo($json);
    exit;
?>