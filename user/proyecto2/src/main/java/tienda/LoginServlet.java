package tienda;

import java.io.IOException;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;

public class LoginServlet extends HttpServlet {
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {


        String usuario = request.getParameter("username");
        String clave = request.getParameter("password");
        
   
        


        HttpSession session = request.getSession(true);


        AccesoBD accesoBD = AccesoBD.getInstance();
        int codigoUsuario = accesoBD.comprobarUsuarioBD(usuario, clave);


        if (codigoUsuario > 0) {

            String codigoUsuarioString = String.valueOf(codigoUsuario);
            
            Usuario usuarios = accesoBD.obtenerUsuarioPorCodigo(codigoUsuarioString);

            session.setAttribute("usuario", usuarios);
            response.sendRedirect("Tienda.jsp");
        } else {

            session.setAttribute("mensaje", "Usuario y/o contraseña incorrectos");

            response.sendRedirect("loginUsuario.jsp");
        }
    }
}
