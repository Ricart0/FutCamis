package tienda;

import java.io.IOException;
import java.sql.SQLException;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;


public class RegistroServlet extends HttpServlet {
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

        String usuario = request.getParameter("username");
        String clave = request.getParameter("contrasena");
        String nombre = request.getParameter("nombre");
        String apellidos = request.getParameter("apellidos");
        String domicilio = request.getParameter("domicilio");
        String poblacion = request.getParameter("poblacion");
        String provincia = request.getParameter("provincia");
        String cp = request.getParameter("cp");
        String telefono = request.getParameter("telefono");


        AccesoBD accesoBD = AccesoBD.getInstance();

        try {

            accesoBD.insertarUsuario(usuario, clave, nombre, apellidos, domicilio, poblacion, provincia, cp, telefono);


            response.sendRedirect("loginUsuario.jsp");
        } catch (SQLException e) {

            e.printStackTrace();

            response.sendRedirect("error.jsp");
        }
    }
}
