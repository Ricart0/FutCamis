<%@ page language="java" contentType="text/html; charset=UTF-8" import="java.util.List,tienda.*" pageEncoding="UTF-8" %>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FutCamis - Carrito de Compras</title>
    <link rel="icon" type="image/ico" href="logo.ico" sizes="64x64">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body onload="mostrarProductosCarrito(); cargarCarritoEnNavbar();">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="Tienda.jsp" style="margin-left: 20px;">FutCamis</a>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Tienda.jsp">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="empresa.jsp">Empresa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacto.jsp">Contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="productos.jsp">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carrito.jsp">Carrito</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuario.jsp">Usuario</a>
                </li>
            </ul>
        </div>
        <% 
                Usuario usuario = (Usuario) session.getAttribute("usuario");
                boolean usuarioLogueado = (usuario != null);
                if (usuario != null) { 
                %>

            <div class="navbar-nav">
            <a class="nav-link" href="#" id="usuarioLink">
                <%= usuario.getNombre() %>
                <img src="img/usuario.png" alt="Usuario" class="carrito-img">
            </a>
            </div>
            <% } %>
        <div class="navbar-nav dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="cargarElementosCarrito()">
                <img src="img/carrito.png" alt="Carrito" class="carrito-img">
                <span id="carritoCount">0</span> 
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-center" id="carritoItems"></ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Carrito de Compras</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="carritoTableBody"> 
            </tbody>
        </table>
        <div class="text-center">
            <button class="btn btn-primary" onclick="vaciarCarrito()">Vaciar Carrito</button>
            <button class="btn btn-success" onclick="finalizarCompra(<%= usuarioLogueado %>)">Finalizar Compra</button>
            <div id="destino"></div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 FutCamis - Todos los derechos reservados</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="carrito.js"></script>
    <script>

            function cargarCarritoEnNavbar() {
            let carrito = JSON.parse(localStorage.getItem('carrito'));
            let carritoCount = 0;
            if (carrito) {
                carrito.forEach(item => {
                    carritoCount += item.cantidad;
                });
            }
            document.getElementById('carritoCount').innerText = carritoCount;
        }


        
    </script>
</body>
</html>
