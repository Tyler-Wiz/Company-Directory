<?php

include '../db.php';

class Personnel extends Db {

    /**
     * Inserts a new personnel record into the database
     * returns true if successful, false if not
     * @return bool|null
     */

    protected function create(string $firstName, string  $lastName, string  $email, string $jobTitle, string | int $departmentID) {
        $query = 'INSERT INTO personnel (firstName, lastName, email, jobTitle, departmentID) VALUES(?,?,?,?,?)';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $jobTitle, $departmentID);
        $stmt->execute();

        $result = null;

        if (false === $stmt) {
            $result = "Error";
        }

        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Returns all personnel records from the database
     * If a search term is provided, returns all personnel records that match the search term
     * @return array|string
     */

    protected function getAll(string | int | null $txt) {
        $result = null;
        $query = 'SELECT p.id, p.lastName, p.firstName, p.jobTitle, p.email, d.name as department, l.name as location FROM personnel p 
                  LEFT JOIN department d ON (d.id = p.departmentID) 
	              LEFT JOIN location l ON (l.id = d.locationID) 
                  ORDER BY p.lastName, p.firstName, d.name, l.name';

        try {
            $result = $this->connect()->query($query);
        } catch (mysqli_sql_exception $e) {
            return "Error : " . $e->getMessage();
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, $row);
        }

        $found = [];
        # Check if the user has entered a search term
        if (isset($txt)) {
            $query = 'SELECT `p`.`id`, `p`.`firstName`, `p`.`lastName`, `p`.`email`, `p`.`jobTitle`, `d`.`id` as `departmentID`, `d`.`name` AS `department`, `l`.`id` as `locationID`, `l`.`name` AS `location` 
                      FROM `personnel` `p` LEFT JOIN `department` `d` ON (`d`.`id` = `p`.`departmentID`) 
                      LEFT JOIN `location` `l` ON (`l`.`id` = `d`.`locationID`) 
                      WHERE `p`.`firstName` LIKE ? OR `p`.`lastName` LIKE ? OR `p`.`email` LIKE ? OR `p`.`jobTitle` LIKE ? OR `d`.`name` LIKE ? OR `l`.`name` LIKE ?
                      ORDER BY `p`.`lastName`, `p`.`firstName`, `d`.`name`, `l`.`name`';

            $stmt = $this->connect()->prepare($query);
            $likeText = "%" . $txt . "%";
            $stmt->bind_param("ssssss", $likeText, $likeText, $likeText, $likeText, $likeText, $likeText);

            $stmt->execute();

            if (false === $stmt) {
                $data = "Error";
            }

            $result = $stmt->get_result();
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($found, $row);
            }

            # If the user has entered a search term, return the search results
            $data = $found;
        }

        $this->connect()->close();

        return $data;
    }

    /**
     * Returns a single personnel record by id and department table from the database 
     * @return array|string
     */

    protected function getById(string | int $id) {
        $data = [];
        // query to get personnel by id
        $query = 'SELECT `id`, `firstName`, `lastName`, `email`, `jobTitle`, `departmentID` 
                  FROM `personnel` 
                  WHERE `id` = ?';

        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (false === $stmt) {
            $data = "Error";
        }

        $result = $stmt->get_result();

        if (!$result) {
            $data = "Error";
        }

        $personnel = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($personnel, $row);
        }

        // get all departments
        $query = 'SELECT id, name from department ORDER BY name';
        $result = $this->connect()->query($query);
        $departments = [];

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($departments, $row);
        }

        $data = [
            "personnel" => $personnel,
            "department" => $departments,
        ];

        $this->connect()->close();

        return $data;
    }

    /**
     * Updates a personnel record in the database
     * returns true if successful, false if not
     * @return bool|null
     */

    protected function update(string | int $id, string $firstName, string $lastName, string $email, string $jobTitle, int | string $departmentID) {
        $query = 'UPDATE personnel SET firstName = ?, lastName = ?, email = ?, jobTitle = ?, departmentID = ? WHERE id = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("ssssii", $firstName, $lastName, $email, $jobTitle, $departmentID, $id);
        $stmt->execute();

        $result = null;

        if (false === $stmt) {
            $result = "Error";
        }

        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Deletes a personnel record from the database
     * returns true if successful, false if not
     * @return bool|null
     */

    protected function delete(string | int $id) {
        $query = 'DELETE FROM personnel WHERE id = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = null;

        if (false === $stmt) {
            $result = "Error";
        }

        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
}
