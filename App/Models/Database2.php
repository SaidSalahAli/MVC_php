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
        $this->connection->begin_transaction();

        try {
            // Validate tablename
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
                throw new Exception("Invalid table name format: $tableName");
            }
    
        // Prepare the SQL statement with a placeholder for the product ID
        $query = "SELECT $columns FROM $tableName WHERE id = ?";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        // handle connection errors
        
        // Bind the product ID parameter to the prepared statement
        $statement->bind_param('i', $productId);        
        // Execute the statement
        $success = $statement->execute();
        // handle execution errors.
        
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
    } catch (Exception $e) {
        $this->connection->rollback(); 
        echo "Error: " . $e->getMessage();
   
    }
    }
    
    
    public function get(string $tableName, $numRows = null, $columns = '*') {        
        // Begin Transaction
        $this->connection->begin_transaction();
    
        try {
            // Validate tablename
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
                throw new Exception("Invalid table name format: $tableName");
            }
            
            // Prepare the SQL statement
            $query = "SELECT $columns FROM $tableName";
            
            // Add LIMIT clause if numRows is specified
            if ($numRows !== null) {
                $query .= " LIMIT ?";
            }
            
            // Prepare the statement
            $statement = $this->connection->prepare($query);
            if (!$statement) {
                throw new Exception("Execution failed: " . $this->connection->error);
            }
            
            // Bind the numRows parameter to the prepared statement
            if ($numRows !== null) {
                $statement->bind_param('i', $numRows);
            }
            
           // Execute the statement
           $success = $statement->execute();
           print_r($statement);
           // Check for execution errors
           if (!$success) {
               throw new Exception("Execution failed: " . $statement->error);
           }
            // Get the result
            $result = $statement->get_result();
            
            // Fetch data and return as associative array
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            // Close the statement
            $statement->close();
            
            $this->connection->commit(); 
            return $data;
        } catch (Exception $e) {
            $this->connection->rollback(); 
            echo "Error: " . $e->getMessage();
        }
    }
    
    
        
    
    
    public function insert(string $tableName, array $data) {
              // Begin Transaction
              $this->connection->begin_transaction();
        try {
            // Validate tablename
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
                throw new Exception("Invalid table name format: $tableName");
            }
        // Prepare the SQL statement
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        if (!$statement) {
            throw new Exception("Execution failed: " . $this->connection->error);
        }
        
        
        // Bind the values to the prepared statement
        $types = str_repeat('s', count($data)); 
        // Assuming all values are strings
        
        $statement->bind_param($types, ...array_values($data));        
        // Execute the statement      
        $result = $statement->execute();
        // Check for execution errors
        if (!$result) {
            throw new Exception("Execution failed: " . $statement->error);
        }
        // Close the statement
        $statement->close();     
        $this->connection->commit();    
         return $result;
         } catch (Exception $e) {
            $this->connection->rollback(); 
           echo "Error: " . $e->getMessage();   
        }
    
    }

    public function update(string $tableName, array $data,  $condition) {
              // Begin Transactions
                                      $this->connection->begin_transaction();
        try {
            // Validate tablename
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
                throw new Exception("Invalid table name format: $tableName");
            }
        // Prepare the SQL statement
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
       
        $query = "UPDATE $tableName SET $set WHERE  $condition";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        if (!$statement) {
            throw new Exception("Execution failed: " . $this->connection->error);
        }
        
        // Bind the values to the prepared statement
        $types = str_repeat('s', count($data)); // Assuming all values are strings
        $values = array_values($data);
        $statement->bind_param($types, ...$values);
        print_r($statement);
        // Execute the statement
        $result = $statement->execute();
        if (!$result) {
            throw new Exception("Execution failed: " . $statement->error);
        }
        // Close the statement
        $statement->close();
        $this->connection->commit();   
        return $result;
    } catch (Exception $e) {
        $this->connection->rollback(); 
        echo "Error: " . $e->getMessage();
   
    }
    }
    
   
    public function delete(string $tableName, string $condition) {
              // Begin Transaction
              $this->connection->begin_transaction();
        try {
            // Validate tablename
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
                throw new Exception("Invalid table name format: $tableName");
            }
        // Prepare the SQL statement
        $query = "DELETE FROM $tableName WHERE $condition";
        
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        if (!$statement) {
            throw new Exception("Execution failed: " . $this->connection->error);
        }
        // Prepare the statement
        $statement = $this->connection->prepare($query);
        
        // Execute the statement
        $result = $statement->execute();
        if (!$result) {
            throw new Exception("Execution failed: " . $statement->error);
        }        
        // Close the statement
        $statement->close();
        $this->connection->commit();   
        return $result;
        
    } catch (Exception $e) {
        $this->connection->rollback(); 
        echo "Error: " . $e->getMessage();
   
    }
    }
}

// transaction