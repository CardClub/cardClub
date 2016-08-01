<?php
require 'db.php';

define( 'PRIVATE_PATH', dirname(__DIR__) . "/");

function pr( $var ) 
{ // funcion para mostrar arreglos de forma legible por humanos
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}