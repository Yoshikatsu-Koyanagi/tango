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
    ?>
    <?php 
            require_once("./header.php");
            //require_once("./side-bar.php");
    ?>

    <div class="middle">
        <?php 
                require_once("./side-bar.php");
        ?>
        <div class="main">
            <div class="wrapper_tableInfo">
                <div class="box1_tableInfo">
                    <div id="tablename">
                    </div>
                    <div class="tableInfo_icons">
                        <div id="public">
                            <i class="fa-solid fa-lock" id="icon_private"></i>
                            <i class="fa-solid fa-unlock" id="icon_public"></i>
                        </div>
                        <button type="button" onclick="displayAddColumnWindow();"><i class="fa-solid fa-square-plus"></i></button>
                        <button type="button" onclick="settingTable();"><i class="fa-solid fa-gear"></i></button>
                    </div>
                </div>
                <details>
                    <summary>explanation</summary>
                    <div id="explanation">
                    </div>
                    <div class="wrapper_dates">
                        <div id="creation">
                        </div>
                        <div id="update">
                        </div>
                    </div>
                </details>
            </div>

            <div id="wrapper_table">

            </div>
            <br>

            <div id="wrapper_window">
                <div id="detail_window">
                    <button onclick="closeWindow();" ><i class="fa-solid fa-circle-xmark"></i></button>
                    <form id="form_detail">
                        <!--<textarea id="data" class=""></textarea>-->
                        <input type="search" id="detail_data" name="data">
                        <input type="hidden" id="detail_data_id" name="data_id"><br>
                        <input type="hidden" id="detail_column_id" name="column_id"><br>
                        <input type="hidden" id="detail_row" name="row"><br>
                    </form>
                        
                    <button id="update_data">UPDATE data</button>
                    
                </div>
                <div id="add_column_window">
                    <button onclick="closeWindow();" ><i class="fa-solid fa-circle-xmark"></i></button>
                    <form id="form_add_column">
                        Name
                        <input type="text" id="columnname" name="columnname"><br>
                        Type
                        <select name="type">
                            <option value="1">string</option>
                            <option value="2">float</option>
                            <option value="3">integer</option>
                            <option value="4">date</option>
                        </select>
                    </form>
                        
                    <button id="add_column">ADD column</button>
                    
                </div>
                <div id="add_data_window">
                    <button onclick="closeWindow();"><i class="fa-solid fa-circle-xmark"></i></button>
                    <div id="add_data_columnname"></div>
                    <form id="form_add_data">
                        <input type="text" id="add_data_data" name="data"><br>
                        <input type="hidden" id="add_data_column_id" name="column_id"><br>
                    </form>
                        
                    <button id="add_data">ADD data</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>





