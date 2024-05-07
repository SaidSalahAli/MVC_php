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
    
    public function getSingleProduct(string $tableName, int $productId, $columns = '*') {
        // Prepare the SQL statement with a placeholder for the product ID
        $query = "SELECT $columns FROM $tableName WHERE id = ?";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        
        // Bind the product ID parameter to the prepared statement
        $statement->bind_param('i', $productId);        
        // Execute the statement
        $statement->execute();

        // Get the result
        $result = $statement->get_result();
        
        // Fetch and return the data
        $data = [];
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        }
        
        // Close the statement
        $statement->close();
        
        return $data;
    }
    
    public function get(string $tableName, $numRows = null, $columns = '*') {
        // Prepare the SQL statement
        $query = "SELECT $columns FROM $tableName";
        
        // Add LIMIT clause if numRows is specified
        if ($numRows !== null) {
            $query .= " LIMIT ?";
        }
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        
        // Bind the numRows parameter to the prepared statement
        if ($numRows !== null) {
            $statement->bind_param('i', $numRows);
        }
        
        // Execute the statement
        $statement->execute();
        
        // Get the result
        $result = $statement->get_result();
        
        // Fetch data and return as associative array
            $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        // Close the statement
        $statement->close();
        
        return $data;
    }
    
    public function insert(string $tableName, array $data) {
        // Prepare the SQL statement
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        
        // Bind the values to the prepared statement
        $types = str_repeat('s', count($data)); // Assuming all values are strings
        $statement->bind_param($types, ...array_values($data));
        
        // Execute the statement
        $result = $statement->execute();
        
        // Close the statement
        $statement->close();
        
        return $result;
    }

    public function update(string $tableName, array $data, string $condition) {
        // Prepare the SQL statement
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $query = "UPDATE $tableName SET $set WHERE $condition";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        
        // Bind the values to the prepared statement
        $types = str_repeat('s', count($data)); // Assuming all values are strings
        $values = array_merge(array_values($data), [$condition]);
        $statement->bind_param($types . 's', ...$values);
        
        // Execute the statement
        $result = $statement->execute();
        
        // Close the statement
        $statement->close();
        
        return $result;
    }
   
    public function delete(string $tableName, string $condition) {
        // Prepare the SQL statement
        $query = "DELETE FROM $tableName WHERE $condition";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        
        // Execute the statement
        $result = $statement->execute();
        
        // Close the statement
        $statement->close();
        
        return $result;
    }
}