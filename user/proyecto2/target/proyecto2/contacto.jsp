<%@ page language="java" contentType="text/html; charset=UTF-8" import="java.util.List,tienda.*" pageEncoding="UTF-8" %>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FutCamis - Contacto</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="icon" type="image/ico" href="img/logo.ico" sizes="64x64">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
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
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"  onclick="cargarElementosCarrito()">
                    <img src="img/carrito.png" alt="Carrito" class="carrito-img">
                    <span id="carritoCount">0</span> 
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-center" id="carritoItems">
                    
                </ul>
            </div>
            
        </nav>
        <div class="container mt-4">
            <h1 class="text-center">Contacto</h1>
            <div class="row">
                <div class="col-md-6">
                    <h2>Formulario de Contacto</h2>
                    <form>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">Mensaje:</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h2>Ubicación</h2>
                    
                    <p>FutCamis</p>
                    <p>Calle Futbol, 123</p>
                    <p>Valencia, España</p>
                    <h2>Horario de Atención</h2>
                    <p>Lunes a Viernes: 9:00 - 18:00</p>
                    <p>Sabados: 9:00 - 13:00</p>
                    <h2>Telefono</h2>
                    <p>+34 123 456 789</p>
                </div>
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
        

            window.onload = cargarCarritoEnNavbar;
        </script>
    </body>
</html>