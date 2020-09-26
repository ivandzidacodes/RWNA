<?php

// loging:

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/departments.php';

$http_method = $_SERVER["REQUEST_METHOD"];

$database = new Database();
$db = $database->getConnection();
$department = new Department($db);

switch ($http_method) {
	case 'GET':
		echo $department->read();
		break;
	case 'POST':
		echo $department->create();
		break;
	case 'PUT':
		echo $department->update();
		break;
	case 'DELETE':
		echo $department->delete();
		break;
	default:
		// Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
    }
?>	