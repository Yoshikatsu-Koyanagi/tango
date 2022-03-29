<?php

    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $user_id = $_POST["user_id"];
	$username = $_POST["username"];
    $table_id = $_POST["table_id"];
    
    $SQL = "SELECT * FROM tables WHERE user_id = '{$user_id}' AND table_id = '{$table_id}'";
    $res0 = pg_query($con, $SQL);
    $num = pg_num_rows($res0);
    if ($num == 0) {
        echo("The table is not found");
    }
    else {
        $array_column = array();
        $SQL = "SELECT * FROM columns WHERE user_id = '{$user_id}' AND table_id = '{$table_id}'";
        $res = pg_query($con, $SQL);
        $num = pg_num_rows($res);

        //for ($i = 0; $i < $num; $i++) {
        while ($row = pg_fetch_array($res)) {
            //$row = pg_fetch_array($res, 0);
            $column_id = $row["column_id"];
            $columnname = $row["columnname"];
            $type = $row["type"];

            $SQL = "SELECT * FROM data WHERE user_id = '{$user_id}' AND column_id = '{$column_id}' ORDER BY row DESC";
            $res2 = pg_query($con, $SQL);
            $num = pg_num_rows($res2);

            if ($num == 0) {
                //break;
            }

            $array_data = array();
            while ($row2 = pg_fetch_array($res2)) {
                $data_id = $row2["data_id"];
                $data = $row2["data"];
                $row = $row2["row"];
                $array_data[] = [$row, $data, $data_id];
            }
            $array_column[] = [$column_id, $columnname, $type, $array_data];
/*
            $array_column[] = array(
                "column_id" => $column_id,
                "columnname" => $columnname,
                "type" => $type,
                "data" => $array_data
            );
*/
        }
        
        $rows = pg_fetch_assoc($res0)["rows"];    //tableテーブルのrow値
    }
    $array_column = [$array_column, $rows]; //
    $json = json_encode($array_column);
    echo($json);
    exit;
?>