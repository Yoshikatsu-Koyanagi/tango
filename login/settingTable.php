<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./login.css">
    <script src="https://kit.fontawesome.com/dac4001bc8.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <title></title>
</head>
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
            <div id="table_info">
                <form id="form_table">
                    <div>
                        <input type="text" id="tablename" name="tablename">
                    </div>
                    <div>
                        <textarea id="explanation" name="explanation"></textarea>
                    </div>
                    <div>
                        <input type="text" id="public" name="public">
                    </div>
                    
                </form>
                <button id="update_table">UPDATE</button>
            </div>
            <div id="column_info">
                <div id="columns">
                </div>
                <button id="button_sort">sort</button>
                    
            </div>

            <div id="wrapper_window">
                <div id="update_column_window">
                    <button onclick="closeWindow();"><i class="fa-solid fa-circle-xmark"></i></button>
                    <form id="form_update_column">
                        <input type="text" id="update_column_columnname" name="columnname"><br>
                        <input type="hidden" id="update_column_column_id" name="column_id"><br>
                    </form>
                        
                    <button id="update_column">UPDATE column</button>
                </div>
                <div id="confirm_window">
                    <button onclick="closeWindow();"><i class="fa-solid fa-circle-xmark"></i></button>
                    <p>You're going to delete this table.</p>
                    <form id="form_confirm_password">
                        Please enter your password <br>
                        to continue.
                        <input type="password" id="confirm_password_password" name="password"><br>
                    </form>
                        <button id="confirm_password">Confirm</button>
                    <div id="wrapper_delete_table">
                        Are you sure you want to completly delete this table?<br>
                        This can't be undone.
                        <div>
                            <button id="delete_table">DELETE</button>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button id="delete" onclick="displayConfirmWindow();"><i class="fa-solid fa-trash-can"></i></button>
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
                setTableInfo(array_tableInfo);
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("table_id=" + table_id);        
    }

    getTableInfo(table_id);

    function setTableInfo(array_tableInfo) {
        tablename = array_tableInfo["tablename"];
        e_tablename = document.getElementById("tablename");
        e_tablename.value = tablename;

        explanation = array_tableInfo["explanation"];
        e_explanation = document.getElementById("explanation");
        e_explanation.value = explanation;

        public = array_tableInfo["public"];
        e_public = document.getElementById("public");
        e_public.value = public;
    }

    let update_table = document.getElementById('update_table');
    update_table.addEventListener('click', function() {
        let form = document.getElementById('form_table');
        formData = new FormData(form);
        formData.append("user_id", user_id);
        //formData.append("username", username);
        formData.append("table_id", table_id);
        console.log(formData);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/updateTable.php');
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
                    getTableInfo(table_id);
                }
                else {
                    window.alert(responce);
                }
            }
        }
        //xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(formData);

    });

    function getColumns(table_id) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/getColumns.php');
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
                array_columns = json[0];
                column_order = json[1].split(',');
                console.log(column_order);
                setColumns(array_columns, column_order);
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("table_id=" + table_id);
    }

    getColumns(table_id);

    function setColumns(array_columns, column_order) {
/*
        Object.keys(array_columns).forEach( function(key) {
            //columnname = value["columnname"];
            //type = value["type"];
            console.log(key + ":" + array_columns[key]["columnname"]);
        });
*/

        column_order.forEach( function (value) {
            //array_columns[value]["columnname"]
            columnname = array_columns[value]["columnname"];
            type = array_columns[value]["type"];

            columns = document.getElementById("columns");
            column = "<div id='" + value + "'><button type='button' onclick='closeWindow(); displayUpdateColumnWindow(\"" + value + "\", \"" + columnname +  "\");'>" + columnname + "</button></div>";
            columns.insertAdjacentHTML('beforeend', column);
        });


    }
    
    function displayUpdateColumnWindow(column_id, columnname) {
        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "flex";
        windows = wrapper_window.children;
        for (let i = 0; i < windows.length; i++){
			windows[i].style.display = "none";
		}
        let update_column_window = document.getElementById("update_column_window");
        update_column_window.style.display = "block";

        let update_column_columnname = document.getElementById("update_column_columnname");
        update_column_columnname.value = columnname;

        let update_column_column_id = document.getElementById("update_column_column_id");
        update_column_column_id.value = column_id;
    }

    function displayConfirmWindow() {
        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "flex";
        windows = wrapper_window.children;
        for (let i = 0; i < windows.length; i++){
			windows[i].style.display = "none";
		}
        let confirm_window = document.getElementById("confirm_window");
        confirm_window.style.display = "block";
    }

    function displayDeleteButton() {
        let wrapper_delete_table = document.getElementById("wrapper_delete_table");
        wrapper_delete_table.style.display = "block";
    }

    function closeWindow() {
        let wrapper_window = document.getElementById("wrapper_window");
        wrapper_window.style.display = "none";
        //clearBgColor();
    }

    function clearColumns() {
        let columns = document.getElementById("columns");
        columns.innerHTML = "";
        //clearBgColor();
    }

    function updateColumnOrder(table_id, column_order) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/updateColumnOrder.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                //console.log("HEADERS_RECEIVED");
            }
            else if (xhr.readyState == 3) {
                //console.log("LOADING");
            }
            else if (xhr.readyState == 4 && xhr.status == 200) {
                let responce = xhr.response;
                console.log(responce);
                if (responce != 0) {
                    echo(responce);
                }
                else {
                    clearColumns();
                    getColumns(table_id);
                }
 
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("table_id=" + table_id + "&column_order=" + column_order);
    }

    let button_sort = document.getElementById('button_sort');
    button_sort.addEventListener('click', function() {
        //let columns = document.getElementById('columns');
        //var num = columns.childElementCount;
        let children = document.querySelectorAll('#columns div');

        let column_order = "";
        for(let i = 0; i < children.length; i++) {
	        column_id = children[i].id.toString();
            column_order = column_order + column_id;
            if (i != children.length - 1) {
                column_order = column_order + ",";
            }
        }
        updateColumnOrder(table_id, column_order);
    });

    let update_column = document.getElementById('update_column');
    update_column.addEventListener('click', function() {
        let form = document.getElementById('form_update_column');
        formData = new FormData(form);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/updateColumn.php');
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
                    clearColumns();
                    getColumns(table_id);
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

    let confirm_password = document.getElementById('confirm_password');
    confirm_password.addEventListener('click', function() {
        let form = document.getElementById('form_confirm_password');
        formData = new FormData(form);
        formData.append("user_id", user_id);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/confirmPassword.php');
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
                    displayDeleteButton();
                }
                else if (responce == -1) {
                    window.alert("Password is incorrect.");
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

    let delete_table = document.getElementById('delete_table');
    delete_table.addEventListener('click', function() {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', './ajax/deleteTable.php');
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
                    window.alert("This table is successfuly deleted.");
                    window.location.href = './tableList.php'; 
                }
                else {
                    window.alert(responce);
                }
                console.log(responce);
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("table_id=" + table_id);
    });
</script>

<script type="text/javascript">
    new Sortable(columns,{
    animation: 150,
    ghostClass: 'ghost',
    chosenClass: 'light-green',
    delay: 100,
    });
</script>

<style>
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
    #update_column_window {
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
    #confirm_window {
        display: none;
        text-align: center;
        background-color: #ff9e8f;
        border: solid 5px;
        border-color: white;
        border-radius: 15px;
        padding: 10px;
        margin: auto;
        box-shadow: 5px 5px 5px;
    }
    #confirm_window .fa-circle-xmark{
        color: black; 
    }
    #confirm_window .fa-circle-xmark:hover{
        color: rgba(0,0,0,0.65); 
    }
    #confirm_password {
        width: 60px;
        height: 25px;
        margin: 5px;
        border: none;
        border-radius: 3px; 
        color: white;
        font-size: 14px;
        font-weight: bold;
        background-color: grey;
    }
    #wrapper_delete_table {
        display: none;
        margin: 3px;
    }
    #delete_table {
        width: 80px;
        height: 30px;
        margin: 3px;
        border: none;
        border-radius: 5px; 
        color: white;
        font-size: 16px;
        font-weight: bold;
        background-color: black;
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
    #column_info div button {
        display: block;
        padding: 5px 10px; 
        margin: 2px; 
        font-size: 18px;
        color: white;
        background-color: rgba(200,200,200,1);
    }
</style>