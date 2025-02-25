<%@ page language="java" contentType="text/html; charset=UTF-8" import="java.util.List, tienda.*" pageEncoding="UTF-8" %>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FutCamis</title>
        <link rel="icon" type="image/ico" href="img/logo.ico" sizes="64x64">
        <link rel="stylesheet" type="text/css" href="styles.css">
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
            <div class="container">
                <h1>Usuario</h1>
        
                <div>
                    <%
                        Usuario usuariodos = (Usuario) session.getAttribute("usuario");
                        if (usuariodos == null) {
                    %>
                        <a href="loginUsuario.jsp" class="btn btn-primary">Iniciar Sesión</a>
                        
                    <%
                        } else {
                    %>
                        <form action="ActualizarDatosServlet.html" method="post">
                        <p>Usuario: <%= usuariodos.getUsuario() %> </p>
                        <p>Nombre: <%= usuariodos.getNombre() %> </p>
                        <p>Apellidos: <%= usuariodos.getApellidos() %></p>
                        <label for="calle">Calle:</label>
                        <input type="text" id="calle" name="calle" value="<%= usuario.getDomicilio() %>"><br><br>
                        
                        <label for="cp">CP:</label>
                        <input type="text" id="cp" name="cp" value="<%= usuario.getCp() %>"><br><br>
                        
                        <label for="poblacion">Población:</label>
                        <input type="text" id="poblacion" name="poblacion" value="<%= usuario.getPoblacion() %>"><br><br>
                        
                        <label for="provincia">Provincia:</label>
                        <input type="text" id="provincia" name="provincia" value="<%= usuario.getProvincia() %>"><br><br>
                        
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" value="<%= usuario.getTelefono() %>"><br><br>

                        <label for="tarjeta">Tarjeta:</label>
                        <input type="text" id="tarjeta" name="tarjeta" value="<%= usuario.getTarjeta() %>"><br><br>

                        <input type="submit" value="Modificar Datos">
                        </form>
                        <form action="LogoutServlet.html" method="post">
                            <input type="submit" value="Cerrar sesión">
                        </form>
                    <%
                        }
                    %>
                </div>

                <div>
                    <% 
                        Usuario usuariotres = (Usuario) session.getAttribute("usuario");
                        if (usuariotres == null) {
                    %>
                        
                    <% } else {
                        // Si hay un usuario en sesión, cargar los pedidos del usuario
                        AccesoBD accesoBD = new AccesoBD();
                        List<Pedido> pedidos = accesoBD.cargarPedidos(usuariotres.getId());
                        
                        // Mostrar los pedidos del usuario
                        for (Pedido pedido : pedidos) {
                            %>
                            <div class="card mb-3" style="max-width: 540px;">
                            <div class="row g-0">
                                <div class="col-md-4">
                                
                                </div>
                                <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Pedido #<%= pedido.getCodigo() %></h5>
                                    <p class="card-text">Fecha: <%= pedido.getFecha() %></p>
                                    <p class="card-text">Importe: <%= pedido.getImporte() %></p>
                                    <p class="card-text">Estado: <%= pedido.getEstadoP() %></p>

                              <h6 class="card-title">Detalles del Pedido:</h6>

                                <% 
                                // Obtener los detalles del pedido
                                List<DetallePedido> detalles = accesoBD.obtenerDetallesPedido(pedido.getCodigo());
                                for (DetallePedido detalle : detalles) {
                                %>  
                                
                                <p><%= detalle.getProducto().getDescripcion() %>: <%= detalle.getCantidad() %> unidades</p>
                                
                                <% 
                                }
                                %>
                                    <% 
                                    // Mostrar el botón de cancelar solo si el estado es "Pendiente"
                                    if (pedido.getEstadoP().equals("Pendiente")) {
                                %>
                                <form action="CancelarPedidoServlet.html" method="post">
                                    <input type="hidden" name="codigoPedido" value="<%= pedido.getCodigo() %>">
                                    <input type="submit" value="Cancelar Pedido">
                                </form>
                                <% } %>
                            </div>
                          
                                </div>
                            </div>
                            </div>
                            <% 
                                    }
                                }
                            %>
                    
                    
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
</body>
</html>
