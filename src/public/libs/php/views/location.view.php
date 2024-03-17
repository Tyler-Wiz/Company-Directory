<?php

declare(strict_types=1);
// include the location controller
include '../Controllers/LocationController.php';

// create location
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // grab the id from the post request
    $name = $_POST['name'];

    // instantiate the location controller
    $location  = new LocationController(name: $name);

    // create the location
    $location->createLocation();
}

// get location by id
if (isset($_POST['action']) && $_POST['action'] == 'getByID') {
    // grab the id from the post request
    $id = $_POST['id'];

    // instantiate the location controller
    $location  = new  LocationController(id: $id);

    // get the location by id
    $location->getLocationById();
}

// get all locations
if (isset($_POST['action']) && $_POST['action'] == 'getAll') {
    // grab the id from the post request
    if (isset($_POST['txt'])) {
        $txt = $_POST['txt'];
    } else {
        $txt = null;
    }
    // instantiate the location controller
    $location  = new LocationController(txt: $txt);

    // get all the locations
    $location = $location->getAllLocations();
}

// update location
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    // grab the id from the post request
    $id = $_POST['id'];
    $name = $_POST['name'];

    // instantiate the location controller
    $location  = new LocationController(id: $id, name: $name);

    // get all the locations

    $location->updateLocation();
}

// check department count on location
if (isset($_POST['action']) && $_POST['action'] == 'count') {
    // grab the id from the post request
    $id = $_POST['id'];

    // instantiate the location controller
    $location  = new  LocationController(id: $id);

    $location->DeptCount();
}

// delete location
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    // grab the id from the post request
    $id = $_POST['id'];

    // instantiate the location controller
    $location  = new  LocationController(id: $id);

    // get all the locations
    $location->deleteLocation();
}
