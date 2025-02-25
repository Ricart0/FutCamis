<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}
$conn = conectar();
// Verificar si se proporciona el ID del pedido
if (isset($_GET['pedido_id'])) {
    $pedido_id = $_GET['pedido_id'];

    // Consulta para obtener los detalles del pedido
    $sql_pedido = "SELECT pedidos.codigo, usuarios.nombre, usuarios.apellidos, pedidos.fecha, pedidos.importe, estados.descripcion AS estado
                   FROM pedidos
                   JOIN usuarios ON pedidos.persona = usuarios.codigo
                   JOIN estados ON pedidos.estado = estados.codigo
                   WHERE pedidos.codigo = '$pedido_id'";
    $result_pedido = mysqli_query($conn, $sql_pedido);

    // Verificar si se encontró el pedido
    if (mysqli_num_rows($result_pedido) > 0) {
        $pedido = mysqli_fetch_assoc($result_pedido);
    } else {
        echo "Pedido no encontrado.";
        exit();
    }

    // Consulta para obtener los productos del pedido
    $sql_productos = "SELECT detalle.codigo_producto, productos.descripcion AS nombre_producto, detalle.unidades, detalle.precio_unitario
                      FROM detalle
                      JOIN productos ON detalle.codigo_producto = productos.codigo
                      WHERE detalle.codigo_pedido = '$pedido_id'";
    $result_productos = mysqli_query($conn, $sql_productos);
} else {
    echo "ID del pedido no proporcionado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link rel="icon" type="image/ico" href="../img/logo.ico" sizes="64x64">
    <link rel="stylesheet" href="../estilo/styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Detalles del Pedido</h2>
        <div>
            <p><strong>Número de Pedido:</strong> <?php echo $pedido['codigo']; ?></p>
            <p><strong>Usuario:</strong> <?php echo $pedido['nombre'] . ' ' . $pedido['apellidos']; ?></p>
            <p><strong>Fecha:</strong> <?php echo $pedido['fecha']; ?></p>
            <p><strong>Estado:</strong> <?php echo $pedido['estado']; ?></p>
            <p><strong>Total:</strong> <?php echo $pedido['importe']; ?>€</p>
        </div>
        <h3>Productos:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Precio Unitario</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($result_productos)) { ?>
                <tr>
                    <td><?php echo $producto['nombre_producto']; ?></td>
                    <td><?php echo $producto['unidades']; ?></td>
                    <td><?php echo $producto['precio_unitario']; ?>€</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="pedidos.php" class="btn btn-secondary">Volver a Pedidos</a>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
