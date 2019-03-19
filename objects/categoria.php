<?php

class Categoria{
 
    // database connection and table name
    private $conn;
    private $table_name = "categorias";
 
    // object properties
    public $idCategoria;
    public $descripcion;
    public $superior;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read categories
    function read(){

    // select all query
    $query = "SELECT idCategoria, descripcion, idCategoriaSuperiorFK as 'superior'
                FROM " . $this->table_name . " 
                WHERE idCategoriaSuperiorFK is not null";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>