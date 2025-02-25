<%@ page language="java" contentType="text/html; charset=UTF-8" import="java.util.List,tienda.*" pageEncoding="UTF-8" %>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FutCamis - Empresa</title>
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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-center" >¡Estos somos nosotros!</h1>
                    <p class="text-justify">En FutCamis estamos orgullosos de nuestra historia y nuestro compromiso con nuestros clientes. Desde nuestros humildes comienzos en Valencia, hemos crecido para convertirnos en una de las tiendas lideres en camisetas de fútbol en la región.</p>
                    <p class="text-justify">Nuestra empresa se fundo en 2003 con una vision clara: ofrecer a los aficionados del futbol la mejor seleccion de camisetas de calidad, junto con un servicio excepcional. A lo largo de los años, hemos expandido nuestro catalogo para incluir no solo camisetas de clubes famosos, sino también equipos internacionales y camisetas retro.</p>
                    <p class="text-justify">En FutCamis, nos tomamos en serio la calidad. Cada camiseta que vendemos pasa por rigurosos controles de calidad para garantizar que nuestros clientes reciban productos autenticos y duraderos. Trabajamos con los mejores proveedores para ofrecerte camisetas confeccionadas con los mejores materiales y los diseños mas actuales.</p>
                    <p class="text-justify">Nuestra organizacion esta comprometida con la satisfacción del cliente. Desde nuestro equipo de atencion al cliente hasta nuestro equipo de envios, cada miembro del equipo está dedicado a brindarte la mejor experiencia de compra posible.</p>
                    <p class="text-justify">Gracias por elegir FutCamis. Esperamos poder servirte durante muchos años mas y compartir nuestra pasion por el futbol contigo.</p>
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