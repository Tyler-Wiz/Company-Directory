<?php

include '../db.php';

class Location extends Db {

    /**
     * Inserts a new location record into the database
     * returns true if successful, false if not
     * @return bool
     */

    protected function create(string $name) {
        $query = 'INSERT INTO location (name) VALUES (?)';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("s", $name);

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
     * Returns all location records from the database
     * If a search term is provided, returns all location records that match the search term
     * @return array|string
     */

    protected function getAll(string | int | null $txt) {
        $result = null;
        $query = 'SELECT id, name FROM location
                    ORDER BY name';
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
                $query = 'SELECT `l`.`id`, `l`.`name` FROM `location` `l`  WHERE `l`.`name` LIKE ? ORDER BY `l`.`name`';
                $stmt = $this->connect()->prepare($query);
                $likeText = "%" . $txt . "%";
                $stmt->bind_param("s", $likeText);

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
     * Returns a single location record from the database
     * @return array|string
     */
    protected function getById(string | int $id) {
        $query = 'SELECT id, name FROM location WHERE id = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);

        $result = null;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
        } else {
            return "Error";
        }

        if (!$result) {
            return false;
        } else {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($data, $row);
            }

            $this->connect()->close();

            return $data;
        }
    }

    /**
     * Updates a location record in the database
     * returns true if successful, false if not
     * @return bool
     */

    protected function update(string | int $id, string $name) {
        $query = 'UPDATE location SET name = ? WHERE id = ?';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("si", $name, $id);

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
     * Checks if a location has any departments associated with it
     * returns the number of departments associated with the location and the location name
     * @return array|string
     */

    protected function checkDeptCount(string | int $id) {
        $query = 'SELECT l.id, l.name, COUNT(d.locationID) AS location_count FROM location l LEFT JOIN department d ON d.locationID = l.id WHERE l.id = ?  GROUP BY l.id';
        $stmt = $this->connect()->prepare($query);
        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = null;

        $result = $stmt->get_result();
        # Check for successful query and fetch data
        if (!$result) {
            return false;
        } else {
            $department = [];
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($department, $row);
            }

            $this->connect()->close();

            return $department;
        }
    }

    /**
     * Deletes a location record from the database
     * returns true if successful, false if not
     * @return bool
     */

    protected function delete(string | int $id) {
        $query = 'DELETE FROM location WHERE id = ?';
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
