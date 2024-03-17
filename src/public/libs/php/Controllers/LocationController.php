<?php

declare(strict_types=1);

include '../Models/Location.php';
include '../responseHandler.php';

class LocationController extends Location {
    public function __construct(
        private $id = null,
        private $name = null,
        private $txt = null,
    ) {
    }
    public function createLocation() {
        $location = $this->create($this->name);
        // if the location is not created successfully
        if (!$location) {
            handleResponse(404, "Not Found", "No Location Created", $location);
            // if the location created successfully
        } else {
            handleResponse(200, "success", "Location created successfully", $location);
        }
    }

    public function getLocationById() {
        $location = $this->getById($this->id);
        // if the location is not found
        if ($location == "Error") {
            handleResponse(400, "Bad Request", "Error fetching location data", $location);
            // if the location is found but empty 
        } else if (empty($location)) {
            handleResponse(404, "Not Found", "No Location found", $location);
            // if the location is found and not empty
        } else {
            handleResponse(200, "success", "Location data fetched successfully", $location);
        }
    }

    public function getAllLocations() {
        $location = $this->getAll($this->txt);
        // if the location is not found
        if ($location == "Error") {
            handleResponse(400, "Bad Request", "Error fetching location data", $location);
            // if the location is found but empty 
        } else if (empty($location)) {
            handleResponse(404, "Not Found", "No Location found", $location);
            // if the location is found and not empty
        } else {
            handleResponse(200, "success", "Location data fetched successfully", $location);
        }
    }

    public function updateLocation() {
        $location = $this->update($this->id, $this->name);
        // if the location is not found
        if (!$location) {
            handleResponse(404, "Not Found", "No Location found", $location);
            // if the location is found and not empty
        } else {
            handleResponse(200, "success", "Location data fetched successfully", $location);
        }
    }

    public function DeptCount() {
        $department = $this->checkDeptCount($this->id);
        if (
            $department[0]['location_count'] > 0
        ) {
            $result = $department;
            handleResponse(403, "Forbidden", "Location Can't be deleted", $result);
        } else {
            handleResponse(201, "error", "No Department found", $department);
        }
    }

    public function deleteLocation() {
        $location = $this->delete($this->id);
        // if the location is not found
        if (!$location) {
            handleResponse(404, "Not Found", "No Location found", $location);
            // if the location is found and not empty
        } else {
            handleResponse(200, "success", "Location deleted Successfully", $location);
        }
    }
}
