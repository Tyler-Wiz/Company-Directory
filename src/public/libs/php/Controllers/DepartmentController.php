<?php

declare(strict_types=1);

include '../Models/Department.php';
include '../responseHandler.php';

class DepartmentController extends Department {
    public function __construct(
        private $id = null,
        private $name = null,
        private $txt = null,
        private $locationID = null
    ) {
    }

    public function createDepartment() {
        $department = $this->create($this->name, $this->locationID);
        // if the department is not created successfully
        if (!$department) {
            handleResponse(404, "Not Found", "No Department Created", $department);
            // if the department created successfully
        } else {
            handleResponse(200, "success", "Department created successfully", $department);
        }
    }

    public function getAllDepartments() {
        $department = $this->getAll($this->txt);
        // if the department is not found
        if ($department == "Error") {
            handleResponse(400, "Bad Request", "Error fetching department data", $department);
            // if the department is found but empty 
        } else if (empty($department)) {
            handleResponse(404, "Not Found", "No Department found", $department);
            // if the department is found and not empty
        } else {
            handleResponse(200, "success", "Department data fetched successfully", $department);
        }
    }

    public function getDepartmentById() {
        $department = $this->getById($this->id);
        // if the department is not found
        if ($department == "Error") {
            handleResponse(400, "Bad Request", "Error fetching department data", $department);
            // if the department is found but empty 
        } else if (empty($department)) {
            handleResponse(404, "Not Found", "No Department found", $department);
            // if the department is found and not empty
        } else {
            handleResponse(200, "success", "Department data fetched successfully", $department);
        }
    }


    public function updateDepartment() {
        $department = $this->update($this->id, $this->name, $this->locationID);
        // if the department is not updated successfully
        if (!$department) {
            handleResponse(404, "Not Found", "No Department Updated", $department);
            // if the department updated successfully
        } else {
            handleResponse(200, "success", "Department updated successfully");
        }
    }

    public function getPersonnelCount() {
        $personnel = $this->personnelCount($this->id);
        // if the department is not found
        if (
            $personnel[0]['personnel_count'] > 0
        ) {
            $result = $personnel;
            handleResponse(403, "Forbidden", "Personnel Can't be deleted", $result);
        } else {
            handleResponse(201, "Success", "No Personnel in Department", $personnel);
        }
    }

    public function deleteDepartment() {
        $department = $this->delete($this->id);
        // if the department is not deleted successfully
        if (!$department) {
            handleResponse(404, "Not Found", "No Department Deleted", $department);
            // if the department deleted successfully
        } else {
            handleResponse(200, "success", "Department deleted successfully");
        }
    }
}
