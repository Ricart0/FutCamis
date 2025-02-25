package tienda;

import jakarta.json.*;
import java.io.IOException;
import java.io.InputStreamReader;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;

public class RecogerCarritoServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        ArrayList<Producto> carritoJSON = new ArrayList<>();
        HttpSession session = request.getSession();
    

        JsonReader jsonReader = Json.createReader(
            new InputStreamReader(
                request.getInputStream(),"utf-8"));
    
        JsonArray jobj = jsonReader.readArray();
    

        float totalPedido = 0; 
        for (int i = 0; i < jobj.size(); i++) {
            JsonObject prod = jobj.getJsonObject(i);
            Producto nuevo = new Producto();
            nuevo.setCodigo(prod.getInt("id"));
            nuevo.setNombre(prod.getString("nombre"));
            nuevo.setPrecio(Float.parseFloat(prod.get("precio").toString()));
            nuevo.setCantidad(prod.getInt("cantidad"));
            carritoJSON.add(nuevo);
            
            totalPedido += nuevo.getPrecio() * nuevo.getCantidad();
        }
        

        session.setAttribute("totalPedido", totalPedido);
    

        session.setAttribute("productos", carritoJSON);
        

        if (verificarStockSuficiente(carritoJSON)) {

            request.getRequestDispatcher("resguardoPedido.jsp").forward(request, response);
        } else {

            response.sendRedirect("errorStock.jsp");
        }
    }
    

    private boolean verificarStockSuficiente(List<Producto> productos) {
        boolean stockSuficiente = true;
        

        for (Producto producto : productos) {
            int codigoProducto = producto.getCodigo();
            int cantidadProducto = producto.getCantidad();

            try {
                AccesoBD accesoBD = AccesoBD.getInstance();
                int stockDisponible = accesoBD.obtenerStockDisponible(codigoProducto);
                if (cantidadProducto > stockDisponible) {
                    stockSuficiente = false;
                    break;
                }
            } catch (SQLException e) {
                e.printStackTrace();

                stockSuficiente = false;
            }
        }

        return stockSuficiente;
    }

}
