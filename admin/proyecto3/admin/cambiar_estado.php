<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido_id']) && isset($_POST['nuevo_estado'])) {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    // Consulta para obtener el código del estado actual del pedido
    $conn = conectar();
    $consulta_estado_actual = "SELECT estado FROM pedidos WHERE codigo = '$pedido_id'";
    $result_estado_actual = mysqli_query($conn, $consulta_estado_actual);

    if ($result_estado_actual) {
        $row = mysqli_fetch_assoc($result_estado_actual);
        $estado_actual = $row['estado'];

        // Consulta para actualizar el estado del pedido en la tabla de estados
        $update_sql = "UPDATE estados SET descripcion = '$nuevo_estado' WHERE codigo = '$estado_actual'";

        if (mysqli_query($conn, $update_sql)) {
            $message = "Estado del pedido cambiado correctamente.";
        } else {
            $error = "Error al cambiar el estado del pedido: " . mysqli_error($conn);
        }
    } else {
        $error = "Error al obtener el estado actual del pedido.";
    }

    desconectar($conn);
}

header("Location: pedidos.php");
exit();
?>
