<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
	<div>
		<form method="post" action="./createColumn.php">
			Name
			<input type="text" name="columnname" class="" placeHolder=""><br>
			Type
			<select name="type" size="">
				<option value="1">string</option>
				<option value="2">float</option>
				<option value="3">iteger</option>
				<option value="1">date</option>
			</select>
			<br>
			<input type="submit" name="submit" value="CREATE COLUMN" class="">
		</form>
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
	echo("user_id: {$user_id}<br>");
	echo("username: {$username}<br>");
	echo("table_id: $table_id<br>");

	if ($_POST["submit"]) {
		$columnname = trim($_POST["columnname"]);
		//カラム名が空のとき
		if (empty($columnname)) {
			
		}
		$type = $_POST["type"];

		$SQL = "SELECT columnname FROM columns WHERE table_id = '{$table_id}' AND columnname = '{$columnname}'";
		$res = pg_query($con, $SQL);
		$num = pg_num_rows($res);

		//カラム名が使われている場合
		if ($num > 0) {
			echo("This column name is already used.");
		}
		else {
			$SQL = "INSERT INTO columns (columnname, type, user_id, table_id, creation) VALUES ('{$columnname}', '{$type}', '{$user_id}', '{$table_id}', current_timestamp)";
			$res = pg_query($con, $SQL);
			if (!$res) {
				echo("FAILED TO CREATE A COLUMN");
			}
			else {
				echo("CREATE A COLUMN");
			}
		}
	}

?>
