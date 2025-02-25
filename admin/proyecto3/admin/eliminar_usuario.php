<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['codigo'])) {
    $codigo_usuario = $_GET['codigo'];

    // Verificar si el usuario tiene pedidos
    $conn = conectar();
    $pedido_sql = "SELECT * FROM pedidos WHERE persona = '$codigo_usuario'";
    $pedido_result = mysqli_query($conn, $pedido_sql);
    
    if (mysqli_num_rows($pedido_result) == 0) {
        // No hay pedidos asociados, se puede eliminar el usuario
        $delete_sql = "DELETE FROM usuarios WHERE codigo = '$codigo_usuario'";
        
        if (mysqli_query($conn, $delete_sql)) {
            $message = "Usuario eliminado correctamente.";
        } else {
            $error = "Error al eliminar usuario: " . mysqli_error($conn);
        }
    } else {
        $error = "No se puede eliminar el usuario. Tiene pedidos asociados.";
    }

    desconectar($conn);
}

header("Location: usuarios.php");
exit();
?>
