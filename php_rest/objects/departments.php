<?php
class Department
{
    private $conn;
    private $table_name = "departments";
    
    public $dept_no;
    public $dept_name;
    
    public function __construct($db)
	{
		$this->conn = $db;
    }
    
    function read()
	{
		if (!empty($_GET["id"])) {
			return $this->readSingle();
		} else {
			return $this->readAll();
		}
    }
    
    function readAll()
	{
		$query = "SELECT * FROM " . $this->table_name;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();

		if ($num > 0) {
			$departments_arr = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				extract($row);
				$departments_item = array(
					"dept_no" => $dept_no,
					"dept_name" => $dept_name,
				);
				array_push($departments_arr, $departments_item);
			}
			http_response_code(200);
			return json_encode($departments_arr);
		} else {
			http_response_code(404);
			return json_encode(
				array("error" => "No departments found.")
			);
		}
    }
    
    function readSingle()
	{
		$query = "SELECT * FROM " . $this->table_name . " WHERE dept_no = :dept_no";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':dept_no', $_GET["id"]);
		$stmt->execute();
		$num = $stmt->rowCount();

		if ($num > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			extract($row);

			$departments_item = array(
				"dept_no" => $dept_no,
				"dept_name" => $dept_name,
			);
			http_response_code(200);
			return json_encode($departments_item);
		} else {
			http_response_code(404);
			return json_encode(
				array("error" => "No departments found or invalid department id.")
			);
		}
    }
    
    function create()
	{
		$body = json_decode(file_get_contents("php://input"), true);
		$dept_no = $body["dept_no"];
		$dept_name = $body["dept_name"];

		if (!empty($dept_no) && !empty($dept_name) ) {

			$query = 'INSERT INTO ' . $this->table_name . '
				SET
				dept_no = :dept_no,
				dept_name = :dept_name';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(':dept_no', $dept_no);
			$stmt->bindParam(':dept_name', $dept_name);
		
			try {
				$stmt->execute();
				http_response_code(201);
				return json_encode(array("message" => "Department was created."));
			} catch (Exception $e) {
				var_dump($e);
				http_response_code(503);
				return json_encode(array("error" => "Unable to create an department."));
			}
		} else {
			http_response_code(400);
			return json_encode(array("error" => "Unable to create an department. Data is missing."));
		}
    }
    
    function update()
	{
		$body = json_decode(file_get_contents("php://input"), true);
		$dept_no = $body["dept_no"];
		$dept_name = $body["dept_name"];

		if (!empty($dept_no)) {

			$query = 'UPDATE ' . $this->table_name . '
				SET
				dept_no = :dept_no,
				dept_name = :dept_name';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(':dept_no', $dept_no);
            $stmt->bindParam(':dept_name', $dept_name);
            
			try {
				$stmt->execute();
				http_response_code(201);
				return json_encode(array("message" => "Department was updated."));
			} catch (Exception $e) {
				var_dump($e);
				http_response_code(503);
				return json_encode(array("message" => "Unable to update department"));
			}
		} else {
			http_response_code(400);
			return json_encode(array("message" => "Unable to update department. Data is missing."));
		}
    }
    
    function delete()
	{
		if (!empty($_GET["id"])) {
			$query = 'DELETE FROM ' . $this->table_name . ' WHERE dept_no = :dept_no';

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(':dept_no', $_GET["id"]);

			try {
				$stmt->execute();
				http_response_code(200);
				return json_encode(array("message" => "Department was deleted."));
			} catch (Exception $e) {
				var_dump($e);
				http_response_code(503);
				return json_encode(array("error" => "Unable to department employee."));
			}
		} else {
			http_response_code(400);
			return json_encode(
				array("error" => "Department doesn't exist or the ID is invalid.")
			);
		}
	}
}
?>