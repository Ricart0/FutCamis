<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $existencias = $_POST['existencias'];
    $imagen = $_POST['imagen'];
    $liga = $_POST['liga'];

    $conn = conectar();

    $sql = "INSERT INTO productos (descripcion, precio, existencias, imagen, liga) VALUES ('$descripcion', $precio, $existencias, '$imagen', '$liga')";

    if (mysqli_query($conn, $sql)) {
        desconectar($conn);
        header("Location: productos.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    desconectar($conn);
}
?>
