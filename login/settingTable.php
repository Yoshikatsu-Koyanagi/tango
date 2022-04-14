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
            <div class="">
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
            <div>
                <form id="form_column">
                </form>
                <button id="button_sort">sort</button>
                    
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
                array_columns = JSON.parse(json);
                console.log(json);
                setColumns(array_columns);
            }
        }
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send("table_id=" + table_id);
    }

    getColumns(table_id);

    function setColumns(array_columns) {
/*
        Object.keys(array_columns).forEach( function(key) {
            //columnname = value["columnname"];
            //type = value["type"];
            console.log(key + ":" + array_columns[key]["columnname"]);
        });
*/
        for (let i = 11; i < 13; i++) {
            columnname = array_columns[i]["columnname"];
            type = array_columns[i]["type"];

            form_column = document.getElementById("form_column");
            column = "<div id='" + i + "'><input type='text' value='" + columnname + "'></input><input type='text' value='" + type + "'></input></div>";
            form_column.insertAdjacentHTML('beforeend', column);
            
        }

    }
</script>

<script type="text/javascript">
    new Sortable(form_column,{
    animation: 150,
    ghostClass: 'ghost',
    chosenClass: 'light-green',
    delay: 100,
    });

    let button_sort = document.getElementById('button_sort');
    button_sort.addEventListener('click', function() {
        //let form_column = document.getElementById('form_column');
        //var num = form_column.childElementCount;
        let children = document.querySelectorAll('#form_column div');

        for(let i = 0; i < children.length; i++) {
	        console.log(children[i].id);
        }

    });


</script>