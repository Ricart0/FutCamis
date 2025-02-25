<?php

function conectar() {
   $servername = "localhost";
   $username = "root";
   $password = "250799";
   $dbname = "daw";

   $conn = mysqli_connect($servername, $username, $password, $dbname);

   if (!$conn) {
       die("Connection failed: " . mysqli_connect_error());
   }
   return $conn;
}

function desconectar($conn) {
   mysqli_close($conn);
}

function obtenerProductos() {
   $conn = conectar();
   $sql = "SELECT * FROM productos";
   $result = mysqli_query($conn, $sql);
   desconectar($conn);
   return $result;
}

function obtenerPedidos($sql) {
    $conn = conectar();
    $result = mysqli_query($conn, $sql);
    $pedidos = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $pedidos[] = $row;
        }
    }

    desconectar($conn);
    return $pedidos;
}


?>