<script type="text/javascript"> 
    let user_id = "<?php echo($user_id); ?>";
	let username = "<?php echo($username); ?>";
    let table_id = "<?php echo($table_id); ?>";

    function getTableInfo(table_id) {

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/getTableInfo.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                //console.log("HEADERS_RECEIVED");
            }
            else if (xhr.readyState == 3) {
                //console.log("LOADING");
            }
            else if (xhr.readyState == 4 && xhr.status == 200) {
                let json = xhr.response;
                array_tableInfo = JSON.parse(json);
                //console.log(array_tableInfo);
                displayTableInfo(array_tableInfo);
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("table_id=" + table_id);        
    }

    getTableInfo(table_id);

    function displayTableInfo(array_tableInfo) {
        tablename = array_tableInfo["tablename"];
        e_tablename = document.getElementById("tablename");
        e_tablename.innerHTML = tablename;

        explanation = array_tableInfo["explanation"];
        e_explanation = document.getElementById("explanation");
        e_explanation.innerHTML = explanation;

        public = array_tableInfo["public"];
        if (public == 1) {
            e_public = document.getElementById("icon_public");
            e_public.style.display = "block";
        }
        else {
            e_private = document.getElementById("icon_private");
            e_private.style.display = "block";
        }

        creation = array_tableInfo["creation"];
        e_creation = document.getElementById("creation");
        e_creation.innerHTML = "Created: " + creation;

        update = array_tableInfo["update"];
        e_update = document.getElementById("update");
        e_update.innerHTML = "Last modified: " + update;
    }

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
                console.log(json);
                json = JSON.parse(json);
                console.log(json);
                array_column = json[0];
                console.log(array_column);
                //rows = parseInt(json[1]);
                array_row = json[1];
                //console.log(array_column);
                displayTable(array_column, array_row);
            }
        }
        //xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(formData);
    }

    loadTable(user_id, username, table_id);

    function displayTable(array_column, array_row) {
        //console.log("json: " + json_column);
        //json_column = JSON.parse(json_column);
        //console.log("json2: " + json_column);
        //console.log(array_column);

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
            cell_name = "<div class='cell_name'><button onclick='displayAddDataWindow(\"" + columnname + "\", \"" + column_id + "\")'>" + columnname + "</button></div>";
            column.insertAdjacentHTML('beforeend', cell_name);

            for (i = 1; i < array_row.length + 1; i++) {
                value2 = array_data[i];
                if (!value2) {
                    row = i;
                    data = "";
                    data_id = "-1";
                }
                else {
                    row = i;
                    data = value2["data"];
                    data_id = value2["data_id"];          
                }
                //column = document.getElementById(column_no);
                id = "data_id_" + index + "_" + i;
                cell_data = "<div class='cell_data' id='" + id + "' onclick='changeBgColor(\"" + id + "\"); displayDetailWindow(\"" + data + "\", \"" + data_id + "\", \"" + column_id + "\", \"" + row + "\")'>" + data + "</div>";
                column.insertAdjacentHTML('beforeend', cell_data);
            };

            //cell_add = "<div class='cell_add'>+</div>";
            //column.insertAdjacentHTML('beforeend', cell_add);

        });
    }

    function displayDetailWindow(data, data_id, column_id, row) {
        let detail_data = document.getElementById("detail_data");
        detail_data.value = data;
        let form_data_id = document.getElementById("detail_data_id");
        detail_data_id.value = data_id;
        let detail_column_id = document.getElementById("detail_column_id");
        detail_column_id.value = column_id;
        let detail_row = document.getElementById("detail_row");
        detail_row.value = row;
        console.log("data: " + data);
        console.log("data_id: " + data_id);
        console.log("column_id: " + column_id);
        console.log("row: " + row);

        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "flex";
        windows = wrapper_window.children;
        for (let i = 0; i < windows.length; i++){
			windows[i].style.display = "none";
		}
        let detail_window = document.getElementById("detail_window");
        detail_window.style.display = "block";
    };

    function displayAddColumnWindow(data, data_id, column_id, row) {
        clearBgColor();
        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "flex";
        windows = wrapper_window.children;
        for (let i = 0; i < windows.length; i++){
			windows[i].style.display = "none";
		}
        let add_column_window = document.getElementById("add_column_window");
        add_column_window.style.display = "block";
    };

    function displayAddDataWindow(columnname, column_id) {
        clearBgColor();
        let add_column_id = document.getElementById("add_data_column_id");
        add_column_id.value = column_id;

        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "flex";
        windows = wrapper_window.children;
        for (let i = 0; i < windows.length; i++){
			windows[i].style.display = "none";
		}
        let add_column_window = document.getElementById("add_data_window");
        add_column_window.style.display = "block";

        let add_data_columnname = document.getElementById("add_data_columnname");
        add_data_columnname.innerHTML = columnname;
    };

    function closeWindow() {
        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "none";
        clearBgColor();
    }

    function changeBgColor(id) {
        clearBgColor();
        let element = document.getElementById(id);
        element.style.backgroundColor = "yellow";
    }

    function clearBgColor() {
        let cell_data = document.getElementsByClassName("cell_data");
        for (let i = 0; i < cell_data.length; i++){
			cell_data[i].style.backgroundColor = "";
		}
    }

    let update_data = document.getElementById('update_data');
    update_data.addEventListener('click', function() {
        let form = document.getElementById('form_detail');
        formData = new FormData(form);
        formData.append("user_id", user_id);
        //formData.append("username", username);
        formData.append("table_id", table_id);
        console.log("form");

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
                closeWindow();
                console.log(responce);
            }
        }
        //xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(formData);
    });

    let add_data = document.getElementById('add_data');
    add_data.addEventListener('click', function() {
        let form = document.getElementById('form_add_data');
        formData = new FormData(form);
        formData.append("user_id", user_id);
        formData.append("table_id", table_id);
        console.log(formData);  

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/addData.php');
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
                    loadTable(user_id, username, table_id);
                    closeWindow();
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

    let add_column = document.getElementById('add_column');
    add_column.addEventListener('click', function() {
        let form = document.getElementById('form_add_column');
        formData = new FormData(form);
        formData.append("user_id", user_id);
        formData.append("table_id", table_id);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/addColumn.php');
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
                    loadTable(user_id, username, table_id);
                    closeWindow();
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

    function settingTable() {
        window.location.href = "./settingTable.php?table_id=" + table_id;
    }
</script>

<style>
    .box1_tableInfo {
        width: 100%;
        height: 40px;
        display: flex;
        flex-direction: row;
    }
    #tablename {
        font-size: 24px;
        font-family: Roboto;
    }
    .tableInfo_icons {
        margin-left: auto;
        display: flex;
        flex-direction: row;
    }
    #explanation {
        margin: 1px 10px;
        white-space: pre-wrap;
    }
    #creation, #update {
        font-style: italic;
    }
    .wrapper_dates {
        display: flex;
        flex-direction: row;
    }
    .wrapper_dates div {
        margin: 1px 10px;
    }
    #wrapper_table {
        display: flex;
        margin: auto;
        flex-direction: row;
        border-collapse: collapse;
        text-align: center;
    }
    .column {
/*
        display: table-cell;
*/
        border-collapse: collapse;
        text-align: center;
    }
    .cell_name {
		height: 40px;
        line-height:40px;
		vertical-align: middle;
		border: solid 4px #C3C3C3;
        border-left: none;
		background-color: #C3C3C3;
	}
    .cell_name:hover {
		background-color: #B7B7B7;
	}
    .cell_name button {
        width: 100%;
        height: 100%;
        padding: 0 10px;
		font-family: Roboto;
		font-size: 20px;
        font-weight: bold;
        color: white;
    }
    .cell_data {
		height: 40px;
        line-height: 40px;
		font-family: Roboto;
		font-size: 20px;
        padding: 0 10px;
		border: solid 4px #C3C3C3;
		border-top: none;
        border-left: none;
		background-color: white;
	}
    .cell_add {
		height: 15px;
        line-height:15px;
		font-family: Roboto;
		font-size: 20px;
        padding: 0 10px;
        color: white;
		border: solid 4px #C3C3C3;
		border: none;
		background-color: #b0b0b0;
	}
    #column_0 .cell_name {
        border-top: solid 4px #C3C3C3;
        border-left: solid 4px #C3C3C3;
    }
    #column_0 .cell_data {
        border-left: solid 4px #C3C3C3;
    }
    #column_0 .cell_add {
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
    #detail_window {
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
    #form_detail * {
        font-size: 18px;
    }
    #add_column_window, #add_data_window {
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
    #form_add_column * {
        font-size: 18px;
    }
    #add_column {
        margin: 10px;
        padding: 8px;
        font-size: 15px;
        color: white;
        background-color: #8d8d8d;
        border-radius: 10px;
    }
    .fa-square-plus {
        margin: 0 8px;
        color: grey; 
        font-size: 20px;
        line-height: 40px;
    }
    .fa-square-plus:hover {
        color: black; 
    }
    .fa-lock {
        vertical-align: middle;
        margin: auto 8px auto 8px;
        color: red; 
        display: none;
        font-size: 20px;
        line-height: 40px;
    }
    .fa-unlock {
        margin: auto 8px;
        color: lime; 
        display: none;
        font-size: 20px;
        line-height: 40px;
    }
    .fa-gear {
        margin: 0 8px;
        color: grey; 
        font-size: 20px;
        line-height: 40px;
    }
    .fa-gear:hover {
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
    details {
        margin: 5px;
    }

</style>