<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];

    $conn = conectar();

    // Consulta para obtener los detalles del pedido
    $consulta_detalles = "SELECT codigo_producto, unidades FROM detalle WHERE codigo_pedido = '$pedido_id'";
    $result_detalles = mysqli_query($conn, $consulta_detalles);

    // Restaurar el stock de productos cancelados
    while ($row = mysqli_fetch_assoc($result_detalles)) {
        $codigo_producto = $row['codigo_producto'];
        $unidades = $row['unidades'];

        // Consulta para actualizar el stock de productos
        $update_stock = "UPDATE productos SET existencias = existencias + '$unidades' WHERE codigo = '$codigo_producto'";
        mysqli_query($conn, $update_stock);
    }

    // Consulta para obtener el código del estado actual del pedido
    $consulta_estado_actual = "SELECT estado FROM pedidos WHERE codigo = '$pedido_id'";
    $result_estado_actual = mysqli_query($conn, $consulta_estado_actual);

    if ($result_estado_actual) {
        $row = mysqli_fetch_assoc($result_estado_actual);
        $estado_actual = $row['estado'];

        // Consulta para actualizar el estado del pedido en la tabla de estados
        $update_sql = "UPDATE estados SET descripcion = 'Cancelado' WHERE codigo = '$estado_actual'";
        if (mysqli_query($conn, $update_sql)) {
            $message = "Pedido cancelado correctamente.";
        } else {
            $error = "Error al cancelar el pedido: " . mysqli_error($conn);
        }
    } else {
        $error = "Error al obtener el estado actual del pedido.";
    }

    desconectar($conn);
}

header("Location: pedidos.php");
exit();
?>
