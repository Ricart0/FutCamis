<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['codigo']) && isset($_GET['estado'])) {
    $codigo = $_GET['codigo'];
    $estado = $_GET['estado'];

    $conn = conectar();

    $sql = "UPDATE usuarios SET activo = $estado WHERE codigo = $codigo";
    
    if (mysqli_query($conn, $sql)) {
        desconectar($conn);
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el estado del usuario.";
    }

    desconectar($conn);
}
?>
