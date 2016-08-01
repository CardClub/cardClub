<?php
include 'medoo.php';
$db = new medoo([
// required
	'database_type' => 'mysql',
	'database_name' => 'cardclub',
	'server' => 'localhost',
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	'port' => 3306,
	'option' => [ PDO::ATTR_CASE => PDO::CASE_NATURAL]
]);
?>