<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./login.css">
    <script src="https://kit.fontawesome.com/dac4001bc8.js" crossorigin="anonymous"></script>
    <title></title>
</head>
<?php
    $username = $_SESSION["username"];
    $user_id = $_POST["user_id"];
    $table_id = $_POST["table_id"];
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
            <div>
                <a href="./createTable.php">Create Table</a><br>
                <a href="./tableList.php">Tables</a><br>
            </div>
        </div>
    </div>

</body>
</html>
