<?php 

class Database {
    private $connection;

    public function __construct(string $host, string $username, string $password, string $database) {
        // Establish database connection
        $this->connection = new mysqli($host, $username, $password, $database);
        // Check for connection errors
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    
    public function get(string $tableName, $numRows = null, $columns = '*') {
        // Build and execute the SELECT query
        $query = "SELECT $columns FROM $tableName";
        // Add LIMIT clause if numRows is specified
        if ($numRows !== null) {
            $query .= " LIMIT $numRows";
        }
        $result = $this->connection->query($query);

        // Fetch data and return as associative array
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function insert(string $tableName, array $data) {
        // Build and execute the INSERT query
        $columns = implode(', ', array_keys($data));
        $values = implode("', '", array_values($data));
        $query = "INSERT INTO $tableName ($columns) VALUES ('$values')";
        return $this->connection->query($query);
    }

    public function update(string $tableName, array $data, string $condition) {
        // Build and execute the UPDATE query
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ', ');
        $query = "UPDATE $tableName SET $set WHERE $condition";
        return $this->connection->query($query);
    }

    public function delete(string $tableName, string $condition) {
        // Build and execute the DELETE query
        $query = "DELETE FROM $tableName WHERE $condition";
        return $this->connection->query($query);
    }
}