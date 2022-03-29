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
    $data = $_POST["data"];
    $data_id = $_POST["data_id"];

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
?>
