<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $activo = isset($_POST['activo']) ? 1 : 0;
    $domicilio = $_POST['domicilio'];
    $poblacion = $_POST['poblacion'];
    $provincia = $_POST['provincia'];
    $cp = $_POST['cp'];
    $telefono = $_POST['telefono'];

    // Actualizar los datos del usuario en la base de datos
    $conn = conectar();
    $update_sql = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', activo = '$activo', domicilio = '$domicilio', poblacion = '$poblacion', provincia = '$provincia', cp = '$cp', telefono = '$telefono' WHERE codigo = '$usuario_id'";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Los cambios se han guardado correctamente.";
    } else {
        $error = "Error al guardar los cambios: " . mysqli_error($conn);
    }

    desconectar($conn);
}

header("Location: usuarios.php");
exit();
?>
