<?php

class Listado{
 
    // database connection and table name
    private $conn;
    private $table_name = "listados";

 
    // object properties
    public $idListado;
    public $idCategoria;
    public $fechaCreacion;
    public $fechaCobro;
    public $fechaCompra;
    public $idCliente;
    public $nombre;
    public $filas;
    public $username;
    public $creado;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

// read data
function readData(){

    // select all query
    $query = "SELECT li.idListado, li.nombre, p.descripcion AS 'producto', lp.cant AS 'cantidad', c.username as 'cliente' 
    FROM " . $this->table_name . " li 
    JOIN listadoxproductos lp ON lp.idListado = li.idListado
    JOIN listadoxcliente lc ON lc.idListado = li.idListado
    JOIN productos p ON p.idProducto = lp.idProducto
    JOIN clientes c ON c.idCliente = lc.idCliente
    WHERE c.username like 'admin'
    ORDER BY li.nombre DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

    // read list name, client filtering
    function readName($username){

        // select all query
        $query = "SELECT li.idListado, li.nombre FROM " . $this->table_name . " li 
        JOIN listadoxcliente lc ON lc.idListado = li.idListado 
        JOIN clientes c ON c.idCliente = lc.idCliente 
        WHERE c.username = '".$username."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

//Creamos el listado de compras en la tabla listado
function createName(){

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_name . "
    (`idListado`, `fechaCreacion`, `fechaCobro`, `fechaCompra`, `nombre`) VALUES
    (NULL, curdate(), NULL, NULL, '".$this->nombre."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->fechaCreacion=htmlspecialchars(strip_tags($this->fechaCreacion));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':fechaCreacion', $this->fechaCreacion);
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}

function deleteListCompra(){
    
    //Insertamos query
    $query = "DELETE FROM listadoxsubcategoria WHERE idListado = '".$this->idListado."';
    DELETE FROM listadoxcliente WHERE idListado = '".$this->idListado."';
    DELETE FROM listados WHERE idListado = '".$this->idListado."';";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}

//Creamos el listadoXCliente
function createListXClien(){

    //Insertamos query
	$query = "INSERT INTO
    listadoxcliente
    (`idCliente`, `idListado`) VALUES
    ('".$this->idCliente."', '".$this->idListado."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->idCliente=htmlspecialchars(strip_tags($this->idCliente));
    $this->idListado=htmlspecialchars(strip_tags($this->idListado));

    // bind the values
    $stmt->bindParam(':idCliente', $this->idCliente);
    $stmt->bindParam(':idListado', $this->idListado);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}

function getId(){
    // select all query
    $query = "SELECT idListado FROM " . $this->table_name . " 
    WHERE nombre like '".$this->nombre."'";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();

    //Creamos vector
    $list_arr=array();
    //Extraemos el resultado
    extract($stmt->fetch(PDO::FETCH_ASSOC));
    //Lo metemos en un vector nuevo
    $list_item=array(
        //Tomamos el dato y lo guardamos en la variable
        "idListado" => $idListado
    );
    array_push($list_arr, $list_item);
 
    return $idListado;
}

//Le marcamos en 1 la casilla creado de la tabla listado
function getInsertExist(){

    //Insertamos query
	$query = "SELECT * FROM
    " . $this->table_name . " l
    JOIN listadoxcliente lc on lc.idListado = l.idListado
    JOIN listadoxsubcategoria ls on ls.idListado = l.idListado
    WHERE l.nombre like '".$this->nombre."'";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    $stmt->execute();

    return $stmt;
}
    
//Creamos el listado de compras en la tabla listadoxsubcategorias
function createCategory($array_cat){
    
    //Insertamos query
	$query = "INSERT INTO listadoxsubcategoria
    (`idListado`, `idCategoria`) VALUES ";

    for($i = 0; $i < $this->filas; $i++){

        $query .= "('".$this->idListado."','".$array_cat[$i]."')";
        
        if($i != $this->filas-1){
            //Con esto manejamos el uso de las comas entre cada VALUE
            $query .= ",";
        }

    }
    
    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){
    return true;
    }

    return false;
    }

}



?>