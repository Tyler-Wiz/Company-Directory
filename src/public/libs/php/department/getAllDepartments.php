<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

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


$query = 'SELECT d.id, d.name, d.locationID, l.name as location 
          FROM department d 
          LEFT JOIN location l ON (l.id = d.locationID) 
          ORDER BY d.name';
$result = db($query);

if (!$result) {
	resHandler(400, "executed", "query failed", []);
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
	array_push($data, $row);
}

$found = [];
if (isset($_POST['txt'])) {
	$query = $conn->prepare('SELECT `d`.`id`, `d`.`name`, `l`.`id` as `locationID`, `l`.`name` AS `location` 
	FROM `department` `d`
	LEFT JOIN `location` `l` ON (`l`.`id` = `d`.`locationID`) 
	WHERE `d`.`name` LIKE ? OR `l`.`name` LIKE ? 
	ORDER BY  `d`.`name`, `l`.`name`');

	$likeText = "%" . $_POST['txt'] . "%";
	$query->bind_param("ss", $likeText, $likeText);

	$query->execute();

	if (false === $query) {

		$output['status']['code'] = "400";
		$output['status']['name'] = "executed";
		$output['status']['description'] = "query failed";
		$output['data'] = [];

		mysqli_close($conn);

		echo json_encode($output);

		exit;
	}

	$result = $query->get_result();
	while ($row = mysqli_fetch_assoc($result)) {
		array_push($found, $row);
	}

	$data = $found;
}


$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = $data;

mysqli_close($conn);

echo json_encode($output);
