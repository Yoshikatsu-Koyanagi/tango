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
			<div>
				<form method="post" action="./createTable.php">
					<input type="text" name="tablename" class="" placeHolder=""><br>
					<input type="text" name="explanation" class="" placeHolder=""><br>
					<input type="submit" name="submit" value="CREATE TABLE" class="">
				</form>
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
	echo("user_id: {$user_id}<br>");
	echo("username: {$username}<br>");

	if ($_POST["submit"]) {
		$tablename = trim($_POST["tablename"]);
		//テーブル名が空のとき
		if (empty($tablename)) {
			echo("The tablename can't be empty.");
			exit;
		}
		$explanation = $_POST["explanation"];

		$SQL = "SELECT tablename FROM tables WHERE user_id = '{$user_id}' AND tablename = '{$tablename}'";
		$res = pg_query($con, $SQL);
		$num = pg_num_rows($res);

		//テーブル名が使われている場合
		if ($num > 0) {
			echo("This table name is already used.");
			exit;
		}
		else {
			$SQL = "INSERT INTO tables (tablename, explanation, user_id, creation) VALUES ('{$tablename}', '{$explanation}', '{$user_id}', current_timestamp)";
			$res = pg_query($con, $SQL);
			if (!$res) {
				echo("FAILED TO CREATE A TABLE");
			}
			else {
				echo("CREATE A TABLE");
			}
		}
	}

?>