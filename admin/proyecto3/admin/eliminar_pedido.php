<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];

    $conn = conectar();

    // Consultar el estado del pedido en la tabla de pedidos
    $estado_pedido_sql = "SELECT estado FROM pedidos WHERE codigo = '$pedido_id'";
    $result_pedido = mysqli_query($conn, $estado_pedido_sql);
    $row_pedido = mysqli_fetch_assoc($result_pedido);
    $codigo_estado_pedido = $row_pedido['estado'];

    // Consultar la descripción del estado en la tabla de estados
    $estado_sql = "SELECT descripcion FROM estados WHERE codigo = '$codigo_estado_pedido'";
    $result_estado = mysqli_query($conn, $estado_sql);
    $row_estado = mysqli_fetch_assoc($result_estado);
    $descripcion_estado = $row_estado['descripcion'];

    // Verificar si el estado del pedido es "Cancelado"
    if ($descripcion_estado === 'Cancelado') {
        // Eliminar el detalle del pedido
        $delete_detalle_sql = "DELETE FROM detalle WHERE codigo_pedido = '$pedido_id'";
        mysqli_query($conn, $delete_detalle_sql);

        // Eliminar el pedido
        $delete_pedido_sql = "DELETE FROM pedidos WHERE codigo = '$pedido_id'";
        mysqli_query($conn, $delete_pedido_sql);

        // Eliminar la fila de estado correspondiente al pedido en la tabla de estados
        $delete_estado_sql = "DELETE FROM estados WHERE codigo = '$codigo_estado_pedido'";
        mysqli_query($conn, $delete_estado_sql);

        $message = "Pedido eliminado correctamente.";
    } else {
        $error = "No se puede eliminar este pedido porque no está cancelado.";
    }

    desconectar($conn);
}

header("Location: pedidos.php");
exit();
?>
