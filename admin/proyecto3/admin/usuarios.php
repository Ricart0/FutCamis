<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}

// Listar usuarios
$conn = conectar();
$sql = "SELECT * FROM usuarios";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="icon" type="image/ico" href="../img/logo.ico" sizes="64x64">
    <link rel="stylesheet" href="../estilo/styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Listado de Usuarios</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <th scope="row"><?php echo $row['codigo']; ?></th>
                    <td><?php echo $row['nombre'] . ' ' . $row['apellidos']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                    <td><?php echo $row['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                    <form action="editar_usuario.php" method="POST">
                            <input type="hidden" name="usuario_id" value="<?php echo $row['codigo']; ?>">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                        <?php if ($row['activo']) { ?>
                            <a href="cambiar_estado_usuario.php?codigo=<?php echo $row['codigo']; ?>&estado=0" class="btn btn-danger">Desactivar Usuario</a>
                        <?php } else { ?>
                            <a href="cambiar_estado_usuario.php?codigo=<?php echo $row['codigo']; ?>&estado=1" class="btn btn-success">Activar Usuario</a>
                        <?php } ?>
                        <?php 
                            // Verificar si el usuario tiene pedidos
                            $pedido_sql = "SELECT * FROM pedidos WHERE persona = '{$row['codigo']}'";
                            $pedido_result = mysqli_query($conn, $pedido_sql);
                            if (mysqli_num_rows($pedido_result) == 0) { ?>
                                <a href="eliminar_usuario.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-danger">Eliminar Usuario</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
<?php desconectar($conn); ?>
