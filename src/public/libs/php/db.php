<?php

class Db {
    private $conn;
    private $serverName = "db";
    private $username = "root";
    private $password = "example";
    private $database = "companydirectory";

    protected function connect() {
        try {
            $this->conn = new mysqli($this->serverName, $this->username, $this->password, $this->database);
            return $this->conn;
        } catch (mysqli_sql_exception $e) {
            $executionStartTime = microtime(true);
            $output['status']['code'] = "300";
            $output['status']['name'] = "failure";
            $output['status']['description'] = $e->getMessage();
            $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
            $output['data'] = [];

            echo json_encode($output);
        }
    }
}
