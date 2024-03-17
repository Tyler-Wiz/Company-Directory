<?php

declare(strict_types=1);

include '../responseHandler.php';
include '../Models/Personnel.php';

class PersonnelController extends Personnel {

    public function __construct(
        private  $id = null,
        private  $firstName = null,
        private  $lastName = null,
        private  $email = null,
        private  $jobTitle = null,
        private  $departmentID = null,
        private  $txt = null
    ) {
    }

    /**
     * Calls the create method from the Personnel model
     * and returns the response
     * Calls the handleResponse function from the responseHandler 
     * file and returns the response with the appropriate status code, message and data
     */
    public function createPersonnel() {
        $personnel = $this->create($this->firstName, $this->lastName, $this->email, $this->jobTitle, $this->departmentID);
        // if the personnel is not created successfully
        if (!$personnel) {
            handleResponse(404, "Not Found", "No Personnel Created", $personnel);
            // if the personnel created successfully
        } else {
            handleResponse(200, "success", "Personnel created successfully", $personnel);
        }
    }

    /**
     * Calls the create method from the Personnel model
     * and returns the response
     * Calls the handleResponse function from the responseHandler 
     * file and returns the response with the appropriate status code, message and data
     */
    public function getAllPersonnel() {
        $personnel = $this->getAll($this->txt);
        // if the personnel is not found
        if ($personnel == "Error") {
            handleResponse(400, "Bad Request", "Error fetching personnel data",   $personnel);
            // if the personnel is found but empty 
        } else if (empty($personnel)) {
            handleResponse(404, "Not Found", "No personnel found",   $personnel);
            // if the personnel is found and not empty
        } else {
            handleResponse(200, "success", "personnel data fetched successfully",   $personnel);
        }
    }

    /**
     * Calls the getByID method from the Personnel model
     * and returns the response
     */
    public function getPersonnelByID() {
        $personnel = $this->getByID($this->id);
        // if the personnel is not found
        if ($personnel == "Error") {
            handleResponse(400, "Bad Request", "Error fetching personnel data",   $personnel);
            // if the personnel is found but empty 
        } else if (empty($personnel)) {
            handleResponse(404, "Not Found", "No Personnel found",   $personnel);
            // if the personnel is found and not empty
        } else {
            handleResponse(200, "success", "Personnel data fetched successfully",   $personnel);
        }
    }

    /**
     * Calls the update method from the Personnel model
     * and returns the response
     */
    public function updatePersonnel() {
        $personnel = $this->update($this->id, $this->firstName, $this->lastName, $this->email, $this->jobTitle, $this->departmentID);
        // if the personnel is not updated successfully
        if (!$personnel) {
            handleResponse(404, "Not Found", "No Personnel Updated", $personnel);
            // if the personnel updated successfully
        } else {
            handleResponse(200, "success", "Personnel updated successfully", $personnel);
        }
    }

    public function deletePersonnel() {
        $personnel = $this->delete($this->id);
        // if the personnel is not deleted successfully
        if (!$personnel) {
            handleResponse(404, "Not Found", "No Personnel Deleted", $personnel);
            // if the personnel deleted successfully
        } else {
            handleResponse(200, "success", "Personnel deleted successfully", $personnel);
        }
    }
}
