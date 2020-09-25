<?php

class Database {
    private $host = "localhost";
    private $db_name = "sakila";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->conn;
    }

    public function require_autentification() {

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Basic"');
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("error" => "Unathorized"));
        exit;
        } elseif ($_SERVER['PHP_AUTH_USER'] != 'root' || $_SERVER['PHP_AUTH_PW'] != 'root') {
        header('HTTP/1.0 403 Forbidden');
        echo json_encode(array("error" => "Forbidden"));
        exit;
        }
    }
}
?>