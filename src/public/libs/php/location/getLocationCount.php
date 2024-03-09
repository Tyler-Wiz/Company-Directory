<?php

// example use from browser
// use insertDepartment.php first to create new dummy record and then specify it's id in the command below
// http://localhost/companydirectory/libs/php/deleteDepartmentByID.php?id=<id>

// remove next two lines for production

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

// this includes the login details
include("../config.php");
include("../function.php");

header('Content-Type: application/json; charset=UTF-8');

$conn = new mysqli($servername, $username, $password, $database);

if (mysqli_connect_errno()) {

    $output['status']['code'] = "300";
    $output['status']['name'] = "failure";
    $output['status']['description'] = "database unavailable";
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
    $output['data'] = [];

    mysqli_close($conn);

    echo json_encode($output);

    exit;
}

// SQL statement accepts parameters and so is prepared to avoid SQL injection.
// $_REQUEST used for development / debugging. Remember to change to $_POST for production
$query = $conn->prepare(
    'SELECT l.id, l.name, COUNT(d.locationID) AS location_count
    FROM location l
    LEFT JOIN department d ON d.locationID = l.id
    WHERE l.id = ?  GROUP BY l.id'
);
$query->bind_param("i", $_POST['id']);
$query->execute();

if (false === $query) {
    resHandler(400, "executed", "query failed", []);
}

$result = $query->get_result();

$department = [];
while ($row = mysqli_fetch_assoc($result)) {
    array_push($department, $row);
}

$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = $department;

mysqli_close($conn);

echo json_encode($output);