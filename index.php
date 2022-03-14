<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
	<div>
		<form method="post" action="./index.php">
			<input type="text" name="username" class="inputBox" placeHolder=""><br>
			<input type="password" name="password" class="" placeHolder=""><br>
			<input type="submit" name="submit" value="LOGIN" class="">
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

	if ($_POST["submit"]) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$result = login($con, $username, $password);

		if ($result == 0) {
			$SQL = "SELECT user_id FROM users WHERE username = '{$username}'";
			$res = pg_query($con, $SQL);
			$row = pg_fetch_assoc($res);
			$_SESSION["user_id"] = $row["user_id"];
			$_SESSION["username"] = $username;
			header("Location: ./login/index.php");
		}
		else if ($result == 1) {
			echo("Password is incorrect.");
		}
		else if ($result == 2) {
			echo("Username is incorrect.");
		}
	}

?>