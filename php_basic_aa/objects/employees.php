<?php
class Employee {
	private $conn;
	private $table_name = "employees";

	public $emp_no;
	public $birth_date;
	public $first_name;
	public $last_name;
	public $gender;
	public $hire_date;
	
	public function __construct($db) {
		$this->conn = $db;
	}

	function read() {
		if (!empty($_GET["id"])) {
			return $this->readSingle();
		} else {
			return $this->readAll();
		}
	}

	function readAll() {
		$query = "SELECT * FROM " . $this->table_name;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();

		if ($num > 0) {
			$employees_arr = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				extract($row);
				$employee_item = array(
					"emp_no" => $emp_no,
					"birth_date" => $birth_date,
					"first_name" => $first_name,
					"last_name" => $last_name,
					"gender" => $gender,
					"hire_date" => $hire_date,
				);
				array_push($employees_arr, $employee_item);
			}
			http_response_code(200);
			return json_encode($employees_arr);
		} else {
			http_response_code(404);
			return json_encode(
				array("error" => "No employees found.")
			);
		}
	}

	function readSingle() {
		$query = "SELECT * FROM " . $this->table_name . " WHERE emp_no = :emp_no";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':emp_no', $_GET["id"]);
		$stmt->execute();
		$num = $stmt->rowCount();

		if ($num > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			extract($row);

			$employee_item = array(
				"emp_no" => $emp_no,
				"birth_date" => $birth_date,
				"first_name" => $first_name,
				"last_name" => $last_name,
				"gender" => $gender,
				"hire_date" => $hire_date,
			);
			http_response_code(200);
			return json_encode($employee_item);
		} else {
			http_response_code(404);
			return json_encode(
				array("error" => "No employees found or invalid employee id.")
			);
		}
	}

	function create() {
		$body = json_decode(file_get_contents("php://input"), true);
		$emp_no = $body["emp_no"];
		$birth_date = $body["birth_date"];
		$first_name = $body["first_name"];
		$last_name = $body["last_name"];
		$gender = $body["gender"];
		$hire_date = $body["hire_date"];

		if (
			!empty($emp_no) &&
			!empty($birth_date) &&
			!empty($first_name) &&
			!empty($last_name) &&
			!empty($gender) &&
			!empty($hire_date)
		) {

			$query = 'INSERT INTO ' . $this->table_name . '
				SET
				emp_no = :emp_no,
				birth_date = :birth_date,
				first_name = :first_name,
				last_name = :last_name,
				gender = :gender,
				hire_date = :hire_date';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(':emp_no', $emp_no);
			$stmt->bindParam(':birth_date', $birth_date);
			$stmt->bindParam(':first_name', $first_name);
			$stmt->bindParam(':last_name', $last_name);
			$stmt->bindParam(':gender', $gender);
			$stmt->bindParam(':hire_date', $hire_date);

			try {
				$stmt->execute();
				http_response_code(201);
				return json_encode(array("message" => "Employee was created."));
			} catch (Exception $e) {
				var_dump($e);
				http_response_code(503);
				return json_encode(array("error" => "Unable to create an employee."));
			}
		} else {
			http_response_code(400);
			return json_encode(array("error" => "Unable to create an employee. Data is missing."));
		}
	}

	function update() {
		$body = json_decode(file_get_contents("php://input"), true);
		$emp_no = $body["emp_no"];
		$birth_date = $body["birth_date"];
		$first_name = $body["first_name"];
		$last_name = $body["last_name"];
		$gender = $body["gender"];
		$hire_date = $body["hire_date"];

		if (!empty($emp_no)) {

			$query = 'UPDATE ' . $this->table_name . '
				SET
				birth_date = :birth_date,
				first_name = :first_name,
				last_name = :last_name,
				gender = :gender,
				hire_date = :hire_date
				WHERE
				emp_no = :emp_no';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(':emp_no', $emp_no);
			$stmt->bindParam(':birth_date', $birth_date);
			$stmt->bindParam(':first_name', $first_name);
			$stmt->bindParam(':last_name', $last_name);
			$stmt->bindParam(':gender', $gender);
			$stmt->bindParam(':hire_date', $hire_date);

			try {
				$stmt->execute();
				http_response_code(201);
				return json_encode(array("message" => "Employee was updated."));
			} catch (Exception $e) {
				var_dump($e);
				http_response_code(503);
				return json_encode(array("message" => "Unable to update employee"));
			}
		} else {
			http_response_code(400);
			return json_encode(array("message" => "Unable to update employee. Data is missing."));
		}
	}

	function delete() {
		if (!empty($_GET["id"])) {
			$query = 'DELETE FROM ' . $this->table_name . ' WHERE emp_no = :emp_no';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(':emp_no', $_GET["id"]);

			try {
				$stmt->execute();
				http_response_code(200);
				return json_encode(array("message" => "Employee was deleted."));
			} catch (Exception $e) {
				var_dump($e);
				http_response_code(503);
				return json_encode(array("error" => "Unable to delete employee."));
			}
		} else {
			http_response_code(400);
			return json_encode(
				array("error" => "Employee doesn't exist or the ID is invalid.")
			);
		}
	}
}
?>