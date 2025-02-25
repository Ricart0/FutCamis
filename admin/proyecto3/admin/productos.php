<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}

// Manejar la solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $conn = conectar();
    $delete_sql = "DELETE FROM productos WHERE codigo = '$delete_id'";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "Producto eliminado correctamente.";
    } else {
        $error = "Error al eliminar el producto.";
    }
    desconectar($conn);
}

// Manejar la solicitud de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $existencias = $_POST['existencias'];
    $imagen = $_POST['imagen'];
    $liga = $_POST['liga'];

    $conn = conectar();
    $edit_sql = "UPDATE productos SET 
        descripcion = '$descripcion', 
        precio = '$precio', 
        existencias = '$existencias', 
        imagen = '$imagen', 
        liga = '$liga' 
        WHERE codigo = '$edit_id'";
    
    if (mysqli_query($conn, $edit_sql)) {
        $message = "Producto actualizado correctamente.";
    } else {
        $error = "Error al actualizar el producto.";
    }
    desconectar($conn);
}

// Obtener los detalles del producto para edición
$edit_product = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $conn = conectar();
    $edit_sql = "SELECT * FROM productos WHERE codigo = '$edit_id'";
    $result = mysqli_query($conn, $edit_sql);
    $edit_product = mysqli_fetch_assoc($result);
    desconectar($conn);
}

// Listar productos
$result = obtenerProductos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="icon" type="image/ico" href="../img/logo.ico" sizes="64x64">
    <link rel="stylesheet" href="../estilo/styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Listado de Productos</h2>
        <?php
        if (isset($message)) {
            echo "<div class='alert alert-success'>$message</div>";
        }
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Existencias</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <th scope="row"><?php echo $row['codigo']; ?></th>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['precio']; ?></td>
                    <td><?php echo $row['existencias']; ?></td>
                    <td>
                        <a href="productos.php?edit_id=<?php echo $row['codigo']; ?>" class="btn btn-primary">Editar</a>
                        <?php 
                            // Solo mostrar el botón de eliminar si el producto no está en ningún pedido
                            $conn = conectar();
                            $pedido_sql = "SELECT * FROM detalle WHERE codigo_producto = '{$row['codigo']}'";
                            $pedido_result = mysqli_query($conn, $pedido_sql);
                            desconectar($conn);
                            
                            if (mysqli_num_rows($pedido_result) == 0) {
                                echo "<form action='productos.php' method='post' style='display:inline;'>";
                                echo "<input type='hidden' name='delete_id' value='{$row['codigo']}'>";
                                echo "<button type='submit' class='btn btn-danger'>Eliminar</button>";
                                echo "</form>";
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if ($edit_product) { ?>
        <h2>Editar Producto</h2>
        <form action="productos.php" method="post">
            <input type="hidden" name="edit_id" value="<?php echo $edit_product['codigo']; ?>">
            <div class="mb-3">
                <label for="descripcion">Descripción:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $edit_product['descripcion']; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo $edit_product['precio']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="existencias">Existencias:</label>
                <input type="number" class="form-control" id="existencias" name="existencias" value="<?php echo $edit_product['existencias']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="imagen">Imagen:</label>
                <input type="text" class="form-control" id="imagen" name="imagen" value="<?php echo $edit_product['imagen']; ?>" required>
            </div>
            <div classclass="mb-3">
                <label for="liga">Liga:</label>
                <input type="text" class="form-control" id="liga" name="liga" value="<?php echo $edit_product['liga']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </form>
        <?php } else { ?>
        <h2>Añadir Nuevo Producto</h2>
        <form action="add_producto.php" method="post">
            <div class="mb-3">
                <label for="descripcion">Descripción:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="existencias">Existencias:</label>
                <input type="number" class="form-control" id="existencias" name="existencias" required>
            </div>
            <div class="mb-3">
                <label for="imagen">Imagen:</label>
                <input type="text" class="form-control" id="imagen" name="imagen" required>
            </div>
            <div class="mb-3">
                <label for="liga">Liga:</label>
                <input type="text" class="form-control" id="liga" name="liga" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
        </form>
        <?php } ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>


