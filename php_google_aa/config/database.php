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

    function require_autentification() {
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

function require_google($google_client) {
    $google_login_url = '';
    if (isset($_GET["code"])) {
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
        if (!isset($token['error'])) {
            $google_client->setAccessToken($token['access_token']);
            $_SESSION['access_token'] = $token['access_token'];
            $google_service = new Google_Service_Oauth2($google_client);
            $data = $google_service->userinfo->get();
            if (!empty($data['given_name'])) {
                $_SESSION['user_first_name'] = $data['given_name'];
            }
            if (!empty($data['family_name'])) {
                $_SESSION['user_last_name'] = $data['family_name'];
            }
            if (!empty($data['email'])) {
                $_SESSION['user_email_address'] = $data['email'];
            }
            if (!empty($data['gender'])) {
                $_SESSION['user_gender'] = $data['gender'];
            }
            if (!empty($data['picture'])) {
                $_SESSION['user_image'] = $data['picture'];
            }
        }
    }
    if (!isset($_SESSION['access_token'])) {
        $google_login_url = $google_client->createAuthUrl();
    }
    return $google_login_url;
}

?>