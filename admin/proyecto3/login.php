<?php
session_start();
require 'includes/db.php'; // Asumiendo que db.php tiene la configuración de la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Conectar a la base de datos
    $conn = conectar();

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT codigo, usuario, clave, admin FROM usuarios WHERE usuario = '$usuario'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $codigo = $row['codigo'];
            $hashed_password = $row['clave'];
            $admin = $row['admin'];
            
            // Verificar la contraseña
            if ($clave == $hashed_password && $admin == 1) {
                $_SESSION['user_id'] = $codigo;
                $_SESSION['username'] = $usuario;
                $_SESSION['role'] = $admin;

                desconectar($conn); // Desconectar de la base de datos

                header("Location: admin/index.php"); // Redirigir a la página de administración
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Error en la consulta: " . mysqli_error($conn);
    }

    desconectar($conn); // Desconectar de la base de datos
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" type="text/css" href="estilo/styles.css">
    <link rel="stylesheet" type="text/css" href="estilo/login.css">
    <link rel="icon" type="image/x-icon" href="logo.ico" sizes="64x64">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar sesión</h1>
        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="clave">Contraseña:</label>
                <input type="password" id="clave" name="clave" required>
            </div>
            <input type="submit" value="Iniciar sesión">
        </form>
    </div>
</body>
</html>
