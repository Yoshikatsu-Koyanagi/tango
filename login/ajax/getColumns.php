<?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $table_id = $_POST["table_id"];

    $SQL = "SELECT * FROM columns ";
    $SQL .= "WHERE table_id = '{$table_id}'";
    $res = pg_query($con, $SQL);

    $res = pg_query($con, $SQL);
    if (!$res) {
        //UPDATE失敗
        echo("error: ".$SQL);
        exit;
    }
    $array_column = array();
    while ($row = pg_fetch_array($res)) {
        $column_id = $row["column_id"];
        $columnname = $row["columnname"];
        $type = $row["type"];
        $array_column += array(
            $column_id => array("columnname" => $columnname, "type" => $type)
        );
    }

    //column_order取得
    $SQL = "SELECT column_order FROM tables ";
    $SQL .= "WHERE table_id = '{$table_id}'";
    $res = pg_query($con, $SQL);

    $res = pg_query($con, $SQL);
    if (!$res) {
        //SELECT失敗
        echo("error: ".$SQL);
        exit;
    }

    $row = pg_fetch_assoc($res);
    $column_order = $row["column_order"];

    $array = [$array_column, $column_order];

    $json = json_encode($array);
    echo($json);
    exit;
?>