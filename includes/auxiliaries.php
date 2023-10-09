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
            echo $stmt->bindParam($param, $paramvalue);
        }
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


    //THE READ FUNCTION WITHOUT A WHERE CLAUSE
    public function readall()
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //THE READ FUNCTION
    public function update($id, $data)
    {
        $fields = array();
        $params = array(':id' => $id);
        //BREAK THE $data OBJECTS AND LOOP THEM TO VARIOUS ARRAYS BASED ON HOW YOU WILL USE IT
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        //CONCATINATE THE FIELD ARRAY TO CREATE A QUERY
        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $param => $paramvalue) {
            echo $stmt->bindParam($param, $paramvalue);
        }
    }
};
