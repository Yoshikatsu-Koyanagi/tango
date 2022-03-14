<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
    <a href="../index.php">login</a>
</body>
</html>

<?php

	ini_set( 'display_errors', 1 );
	ini_set( 'error_reporting', E_ALL );
	require_once("/var/www/html/tango/func.php");

	session_start();
    $_SESSION = array();
    session_destroy();

    

?>