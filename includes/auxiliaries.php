<?php

//CREATING THE ADMIN CLASS
class Admin
{
    //DECLARE $conn AS A PRIVATE VARIABLE
    private $conn;
    //DECLARE $table AS A PRIVATE VARIABLE
    private $table;

    //DECLARING CONSTRUCTOR
    public function __construct($conn, $table)
    {
        $this->conn = $conn;
        $this->table = $table;
    }

    //THE CREATE FUNCTION
    public function create($data)
    {
        //DECARE EMPTY ARRAYS
        $fields = array();
        $values = array();
        $params = array();

        //BREAK THE $data OBJECTS AND LOOP THEM TO VARIOUS ARRAYS BASED ON HOW YOU WILL USE IT
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $values[] = ":$key";
            $params[":$key"] = $value;
        }
        //CONCATINATE THE FIELD ARRAY TO CREATE A QUERY
        $sql = "INSERT INTO " . $this->table . "(" . implode(", ", $fields) . ") VALUES(" . implode(", ", $values) . ")";
        $stmt = $this->conn->prepare($sql);
        // BIND PARAMETERS WITH THE HELP OF $param OBJECTS

        foreach ($params as $param => $paramvalue) {
            $stmt->bindValue($param, $paramvalue);
        }

        // EXECUTE THE QUERY
        $result = $stmt->execute();
        if ($result === false) {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
        }
        return true;
    }
    //THE READ FUNCTION WITH A WHERE CLAUSE
    public function read($searchField, $searchValue)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE $searchField = :$searchField";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":$searchField", $searchValue);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //THE READ FUNCTION WITHOUT A LIMIT
    public function readAll($primaryKey = 'id')
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY " . $primaryKey . " DESC ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    //THE READ FUNCTION WITH NEWEST AT TOP
    public function readWithLimit($limit, $primaryKey)
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY " . $primaryKey . " DESC LIMIT " . $limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readWithNoRestriction()
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //THE UPDATE FUNCTION
    public function update($id, $data, $whereColumn = 'id')
    {
        // Declare empty arrays
        $fields = array();
        $params = array(':' . $whereColumn => $id);

        // Break the $data objects and loop them to various arrays based on how you will use it
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        // Construct the SQL UPDATE query
        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE $whereColumn = :$whereColumn";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters with the help of $params objects
        foreach ($params as $param => $paramvalue) {
            $stmt->bindValue($param, $paramvalue);
        }

        // Execute the query
        $result = $stmt->execute();
        if ($result === false) {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false; // Return false on error
        }
        return true;
    }
}
