<%@page language="java" contentType="text/html;charset=UTF-8" import="java.sql.*,java.io.*,javax.servlet.*,javax.servlet.http.*,java.util.*" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión / Registrarse</title>
    <link rel="icon" type="image/ico" href="logo.ico" sizes="64x64">
    <link rel="stylesheet" type="text/css" href="resguardo.css">
</head>
<body>
    <h1>Iniciar sesión / Registrarse</h1>
    <%-- Comprobar si hay un mensaje de error --%>
    <%
    String mensaje = (String) session.getAttribute("mensaje");
    if (mensaje != null) {
    %>
    <p style="color: red;"><%= mensaje %></p>
    <%
        session.removeAttribute("mensaje");
    }
    %>
    <%-- Comprobar si el usuario está autenticado --%>
    <%
    String usuario = (String) session.getAttribute("usuario");
    if (usuario == null || usuario.isEmpty()) {
    %>
    <form action="LoginServlet.html" method="post"  id="accesoForm">
        <div id="accesoForm">
            <label for="username">Usuario:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br>
        </div>
        <input type="radio" id="acceso" name="tipoAcceso" value="Acceso" checked onclick="mostrarFormulario('acceso')">
        <label for="acceso">Acceso</label>
        <input type="radio" id="registro" name="tipoAcceso" value="Registro" onclick="mostrarFormulario('registro')">
        <label for="registro">Registro</label><br>
        <input type="submit" value="Entrar">
        <a href="Tienda.jsp" class="button">Cancelar</a>
    </form>

    <form action="RegistroServlet.html" method="post" style="display:none;" id="registroForm" onsubmit="return validarContraseñas()">
        <label for="username">Usuario:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br>

        <label for="confirmarContrasena">Confirmar contraseña:</label><br>
        <input type="password" id="confirmarContrasena" name="confirmarContrasena" required><br>

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" required><br>
        <label for="domicilio">Domicilio:</label><br>
        <input type="text" id="domicilio" name="domicilio" required><br>
        <label for="poblacion">Población:</label><br>
        <input type="text" id="poblacion" name="poblacion" required><br>
        <label for="provincia">Provincia:</label><br>
        <input type="text" id="provincia" name="provincia" required><br>
        <label for="cp">Código Postal:</label><br>
        <input type="text" id="cp" name="cp" required><br>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" required><br>
        <input type="radio" id="acceso" name="tipoAcceso" value="Acceso" onclick="mostrarFormulario('acceso')">
        <label for="acceso">Acceso</label>
        <input type="radio" id="registro" name="tipoAcceso" value="Registro" checked>
        <label for="registro">Registro</label><br>
        <input type="submit" value="Registrar">
        <a href="Tienda.jsp" class="button">Cancelar</a>
    </form>

    <script>
        function mostrarFormulario(tipo) {
            if (tipo === 'registro') {
                document.getElementById('registroForm').style.display = 'block';
                document.getElementById('accesoForm').style.display = 'none';
                document.getElementById('accesoButton').style.display = 'inline';
                document.getElementById('registroButton').style.display = 'none';
            } else {
                document.getElementById('registroForm').style.display = 'none';
                document.getElementById('accesoForm').style.display = 'block';
                document.getElementById('accesoButton').style.display = 'none';
                document.getElementById('registroButton').style.display = 'inline';
            }
        }

        function validarContraseñas() {
            var password = document.getElementById('contrasena').value;
            var confirmarContrasena = document.getElementById('confirmarContrasena').value;
            

            if (password !== confirmarContrasena) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
            return true;
        }
    </script>
    
    <%
    } else {
    %>
    <p>Bienvenido, <%= usuario %>! Ya has iniciado sesión.</p>
    <form action="LogoutServlet.html" method="post">
        <input type="submit" value="Cerrar sesión">
    </form>
    <%
    }
    %>
</body>
</html>
