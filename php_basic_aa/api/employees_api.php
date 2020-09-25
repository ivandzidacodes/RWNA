<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/employees.php';

$http_method = $_SERVER["REQUEST_METHOD"];

$database = new Database();
$database->require_autentification();
$db = $database->getConnection();
$employee = new Employee($db);

switch ($http_method) {
	case 'GET':
		echo $employee->read();
		break;
	case 'POST':
		echo $employee->create();
		break;
	case 'PUT':
		echo $employee->update();
		break;
	case 'DELETE':
		echo $employee->delete();
		break;
	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>