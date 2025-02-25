package tienda;

import java.io.IOException;
import java.sql.SQLException;
import java.util.List;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;



public class CancelarPedidoServlet extends HttpServlet {
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        int codigoPedido = Integer.parseInt(request.getParameter("codigoPedido"));
        

        AccesoBD accesoBD = new AccesoBD();
        List<DetallePedido> detallesPedido = AccesoBD.getInstance().obtenerDetallesPedido(codigoPedido);
            
            
            for (DetallePedido detalle : detallesPedido) {
            ProductoBD producto = detalle.getProducto();
            int cantidad = detalle.getCantidad();
            try {
                AccesoBD.getInstance().devolverStockProducto(producto.getCodigo(), cantidad);
            } catch (SQLException e) {
                
                e.printStackTrace(); 
            }
}
        boolean cancelado = accesoBD.cancelarPedido(codigoPedido);
        
        if (cancelado) {

            response.sendRedirect("usuario.jsp");
        } else {
            response.setContentType("text/html");
            response.getWriter().println("<html><body><h1>Error al cancelar el pedido.</h1></body></html>");
        }
    }
}
