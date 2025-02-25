<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}

// Variables de filtros
$filter_usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
$filter_fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filter_fecha_opcion = isset($_GET['fecha_opcion']) ? $_GET['fecha_opcion'] : '=';
$filter_producto = isset($_GET['producto']) ? $_GET['producto'] : '';

// Obtener usuarios para el filtro de usuario
$conn = conectar();
$usuarios_sql = "SELECT codigo, nombre, apellidos FROM usuarios";
$usuarios_result = mysqli_query($conn, $usuarios_sql);

// Obtener productos para el filtro de producto
$productos_sql = "SELECT DISTINCT descripcion FROM productos";
$productos_result = mysqli_query($conn, $productos_sql);

// Construir la consulta con filtros
$sql = "SELECT pedidos.codigo, usuarios.nombre, usuarios.apellidos, pedidos.fecha, pedidos.importe, estados.descripcion AS estado
        FROM pedidos
        JOIN usuarios ON pedidos.persona = usuarios.codigo
        JOIN estados ON pedidos.estado = estados.codigo
        WHERE 1=1";

if ($filter_usuario) {
    $sql .= " AND usuarios.codigo = '$filter_usuario'";
}

if ($filter_fecha) {
    $sql .= " AND pedidos.fecha $filter_fecha_opcion '$filter_fecha'";
}

if ($filter_producto) {
    $sql .= " AND pedidos.codigo IN (
                SELECT detalle.codigo_pedido 
                FROM detalle 
                JOIN productos ON detalle.codigo_producto = productos.codigo 
                WHERE productos.descripcion LIKE '%$filter_producto%')";
}

$result = obtenerPedidos($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="icon" type="image/ico" href="../img/logo.ico" sizes="64x64">
    <link rel="stylesheet" href="../estilo/styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Listado de Pedidos</h2>
        <form method="GET" action="pedidos.php" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="usuario" class="form-select">
                        <option value="">Seleccionar Usuario</option>
                        <?php while ($usuario = mysqli_fetch_assoc($usuarios_result)) { ?>
                            <option value="<?php echo $usuario['codigo']; ?>" <?php echo ($filter_usuario == $usuario['codigo']) ? 'selected' : ''; ?>>
                                <?php echo $usuario['nombre'] . ' ' . $usuario['apellidos']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="fecha_opcion" class="form-select">
                        <option value="=" <?php echo ($filter_fecha_opcion == '=') ? 'selected' : ''; ?>>Igual a</option>
                        <option value="<=" <?php echo ($filter_fecha_opcion == '<=') ? 'selected' : ''; ?>>Menor o igual a</option>
                        <option value=">=" <?php echo ($filter_fecha_opcion == '>=') ? 'selected' : ''; ?>>Mayor o igual a</option>
                    </select>
                    <input type="date" name="fecha" class="form-control" value="<?php echo $filter_fecha; ?>">
                </div>
                <div class="col-md-3">
                    <select name="producto" class="form-select">
                        <option value="">Seleccionar Producto</option>
                        <?php while ($producto = mysqli_fetch_assoc($productos_result)) { ?>
                            <option value="<?php echo $producto['descripcion']; ?>" <?php echo ($filter_producto == $producto['descripcion']) ? 'selected' : ''; ?>>
                                <?php echo $producto['descripcion']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <a href="pedidos.php" class="btn btn-secondary">Borrar Filtros</a>
                </div>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Total</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) { ?>
                <tr>
                    <th scope="row"><?php echo $row['codigo']; ?></th>
                    <td><?php echo $row['nombre'] . ' ' . $row['apellidos']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['importe']; ?>€</td>
                    <td>
                        <a href="detalles_pedido.php?pedido_id=<?php echo $row['codigo']; ?>" class="btn btn-primary">Ver Detalles</a>
                        <?php if ($row['estado'] != 'Cancelado') { ?>
                        <form action="cambiar_estado.php" method="post" style="display: inline;">
                            <input type="hidden" name="pedido_id" value="<?php echo $row['codigo']; ?>">
                            <select name="nuevo_estado" class="form-select">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Enviado">Enviado</option>
                                <option value="Llegando">Llegando</option>
                                <option value="Entregado">Entregado</option>
                            </select>
                            <button type="submit" class="btn btn-success">Cambiar Estado</button>
                        </form>
                        <?php } ?>
                        
                        <?php if ($row['estado'] != 'Cancelado') { ?>
                            <form action="cancelar_pedido.php" method="post" style="display: inline;">
                                <input type="hidden" name="pedido_id" value="<?php echo $row['codigo']; ?>">
                                <button type="submit" class="btn btn-warning">Cancelar Pedido</button>
                            </form>
                        <?php } ?>
                        <?php if ($row['estado'] == 'Cancelado') { ?>
                            <form action="eliminar_pedido.php" method="post" style="display: inline;">
                                <input type="hidden" name="pedido_id" value="<?php echo $row['codigo']; ?>">
                                <button type="submit" class="btn btn-danger">Eliminar Pedido</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"></script>
</body>
</html>
<?php desconectar($conn); ?>
