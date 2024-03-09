<?php

// remove next two lines for production
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include("../config.php");
include("../function.php");

header('Content-Type: application/json; charset=UTF-8');

$conn = new mysqli($servername, $username, $password, $database);

if (mysqli_connect_errno()) {
    resHandler(300, "failure", "database unavailable");
}

// $_REQUEST used for development / debugging. Remember to change to $_POST for production
$query = $conn->prepare('INSERT INTO personnel (firstName, lastName, email, jobTitle, departmentID) VALUES(?,?,?,?,?)');
$query->bind_param("sssii", $firstName, $lastName, $email, $jobTitle, $departmentID);

// Set the values for the parameters
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$jobTitle = $_POST['jobTitle'];
$departmentID = $_POST['departmentID'];

$query->execute();

if ($query->affected_rows > 0) {
    resHandler(200, "success", "personnel updated");
} else {
    resHandler(400, "failure", "personnel not updated");
}
