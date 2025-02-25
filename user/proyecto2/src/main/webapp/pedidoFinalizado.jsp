<%@ page import="java.util.List" %>
<%@ page import="tienda.Producto" %>
<%@ page import="tienda.Usuario" %>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<html>
<head>
    <title>Pedido Finalizado</title>
    <link rel="icon" type="image/ico" href="logo.ico" sizes="64x64">
    <link rel="stylesheet" type="text/css" href="resguardo.css">
</head>
<body>
    <h1>Pedido Finalizado</h1>
    <p>Su pedido ha sido tramitado satisfactoriamente.</p>
    
   
    <h2>Datos del Usuario:</h2>
    <ul>
        <li><strong>Nombre:</strong> <%= ((Usuario) request.getSession().getAttribute("usuario")).getNombre() %></li>
        <li><strong>Domicilio:</strong> <%= ((Usuario) request.getSession().getAttribute("usuario")).getDomicilio() %></li>
        <li><strong>Código Postal:</strong> <%= ((Usuario) request.getSession().getAttribute("usuario")).getCp() %></li>
        <li><strong>Población:</strong> <%= ((Usuario) request.getSession().getAttribute("usuario")).getPoblacion() %></li>
        <li><strong>Provincia:</strong> <%= ((Usuario) request.getSession().getAttribute("usuario")).getProvincia() %></li>
        <li><strong>Teléfono:</strong> <%= ((Usuario) request.getSession().getAttribute("usuario")).getTelefono() %></li>
        <li><strong>Codigo pedido:</strong> <%= session.getAttribute("codigoPedido") %></li>
    </ul>
    
    
    <h2>Productos del Pedido:</h2>
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
            List<Producto> productos = (List<Producto>) session.getAttribute("productos");
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
    </table>
    
    <h2>Total del Pedido:</h2>
    <p><strong><%= session.getAttribute("totalPedido") %></strong></p>
    <button onclick="volver()">Volver al inicio</button>
    <script>

            function volver() {
            window.location.href = "Tienda.jsp";
        }
   
        localStorage.removeItem('carrito');
        session.removeAttribute("productos");
        session.removeAttribute("totalPedido");
        session.removeAttribute("codigoPedido");

    </script>
</body>
</html>
