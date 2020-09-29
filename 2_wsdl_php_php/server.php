<?php

include_once 'database.php';

if (!extension_loaded("soap")) {
	dl("php_soap.dll");
}
ini_set("soap.wsdl_cache_enabled", "0");

//http://localhost:8888/wsdl/employeesService.wsdl
$server = new SoapServer("http://localhost/2_wsdl_php_php/employeesService.wsdl", array('soap_version' => SOAP_1_2));

function getEmployees($name, $fromDate, $toDate){

	$database = new Database();
	$db = $database->getConnection();

	$query = "
	SELECT employees.emp_no, 
	employees.first_name, 
	employees.last_name, 
	dept_emp.from_date, 
	dept_emp.to_date, 
	departments.dept_name FROM `employees` 
	INNER JOIN `dept_emp` ON employees.emp_no = dept_emp.emp_no 
	INNER JOIN `departments` ON dept_emp.dept_no = departments.dept_no 
	WHERE CONCAT(employees.first_name, ' ', employees.last_name) LIKE '%$name%' AND (CAST(dept_emp.from_date AS DATE) BETWEEN '" . $fromDate . "' AND '" . $toDate . "') AND (CAST(dept_emp.to_date AS DATE) BETWEEN '" . $fromDate . "' AND '" . $toDate . "')";

	$stmt = $db->prepare($query);
	$stmt->execute();

	$num = $stmt->rowCount();

	$employees_arr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$employee_item = array(
			"emp_no" => $emp_no,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"dept_name" => $dept_name,
            "from_date" => $from_date,
            "to_date" => $to_date
		);

		array_push($employees_arr, $employee_item);
	}

	return $employees_arr;
}

$server->AddFunction("getEmployees");
$server->handle();