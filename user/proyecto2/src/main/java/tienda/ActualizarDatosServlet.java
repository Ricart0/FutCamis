package tienda;

import java.io.IOException;
import java.sql.SQLException;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;

public class ActualizarDatosServlet extends HttpServlet {

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        String calle = request.getParameter("calle");
        String cp = request.getParameter("cp");
        String poblacion = request.getParameter("poblacion");
        String provincia = request.getParameter("provincia");
        String telefono = request.getParameter("telefono");
        String tarjeta = request.getParameter("tarjeta");
        

        HttpSession session = request.getSession();
        Usuario usuario = (Usuario) session.getAttribute("usuario");
        

        usuario.setDomicilio(calle);
        usuario.setCp(cp);
        usuario.setPoblacion(poblacion);
        usuario.setProvincia(provincia);
        usuario.setTelefono(telefono);
        usuario.setTarjeta(tarjeta);
        

        try {
            AccesoBD.getInstance().actualizarUsuario(usuario);
        } catch (SQLException e) {
            e.printStackTrace();

            
            return;
        }
        
  

        response.sendRedirect("usuario.jsp");
    }
}

