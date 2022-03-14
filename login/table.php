<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>

    <div id="wrapper_table">

    </div>
    <br>

    <div id="wrapper_detail">
        <div class="detail">
            <form method="post" action="./table.php">
                <input type="text" name="datum" id="datum" class="" placeHolder=""><br>
                <input type="submit" name="update_datum" value="UPDATE DATUM" class="">
            </form>
        </div>
    </div>
</body>
</html>

<?php

	ini_set( 'display_errors', 1 );
	ini_set( 'error_reporting', E_ALL );
	require_once("/var/www/html/tango/func.php");

	$con = connectDB();
	session_start();
	$user_id = $_SESSION["user_id"];
	$username = $_SESSION["username"];
    $table_id = 1;

    if (!$user_id) {
        header("Location: ./logout.php");
    }

	echo("user_id: {$user_id}<br>");
	echo("username: {$username}<br>");
    echo("table_id: {$table_id}<br>");

    $SQL = "SELECT * FROM tables WHERE user_id = '{$user_id}' AND table_id = '{$table_id}'";
    $res = pg_query($con, $SQL);
    $num = pg_num_rows($res);
    if ($num == 0) {
        echo("The table is not found.<br>");
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
        //var_dump($array_column);
    }
    $json_column = json_encode($array_column);

?>



<script type="text/javascript">
    let json_column = <?php echo $json_column?>;

    json_column.forEach( function (value, index) {
        column_id = value[0];
        columnname = value[1];
        type = value[2];
        array_data = value[3];

        column_no = "column_" + index;
        wrapper = document.getElementById("wrapper_table");
        column = "<div id='" + column_no + "' class='column'></div>";
        wrapper.insertAdjacentHTML('beforeend', column);

        column = document.getElementById(column_no);
        cell_name = "<div class='cell_name'>" + columnname + "</div>";
        column.insertAdjacentHTML('beforeend', cell_name);

        array_data.forEach( function (value2) {
            row = value2[0];
            data = value2[1];
            data_id = value2[2];
            column = document.getElementById(column_no);
            cell_data = "<div class='cell_data' onclick='clickData(\"" + data + "\", \"" + data_id + "\")'>" + data + "</div>";
            column.insertAdjacentHTML('beforeend', cell_data);
        });
    });

    function clickData(data, data_id) {
        console.log(data);
        let form = document.getElementById("datum");
        form.value = data;

        let detail = document.getElementById("wrapper_detail");
        detail.style.display = "block";
    };
</script>

<style>
    #wrapper_table {
        display: flex;
        flex-direction: row;
        border-collapse: collapse;
    }
    .column {
        display: table-cell;
        border-collapse: collapse;
        text-align: center;
    }
    .cell_name {
		height: 40px;
        line-height:40px;
		vertical-align: middle;
		font-family: Roboto;
		font-size: 20px;
        font-weight: bold;
		border: solid 4px #C3C3C3;
        border-left: none;
		background-color: #C3C3C3;
		color: white;
	}
    .cell_data {
		height: 40px;
        line-height:40px;
		font-family: Roboto;
		font-size: 20px;
		border: solid 4px #C3C3C3;
		border-top: none;
        border-left: none;
		background-color: white;
	}
    #column_0 .cell_name {
        border-top: solid 4px #C3C3C3;
        border-left: solid 4px #C3C3C3;
    }
    #column_0 .cell_data {
        border-left: solid 4px #C3C3C3;
    }

    #wrapper_detail {
        display: none;
        position: fixed;
        left: 800px;
        top: 200px;
        z-index: 10;
    }
    .detail {
        border: solid 2px;
        padding: 10px; 
    }
</style>