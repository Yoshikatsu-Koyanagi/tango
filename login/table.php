<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./login.css">
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
            <div id="wrapper_table">

            </div>
            <br>

            <div id="wrapper_detail">
                <div class="detail">
                    <form>
                        <input type="text" name="data" id="data" class="" placeHolder=""><br>
                        <input type="hidden" name="data_id" id="data_id" class="" placeHolder=""><br>
                    </form>
                        
                    <button name="update_data" id="update_data" class="">UPDATE data</button>
                    
                </div>
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
    $table_id = $_GET["table_id"];

    if (!$user_id) {
        header("Location: ./logout.php");
    }

/*
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

//            $array_column[] = array(
//                "column_id" => $column_id,
//                "columnname" => $columnname,
//                "type" => $type,
//                "data" => $array_data
//            );

        }
        //var_dump($array_column);
    }
    $json_column = json_encode($array_column);
*/
?>



<script type="text/javascript"> 
    let user_id = "<?php echo($user_id); ?>";
	let username = "<?php echo($username); ?>";
    let table_id = "<?php echo($table_id); ?>";

    function loadTable(user_id, username, table_id) {
        formData = new FormData();
        formData.append("user_id", user_id);
        formData.append("username", username);
        formData.append("table_id", table_id);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/loadTable.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                //console.log("HEADERS_RECEIVED");
            }
            else if (xhr.readyState == 3) {
                //console.log("LOADING");
            }
            else if (xhr.readyState == 4 && xhr.status == 200) {
                let json = xhr.response;
                json = JSON.parse(json);
                array_column = json[0];
                rows = parseInt(json[1]);
                console.log(array_column);
                console.log(rows);
                displayTable(array_column, rows);
            }
        }
        //xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(formData);
    }

    loadTable(user_id, username, table_id);

    function displayTable(array_column, rows) {
        //console.log("json: " + json_column);
        //json_column = JSON.parse(json_column);
        //console.log("json2: " + json_column);

        wrapper = document.getElementById("wrapper_table");
        wrapper.innerHTML = "";

        array_column.forEach( function (value, index) {
            column_id = value[0];
            columnname = value[1];
            type = value[2];
            array_data = value[3];

            column_no = "column_" + index;
            column = "<div id='" + column_no + "' class='column'></div>";
            wrapper.insertAdjacentHTML('beforeend', column);

            column = document.getElementById(column_no);
            cell_name = "<div class='cell_name'>" + columnname + "</div>";
            column.insertAdjacentHTML('beforeend', cell_name);

            for (i = 0; i < rows; i++) {
                value2 = array_data[i];
                if (!value2) {
                    data = "";
                    data_id = "";
                }
                else {
                    row = value2[0];
                    data = value2[1];
                    data_id = value2[2];      
                }
                column = document.getElementById(column_no);
                cell_data = "<div class='cell_data' onclick='clickData(\"" + data + "\", \"" + data_id + "\")'>" + data + "</div>";
                column.insertAdjacentHTML('beforeend', cell_data);
            };
/*
            array_data.forEach( function (value2) {
                row = value2[0];
                data = value2[1];
                data_id = value2[2];
                column = document.getElementById(column_no);
                cell_data = "<div class='cell_data' onclick='clickData(\"" + data + "\", \"" + data_id + "\")'>" + data + "</div>";
                column.insertAdjacentHTML('beforeend', cell_data);
            });
*/
        });
    }
/*
    let array_column = <?php //echo($array_column); ?>;
    console.log("json: " + json_column);
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
*/
    function clickData(data, data_id) {
        console.log(data);
        let form_data = document.getElementById("data");
        form_data.value = data;
        let form_data_id = document.getElementById("data_id");
        form_data_id.value = data_id;

        let detail = document.getElementById("wrapper_detail");
        detail.style.display = "block";
    };


    let update_data = document.getElementById('update_data');
    update_data.addEventListener('click', function() {
        let form = document.querySelector('form');
        //new FormData(form);
        //console.log(form);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/updateData.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                //console.log("HEADERS_RECEIVED");
            }
            else if (xhr.readyState == 3) {
                //console.log("LOADING");
            }
            else if (xhr.readyState == 4 && xhr.status == 200) {
                let responce = xhr.response;
                loadTable(user_id, username, table_id);
                console.log(responce);
            }
        }
        //xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(new FormData(form));
    });
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