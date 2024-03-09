<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

include("../config.php");
include("../function.php");

header('Content-Type: application/json; charset=UTF-8');

$conn = new mysqli($servername, $username, $password, $database);

if (mysqli_connect_errno()) {
    resHandler(300, "failure", "database unavailable");
}
// SQL statement accepts parameters and so is prepared to avoid SQL injection.
// $_REQUEST used for development / debugging. Remember to change to $_POST for production

$query = $conn->prepare('DELETE FROM personnel WHERE id = ?');
$query->bind_param("i", $_REQUEST['id']);
$query->execute();

if ($query->affected_rows > 0) {
    resHandler(200, "success", "personnel deleted", []);
} else {
    resHandler(400, "failure", "personnel not deleted", []);
}
