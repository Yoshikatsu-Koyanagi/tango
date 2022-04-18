

    <?php
    ini_set( 'display_errors', 1 );
    ini_set( 'error_reporting', E_ALL );
    require_once("/var/www/html/tango/func.php");
    $con = connectDB();

    $user_id = $_POST["user_id"];

    $SQL = "SELECT * FROM tables WHERE user_id = {$user_id} ORDER BY tablename ASC";
    $res = pg_query($con, $SQL);
    if (!$res) {
        //SELECT失敗
        echo("error: ".$SQL);
        exit;
    }
    
    $array_tables = array();
    while ($row = pg_fetch_array($res)) {
        $table_id = $row["table_id"];
        $tablename = $row["tablename"];
        $explanation = $row["explanation"];
        $public = $row["public"];
        $array_tables[] = [$table_id, $tablename, $explanation, $public];
    }

    $json = json_encode($array_tables);
    echo($json);
    exit;




?>