<%@ page language="java" contentType="text/html; charset=UTF-8" import="java.util.List,java.util.ArrayList,tienda.*" pageEncoding="UTF-8" %>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de productos</title>
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
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="productos.jsp" method="get">
                <div class="input-group">
                    <select name="liga" class="form-select">
                        <option value="">Todos</option>
                        <option value="espana">España</option>
                        <option value="italia">Italia</option>
                        <option value="inglaterra">Inglaterra</option>
                        <option value="alemania">Alemania</option>
                        <option value="seleccion">Selección</option>
                    </select>
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar...">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <% 
            AccesoBD con = AccesoBD.getInstance();
            String ligaSeleccionada = request.getParameter("liga");
            String busqueda = request.getParameter("busqueda");
            List<ProductoBD> productos;

            // Obtener todos los productos si no hay una liga seleccionada
            if (ligaSeleccionada == null || ligaSeleccionada.isEmpty()) {
                productos = con.obtenerProductosBD();
            } else {
                productos = con.obtenerProductosPorLiga(ligaSeleccionada);
            }

            // Filtrar productos por búsqueda si se ha proporcionado una
            if (busqueda != null && !busqueda.isEmpty()) {
                List<ProductoBD> productosFiltrados = new ArrayList<>();
                for (ProductoBD producto : productos) {
                    // Filtrar por nombre del producto
                    if (producto.getDescripcion().toLowerCase().contains(busqueda.toLowerCase())) {
                        productosFiltrados.add(producto);
                    }
                }
                productos = productosFiltrados;
            }
        %>
        <% for (ProductoBD producto : productos) { 
            boolean hayStock = producto.getStock() > 0;%>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <img src="<%= producto.getImagen() %>" alt="<%= producto.getDescripcion() %>" class="imgprod">
                </div>
                <h5  class="mb-3 text-center"><%= producto.getDescripcion() %> : <%= producto.getPrecio() %>€ </h5>
                <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Cantidad" aria-label="Cantidad" id="cantidad_<%=producto.getCodigo()%>" <%= hayStock ? "" : "disabled" %> min="1" max="<%= producto.getStock() %>">
                    <% if (hayStock) { %>
                        <input type="button" value="Añadir al carrito" onclick="agregarAlCarrito(<%=producto.getCodigo()%>,'<%= producto.getDescripcion() %>', <%= producto.getPrecio() %>, parseInt(document.getElementById('cantidad_<%=producto.getCodigo()%>').value))" class="btn btn-primary">
                    <% } else { %>
                        <span class="text-danger">No hay stock</span>
                    <% } %>
                </div>
            </div>
        <% } %>
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
