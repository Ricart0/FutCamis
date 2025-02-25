<%@ page import="java.util.List" %>
<%@ page import="tienda.Producto" %>
<%@ page import="tienda.Usuario" %>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ page import="tienda.AccesoBD" %>
<%@ page import="java.sql.SQLException" %>
<%@ page import="java.util.ArrayList" %>

<%
    
    Usuario usuario = (Usuario) session.getAttribute("usuario");

    // Si el usuario no está en la sesión, redirigir a la página de login
    if (usuario == null) {
        response.sendRedirect("login.jsp");
    }

    // Obtener los productos del carrito
    List<Producto> productos = (List<Producto>) session.getAttribute("productos");
    float totalPedido = (float) session.getAttribute("totalPedido");
%>



<html>
<head>
    <title>Resguardo del Pedido</title>
    <link rel="stylesheet" type="text/css" href="resguardo.css"> 
</head>
<body>
    <form action="TramitacionServlet.html" method="post">
    <h1>Resguardo del Pedido</h1>

    <h2>Datos de Envio y Facturacion</h2>
    <p><strong>Nombre:</strong> <%= usuario.getNombre() %> <%= usuario.getApellidos() %></p>
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
        <input type="hidden" name="totalPedido" value="<%= totalPedido %>">
        <input type="hidden" name="productos" value="<%= productos %>">
        
    <h2>Productos en el Pedido:</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <% 
            if (productos != null) {
                for (Producto producto : productos) {
            %>
            <tr>
                <td><%= producto.getNombre() %></td>
                <td><%= producto.getPrecio() %></td>
                <td><%= producto.getCantidad() %></td>
            </tr>
            <% 
                }
            }
            %>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: center;"><strong>Total del Pedido: <%= totalPedido %></strong></td>
            </tr>
        </tfoot>
    </table>
    
    
    
   
        <button type="submit">Finalizar Pedido</button>

    </form>
    <button onclick="cancelar()">Cancelar</button>
    <script>
        
        function cancelar() {
            window.location.href = "carrito.jsp";
        }
    </script>
</body>
</html>
