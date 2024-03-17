<?php
include '../db.php';

class Department extends Db {

    /**
     * Inserts a new department record into the database
     * returns true if successful, false if not
     * @return bool
     */
    protected function create(string $name, string | int $locationID) {
        $query = 'INSERT INTO department (name, locationID) VALUES(?,?)';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("si", $name, $locationID);

        $result = null;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
        } else {
            $result = "Error";
        }

        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        $this->connect()->close();

        return $result;
    }

    /**
     * Returns all department records from the database
     * If a search term is provided, returns all department records that match the search term
     * @return array|string
     */

    protected function getAll(string | int | null $txt) {
        $query = 'SELECT d.id, d.name, d.locationID, l.name as location FROM department d LEFT JOIN location l ON (l.id = d.locationID) ORDER BY d.name';

        $result = null;
        try {
            $result = $this->connect()->query($query);
        } catch (mysqli_sql_exception $e) {
            return "Error";
        }

        if (!$result) {
            return "Error";
        } else {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($data, $row);
            }

            $found = [];
            # Check if the user has entered a search term
            if (isset($txt)) {
                $query = 'SELECT `d`.`id`, `d`.`name`, `l`.`id` as `locationID`, `l`.`name` AS `location` FROM `department` `d`
	                      LEFT JOIN `location` `l` ON (`l`.`id` = `d`.`locationID`) WHERE `d`.`name` LIKE ? OR `l`.`name` LIKE ? 
	                      ORDER BY  `d`.`name`, `l`.`name`';

                $stmt = $this->connect()->prepare($query);
                $likeText = "%" . $txt . "%";
                $stmt->bind_param("ss", $likeText, $likeText);

                $stmt->execute();

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
    }

    /**
     * Returns a single department record from the database and all location records
     * @return array|string
     */
    protected function getById(string | int $id) {
        $query = 'SELECT d.id, d.name, d.locationID, l.name as location 
                  FROM department d 
                  LEFT JOIN location l ON (l.id = d.locationID) 
                  WHERE d.id = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result) {
            return "Error";
        }

        $department = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($department, $row);
        }

        // get all Locations 
        $query = 'SELECT id, name FROM location ORDER BY name';
        $result = $this->connect()->query($query);
        $location = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($location, $row);
        }

        $data = [
            "department" => $department,
            "location" => $location
        ];

        $this->connect()->close();

        return $data;
    }

    /**
     * Updates a department record in the database
     * returns true if successful, false if not
     * @return bool
     */
    protected function update(string | int $id, string $name, string | int $locationID) {
        $query = 'UPDATE department SET `name` = ?, `locationID` = ?  WHERE `id` = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("sii", $name, $locationID, $id);

        $result = null;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
        } else {
            $result = "Error";
        }

        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        $this->connect()->close();

        return $result;
    }

    /**
     * Returns the number of personnel in a department record from the database
     * @return array|string
     */
    public function personnelCount(string | int $id) {
        $query = 'SELECT d.id, d.name, COUNT(p.departmentID) AS personnel_count
                  FROM department d
                  LEFT JOIN personnel p ON d.id = p.departmentID
                  WHERE d.id = ? GROUP BY d.id';

        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result) {
            return "Error";
        }

        $count = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($count, $row);
        }

        $this->connect()->close();

        return $count;
    }

    /**
     * Deletes a department record from the database
     * returns true if successful, false if not
     * @return bool
     */
    protected function delete(string | int $id) {
        $query = 'DELETE FROM department WHERE id = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);

        $result = null;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
        } else {
            $result = "Error";
        }

        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        $this->connect()->close();

        return $result;
    }
}
