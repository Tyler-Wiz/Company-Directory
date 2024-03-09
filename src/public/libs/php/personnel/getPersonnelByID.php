<?php

// remove next two lines for production
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

// $_REQUEST used for development / debugging. Remember to change to $_POST for production
$query = $conn->prepare('SELECT `id`, `firstName`, `lastName`, `email`, `jobTitle`, `departmentID` FROM `personnel` WHERE `id` = ?');
$query->bind_param("i", $_REQUEST['id']);
$query->execute();

if (false === $query) {
	resHandler(400, "executed", "query failed", []);
}

$result = $query->get_result();
$personnel = [];
while ($row = mysqli_fetch_assoc($result)) {
	array_push($personnel, $row);
}

// second query - does not accept parameters and so is not prepared
$query = 'SELECT id, name from department ORDER BY name';
$result = $conn->query($query);

if (!$result) {
	resHandler(400, "executed", "query failed", []);
}

$department = [];

while ($row = mysqli_fetch_assoc($result)) {
	array_push($department, $row);
}

$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data']['personnel'] = $personnel;
$output['data']['department'] = $department;

mysqli_close($conn);

echo json_encode($output);
