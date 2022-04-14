<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./login.css">
    <script src="https://kit.fontawesome.com/dac4001bc8.js" crossorigin="anonymous"></script>
    <title></title>
</head>
<body>
    <?php 
                require_once("./header.php");
                //require_once("./side-bar.php");
    ?>
    <div class="middle">
        <?php 
                require_once("./side-bar.php");
        ?>
        <div class="main">
            <div id="wrapper_list">
            </div>
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

    $SQL = "SELECT * FROM tables WHERE user_id = {$user_id} ORDER BY tablename ASC";
    $res = pg_query($con, $SQL);
    
    $array_tables = array();
    while ($row = pg_fetch_array($res)) {
        $table_id = $row["table_id"];
        $tablename = $row["tablename"];
        $explanation = $row["explanation"];
        $public = $row["public"];
        $array_tables[] = [$table_id, $tablename, $explanation, $public];
    }

    $json_tables = json_encode($array_tables);
    //var_dump($array_tables);

?>

<script type="text/javascript">
    let json_tables = <?php echo $json_tables?>;

    let wrapper = document.getElementById("wrapper_list");

    let items_name = "<div class='items_name'>";
    items_name += "<div class='boxes box1'>tablename</div>";
    items_name += "<div class='boxes box2'>explanation</div>";
    items_name += "<div class='boxes box3'>public / private</div>";
    items_name += "</div>";
    wrapper.insertAdjacentHTML('beforeend', items_name);

    json_tables.forEach( function (value, index) {
        let table_id = value[0];
        let tablename = value[1];
        let explanation = value[2];
        let public = value[3];
        if (public == 1) {
            public_text = "public";
        }
        else {
            public_text = "private";
        }
        let table = "<div class='tables' onclick='location.href=\"./table.php?table_id=" + table_id + "\"'>";
        table += "<div class='boxes box1'>" + tablename + "</div>";
        table += "<div class='boxes box2'>" + explanation + "</div>";
        table += "<div class='boxes box3'>" + public_text + "</div>";
        table += "</div>";
        wrapper.insertAdjacentHTML('beforeend', table);
    });

</script>

<style>

    #wrapper_list {
        display: table-cell;
        border-collapse: collapse;
        text-align: center;
    }
    .items_name {
		margin: auto;
		display: table;
		width: 1000px;
	}
    .tables {
		margin: auto;
		display: table;
		width: 1000px;
	}
    .items_name .boxes {
		display: table-cell;
		vertical-align: middle;
		background-color: #C3C3C3;
		color: white;
		font-family: Roboto;
		font-weight: bold;
		font-size: 20px;
		border: solid 4px #C3C3C3;
	}
    .tables .boxes {
		display: table-cell;
		height: 40px;
		vertical-align: middle;
		font-family: Roboto;
		font-size: 20px;
		border: solid 4px #C3C3C3;
		border-top: none;
		background-color: white;
	}
    .box1 {
        width: 300px;
    }
    .box2 {
        text-align: left;
        width: 500px;
    }
    .box3 {
        width: 200px;
    }
    #column_0 .cell_name {
        border-top: solid 4px #C3C3C3;
        border-left: solid 4px #C3C3C3;
    }
    #column_0 .cell_data {
        border-left: solid 4px #C3C3C3;
    }

</style>