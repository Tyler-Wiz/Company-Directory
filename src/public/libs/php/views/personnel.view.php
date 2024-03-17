<?php

declare(strict_types=1);

// include the personnel controller
include '../Controllers/personnelController.php';

// create location
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // grab the personnel info from the post request
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $jobTitle = $_POST['jobTitle'];
    $departmentID = $_POST['departmentID'];

    // Initialize new personnel controller
    $personnel = new PersonnelController(
        firstName: $firstName,
        lastName: $lastName,
        email: $email,
        jobTitle: $jobTitle,
        departmentID: $departmentID,
    );

    // create the personnel
    $personnel->createPersonnel();
}

// get all personnel
if (isset($_POST['action']) && $_POST['action'] == 'getAll') {
    // grab the id from the post request
    if (isset($_POST['txt'])) {
        $txt = $_POST['txt'];
    } else {
        $txt = null;
    }

    // Initialize new personnel controller
    $personnel = new PersonnelController(txt: $txt);

    // get all the personnel
    $personnel->getAllPersonnel();
}

// get personnel by id

if (isset($_POST['action']) && $_POST['action'] == 'getById') {
    // grab the id from the post request
    $id = $_POST['id'];

    // Initialize new personnel controller
    $personnel = new PersonnelController(id: $id);

    // get the personnel by id
    $personnel->getPersonnelByID();
}

// update personnel

if (isset($_POST['action']) && $_POST['action'] == 'update') {
    // grab the personnel info from the post request
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $jobTitle = $_POST['jobTitle'];
    $departmentID = $_POST['departmentID'];

    // Initialize new personnel controller
    $personnel = new PersonnelController(
        id: $id,
        firstName: $firstName,
        lastName: $lastName,
        email: $email,
        jobTitle: $jobTitle,
        departmentID: $departmentID,
    );

    // update the personnel
    $personnel->updatePersonnel();
}

// delete personnel
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    // grab the id from the post request
    $id = $_POST['id'];

    // Initialize new personnel controller
    $personnel = new PersonnelController(id: $id);

    // delete the personnel
    $personnel->deletePersonnel();
}
