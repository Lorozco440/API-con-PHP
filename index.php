<?php

$host="localhost";
$usuario="root";
$password="";
$basededatos="api";


$conexion= new mysqli($host,$usuario,$password,$basededatos);

if($conexion->connect_error){
    die("Conexion no establecida". $conexion->connect_error);

}

header("Content-Type: application/json");
$metodo=$_SERVER['REQUEST_METHOD'];
//print_r($metodo);

$path=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
$buscarId = explode('/', $path);
$id= ($path!=='/') ? end($buscarId):null;

switch ($metodo){
        //SELECT usuarios
    case 'GET':
        //echo "Consulta de registros - GET";
        consulta($conexion, $id);
        break;
         //INSERT
    case 'POST':
        //echo "Insertar de registros - POST";
        instertar($conexion);
        break;
         //UPDATE
    case 'PUT':
        actualizar($conexion, $id);
        //echo "Edicion de registros - PUT";
        break;
         //DELETE
    case 'DELETE':
        borrar($conexion, $id);
        //echo " Borrado de registros - DELETE";
        break;
    default:
        echo "Metodo no permitido";
        break;
}

function consulta($conexion, $id){

    $sql= ($id===null) ? "SELECT * FROM usuarios":"SELECT * FROM usuarios WHERE id=$id";
    $resultado= $conexion->query($sql);

    if($resultado){
        $datos= array();
        while($fila= $resultado->fetch_assoc()){
            $datos[]= $fila;

        }
        echo json_encode($datos);
    }
}

function instertar($conexion){
    $dato= json_decode(file_get_contents('php://input'),true);
    $nombre= $dato['nombre'];
    //print_r($nombre);
    $sql= "INSERT INTO usuarios(nombre) VALUES ('$nombre')";
    $resultado= $conexion->query($sql);

    if($resultado){
        $dato['id'] = $conexion->insert_id;
        echo json_encode($dato);
    }else{
        echo json_encode(array('error'=>'Error al crear usuario'));
    }
}

function borrar($conexion, $id){
    echo "El id a borrar es: ". $id;

    $sql= "DELETE FROM usuarios WHERE id = $id";
    $resultado= $conexion->query($sql);

    if($resultado){
        
        echo json_encode(array('mensaje'=>'Usuario eliminado'));
    }else{
        echo json_encode(array('error'=>'Error al eliminar usuario'));
    }

}

function actualizar($conexion, $id){

    $dato = json_decode(file_get_contents('php://input'), true);
    $nombre = $dato['nombre'];
    echo "El id a editar es: ".$id. " con el dato ".$nombre;

    $sql = "UPDATE usuarios SET nombre = '$nombre' WHERE id = $id;";
    $resultado = $conexion->query($sql);

    if($resultado){
        echo json_encode(array('mensaje'=>' Usuario actualizado'));
    }else{
        echo json_encode(array('error'=>' Error al actualizar usuario'));
    }
} 

?>