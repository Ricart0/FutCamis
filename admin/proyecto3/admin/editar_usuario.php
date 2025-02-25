<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '1') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];
    
    // Obtener detalles del usuario
    $conn = conectar();
    $sql = "SELECT * FROM usuarios WHERE codigo = '$usuario_id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $usuario = mysqli_fetch_assoc($result);
    } else {
        $error = "Error al obtener detalles del usuario: " . mysqli_error($conn);
    }
    desconectar($conn);
} else {
    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="icon" type="image/ico" href="../img/logo.ico" sizes="64x64">
    <link rel="stylesheet" href="../estilo/styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Editar Usuario</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } else { ?>
            <form action="guardar_usuario.php" method="POST">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario['codigo']; ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $usuario['apellidos']; ?>" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="activo" name="activo" <?php if ($usuario['activo']) echo "checked"; ?>>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
                <div class="mb-3">
                    <label for="domicilio" class="form-label">Domicilio</label>
                    <input type="text" class="form-control" id="domicilio" name="domicilio" value="<?php echo $usuario['domicilio']; ?>">
                </div>
                <div class="mb-3">
                    <label for="poblacion" class="form-label">Población</label>
                    <input type="text" class="form-control" id="poblacion" name="poblacion" value="<?php echo $usuario['poblacion']; ?>">
                </div>
                <div class="mb-3">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="provincia" name="provincia" value="<?php echo $usuario['provincia']; ?>">
                </div>
                <div class="mb-3">
                    <label for="cp" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $usuario['cp']; ?>" maxlength="5">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" maxlength="9">
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="usuarios.php" class="btn btn-secondary">Volver a Usuarios</a>
            </form>
        <?php } ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
