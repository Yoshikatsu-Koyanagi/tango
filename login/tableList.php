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
            <div class="wrapper_new">
                <button id="new" onclick="displayCreateTableWindow();">NEW</button>
            </div>
            <div id="wrapper_list">
            </div>
            <div id="wrapper_window">
                <div id="create_table_window">
                    <button onclick="closeWindow();" ><i class="fa-solid fa-circle-xmark"></i></button>
                    <form id="form_create_table">
                        <!--<textarea id="data" class=""></textarea>-->
                        <div>
                            <p>tablename</p>
                            <input type="text" id="create_table_tablename" name="tablename" width="15">
                        </div>
                        <div>
                            <p>explanation</p>
                            <textarea id="create_table_explanation" name="explanation" cols="30" rows="5"></textarea>
                        </div>
                    </form>
                    <button id="create_table">Create table</button>
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
/*
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
*/
?>

<script type="text/javascript">
    let user_id = "<?php echo($user_id); ?>";
	let username = "<?php echo($username); ?>";

    function displayTableList(json_tables) {
        let wrapper = document.getElementById("wrapper_list");
        wrapper.innerHTML = "";
        let items_name = "<div class='items_name'>";
        items_name += "<div class='boxes box1'>tablename</div>";
        items_name += "<div class='boxes box2'>explanation</div>";
        items_name += "<div class='boxes box3'>public / private</div>";
        items_name += "</div>";
        wrapper.insertAdjacentHTML('beforeend', items_name);

        json_tables.forEach( function (value, index) {
            let table_id = value[0];
            let tablename = value[1];
            if (tablename.length > 18) {
                tablename = tablename.substr(0, 18) + "...";
            }
            let explanation = value[2];
            if (explanation.length > 40) {
                explanation = explanation.substr(0, 40) + "...";
            }
            console.log(explanation.length);
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
    }

    function loadTableList(user_id) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/loadTableList.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                //console.log("HEADERS_RECEIVED");
            }
            else if (xhr.readyState == 3) {
                //console.log("LOADING");
            }
            else if (xhr.readyState == 4 && xhr.status == 200) {
                let json = xhr.response;
                console.log(json);
                json_tables = JSON.parse(json);
                displayTableList(json_tables);
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("user_id=" + user_id);
    }
    loadTableList(user_id);

    function displayCreateTableWindow() {
        //clearBgColor();

        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "flex";
        windows = wrapper_window.children;
        for (let i = 0; i < windows.length; i++){
			windows[i].style.display = "none";
		}
        let create_table_window = document.getElementById("create_table_window");
        create_table_window.style.display = "block";
    };

    function closeWindow() {
        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "none";
        //clearBgColor();
    }

    let create_table = document.getElementById('create_table');
    create_table.addEventListener('click', function() {
        let form = document.getElementById('form_create_table');
        formData = new FormData(form);
        formData.append("user_id", user_id);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/createTable.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                //console.log("HEADERS_RECEIVED");
            }
            else if (xhr.readyState == 3) {
                //console.log("LOADING");
            }
            else if (xhr.readyState == 4 && xhr.status == 200) {
                let responce = xhr.response;
                if (responce == 0) {
                    closeWindow();
                    loadTableList(user_id);
                }
                else {
                    window.alert(responce);
                }
                console.log(responce);
            }
        }
        //xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(formData);
    });

</script>

<style>

    #wrapper_list {
        width: 100%;
/*
        display: table-cell;
*/
        border-collapse: collapse;
        text-align: center;
        margin: auto;
    }
    .wrapper_new {
        margin: 8px;
        text-align:center;
    }
    #new {
        margin: auto;
        width: 80px;
        height: 30px;
        color: white;
        font-size: 18px;
        font-family: Roboto;
        font-weight: bold;
        background-color: rgba(50,255,50,0.9);
        border: none;
        border-radius: 3px;
        box-shadow: 0 3px 0 0 rgba(50,150,50,0.9);
    }
    #new:active{
		position: relative;
		top: 3px;
		box-shadow: none;
	}
    #create_table_window {
        display: none;
        text-align: center;
        background-color: #eeeeee;
        border: solid 5px;
        border-color: white;
        border-radius: 15px;
        padding: 10px;
        margin: auto;
        box-shadow: 5px 5px 5px;
    }
    #create_table_window div {
        margin: 5px;
    }
    #create_table_window p {
        margin: 3px;
    }
    #create_table_tablename {
        width: 230px;
        height: 20px;
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
        padding: 0 10px;
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
        padding: 0 10px;
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
    #wrapper_window {
        display: none;
        position: absolute;
        width: 300px;
        height: 200px;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        z-index: 10;
    }
    .fa-circle-plus {
        margin: 0 8px;
        color: grey; 
        font-size: 20px;
        line-height: 40px;
    }
    .fa-circle-plus:hover {
        color: black; 
    }
    .fa-circle-xmark {
        margin: 0 8px;
        color: grey; 
        font-size: 20px;
    }
    .fa-circle-xmark:hover {
        color: black;
    }
    button {
        border: none; 
        padding: 0; 
        margin: 0; 
        background-color: rgba(0,0,0,0);
    }
</style>