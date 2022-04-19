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

    $column_id = $_POST["column_id"];
    $columnname = $_POST["columnname"];
     

    $SQL = "UPDATE columns SET columnname = '{$columnname}' WHERE column_id = '{$column_id}'";
    $res = pg_query($con, $SQL);
    if (!$res) {
        //エラー
        echo("error: ".$SQL);
        exit;
    }
    
    echo(0);
    exit;

?>
