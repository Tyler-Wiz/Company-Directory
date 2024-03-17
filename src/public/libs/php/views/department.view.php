<?php

include '../Controllers/DepartmentController.php';


// create Departments
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // grab the id from the post request
    $name = $_POST['name'];
    $locationID = $_POST['locationID'];

    // instantiate the location controller
    $location  = new DepartmentController(name: $name,  locationID: $locationID);

    // create the location
    $location->createDepartment();
}

// get all Departments
if (isset($_POST['action']) && $_POST['action'] == 'getAll') {
    // grab text from the post request 
    if (isset($_POST['txt'])) {
        $txt = $_POST['txt'];
    } else {
        $txt = null;
    }
    // instantiate the location controller
    $location  = new  DepartmentController(txt: $txt);

    // get all the locations
    $location->getAllDepartments();
}

// get Department by id
if (isset($_POST['action']) && $_POST['action'] == 'getById') {
    // grab the id from the post request
    $id = $_POST['id'];

    // instantiate the location controller
    $location  = new DepartmentController(id: $id);

    // get the location by id
    $location->getDepartmentById();
}

// update Department by id
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    // grab the id from the post request
    $id = $_POST['id'];
    $name = $_POST['name'];
    $locationID = $_POST['locationID'];

    // instantiate the location controller
    $location  = new DepartmentController(name: $name, id: $id, locationID: $locationID);

    // update the location
    $location->updateDepartment();
}


// check personnel count on location
if (isset($_POST['action']) && $_POST['action'] == 'count') {
    // grab the id from the post request
    $id = $_POST['id'];
    // instantiate the location controller
    $location  = new DepartmentController(id: $id);

    // get the location by id
    $location->getPersonnelCount();
}

// delete Department by id

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    // grab the id from the post request
    $id = $_POST['id'];

    // instantiate the location controller
    $location  = new DepartmentController(id: $id);

    // delete the location by id
    $location->deleteDepartment();
}
