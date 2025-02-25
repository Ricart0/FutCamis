package tienda;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpSession;


public class AccesoBD {
	private static AccesoBD instanciaUnica = null;
	private Connection conexionBD = null;

	public static AccesoBD getInstance(){
		if (instanciaUnica == null){
			instanciaUnica = new AccesoBD();
		}
		return instanciaUnica;
	}

	public AccesoBD() {
		abrirConexionBD();
	}

	public void abrirConexionBD() {
		if (conexionBD == null)
		{
			String JDBC_DRIVER = "org.mariadb.jdbc.Driver";
			// daw es el nombre de la base de datos que hemos creado con anterioridad.
			String DB_URL = "jdbc:mariadb://localhost:3306/daw";
			// El usuario root y su clave son los que se puso al instalar MariaDB.
			String USER = "root";
			String PASS = "250799";
			try {
				Class.forName(JDBC_DRIVER);
				conexionBD = DriverManager.getConnection(DB_URL,USER,PASS);
			}
			catch(Exception e) {
				System.err.println("No se ha podido conectar a la base de datos");
				System.err.println(e.getMessage());
                e.printStackTrace();
			}
		}
	}

	public boolean comprobarAcceso() {
		abrirConexionBD();
		return (conexionBD != null);
	}


public List<ProductoBD> obtenerProductosBD() {
	abrirConexionBD();

	ArrayList<ProductoBD> productos = new ArrayList<>();

	try {
		// hay que tener en cuenta las columnas de la tabla de productos
	String con = "SELECT codigo,descripcion,precio,existencias,imagen FROM productos";
		Statement s = conexionBD.createStatement();
		ResultSet resultado = s.executeQuery(con);
		while(resultado.next()){
			ProductoBD producto = new ProductoBD();
			producto.setCodigo(resultado.getInt("codigo"));
			producto.setDescripcion(resultado.getString("descripcion"));
			producto.setPrecio(resultado.getFloat("precio"));
			producto.setStock(resultado.getInt("existencias"));
			producto.setImagen(resultado.getString("imagen"));
			productos.add(producto);
		}
	}
	catch(Exception e) {
		System.err.println("Error ejecutando la consulta a la base de datos");
		System.err.println(e.getMessage());
	}

	return productos;
}

public int comprobarUsuarioBD(String usuario, String clave) {
	abrirConexionBD();

	int codigo = -1;

	try{
		String con = "SELECT codigo FROM usuarios WHERE usuario=? AND clave=?";
		PreparedStatement s = conexionBD.prepareStatement(con);
		s.setString(1,usuario);
		s.setString(2,clave);

		ResultSet resultado = s.executeQuery();


		if ( resultado.next() ) {
			codigo =  resultado.getInt("codigo");
		}
	}
	catch(Exception e) {

		// Error en la conexión con la BD
		System.err.println("Error verificando usuario/clave");
		System.err.println(e.getMessage());
		e.printStackTrace();
	}

	return codigo;
}

public void insertarUsuario(String usuario, String clave, String nombre, String apellidos, String domicilio, String poblacion, String provincia, String cp, String telefono) throws SQLException {

	abrirConexionBD();

	String consulta = "INSERT INTO usuarios (activo, admin, usuario, clave, nombre, apellidos, domicilio, poblacion, provincia, cp, telefono, tarjeta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	try (PreparedStatement statement = conexionBD.prepareStatement(consulta)) {
 
		statement.setInt(1, 1);
		statement.setInt(2, 0); 
		statement.setString(3, usuario);
		statement.setString(4, clave);
		statement.setString(5, nombre);
		statement.setString(6, apellidos);
		statement.setString(7, domicilio);
		statement.setString(8, poblacion);
		statement.setString(9, provincia);
		statement.setString(10, cp);
		statement.setString(11, telefono);
		statement.setString(12, "0");


		statement.executeUpdate();
	}
}

public Usuario obtenerDatosUsuario(int idUsuario) {
    abrirConexionBD();

    Usuario usuario = null;

    try {
        String consulta = "SELECT * FROM usuarios WHERE id = ?";
        PreparedStatement statement = conexionBD.prepareStatement(consulta);
        statement.setInt(1, idUsuario);
        ResultSet resultado = statement.executeQuery();

        if (resultado.next()) {
            usuario = new Usuario();
            usuario.setId(resultado.getInt("id"));
            usuario.setNombre(resultado.getString("nombre"));
            usuario.setApellidos(resultado.getString("apellidos"));
            usuario.setDomicilio(resultado.getString("domicilio"));
            usuario.setPoblacion(resultado.getString("poblacion"));
            usuario.setProvincia(resultado.getString("provincia"));
            usuario.setCp(resultado.getString("cp"));
            usuario.setTelefono(resultado.getString("telefono"));
			usuario.setTarjeta(resultado.getString("tarjeta"));

		}
    } catch (SQLException e) {
        System.err.println("Error al obtener los datos del usuario desde la base de datos");
        e.printStackTrace();
    }

    return usuario;
}

public Usuario obtenerUsuarioPorCodigo(String codigoUsuario) {
    abrirConexionBD();

    Usuario usuario = null;

    try {
        String consulta = "SELECT * FROM usuarios WHERE codigo = ?";
        PreparedStatement statement = conexionBD.prepareStatement(consulta);
        statement.setString(1, codigoUsuario);

        ResultSet resultado = statement.executeQuery();

        if (resultado.next()) {

			usuario = new Usuario();
			usuario.setUsuario(resultado.getString("usuario"));
			usuario.setClave(resultado.getString("clave"));
            usuario.setId(resultado.getInt("codigo"));
            usuario.setNombre(resultado.getString("nombre"));
			usuario.setApellidos(resultado.getString("apellidos"));
			usuario.setDomicilio(resultado.getString("domicilio"));
			usuario.setPoblacion(resultado.getString("poblacion"));
			usuario.setProvincia(resultado.getString("provincia"));
			usuario.setCp(resultado.getString("cp"));
			usuario.setTelefono(resultado.getString("telefono"));
			usuario.setTarjeta(resultado.getString("tarjeta"));
            
        }
    } catch (SQLException e) {
        System.err.println("Error al obtener el usuario por código: " + e.getMessage());
        e.printStackTrace();
    }

    return usuario;
}
public void actualizarUsuario(Usuario usuario) throws SQLException {
	abrirConexionBD();

	String consulta = "UPDATE usuarios SET domicilio = ?, cp = ?, poblacion = ?, provincia = ?, telefono = ?, tarjeta = ? WHERE codigo = ?";

	try (PreparedStatement statement = conexionBD.prepareStatement(consulta)) {
		statement.setString(1, usuario.getDomicilio());
		statement.setString(2, usuario.getCp());
		statement.setString(3, usuario.getPoblacion());
		statement.setString(4, usuario.getProvincia());
		statement.setString(5, usuario.getTelefono());
		statement.setString(6, usuario.getTarjeta());
		statement.setInt(7, usuario.getId());
		

		statement.executeUpdate();
	}
}

public int guardarPedido(List<Producto> productos, Usuario usuario, float totalPedido) throws SQLException {
    abrirConexionBD();

    try {

		String insertEstadoQuery = "INSERT INTO estados (descripcion) VALUES (?)";
		PreparedStatement insertEstadoStatement = conexionBD.prepareStatement(insertEstadoQuery);
		insertEstadoStatement.setString(1, "Pendiente");
		insertEstadoStatement.executeUpdate();
        String selectEstadoQuery = "SELECT MAX(codigo) AS max_codigo FROM estados WHERE descripcion = ?";
		PreparedStatement selectEstadoStatement = conexionBD.prepareStatement(selectEstadoQuery);
		selectEstadoStatement.setString(1, "Pendiente");
		ResultSet estadoResult = selectEstadoStatement.executeQuery();
		int estadoCodigo = 0;
		if (estadoResult.next()) {
			estadoCodigo = estadoResult.getInt("max_codigo");
		}

		String insertPedidoQuery = "INSERT INTO pedidos (persona, fecha, importe, estado, Direccion) VALUES (?, ?, ?, ?, ?)";
		PreparedStatement insertPedidoStatement = conexionBD.prepareStatement(insertPedidoQuery, Statement.RETURN_GENERATED_KEYS);
		insertPedidoStatement.setInt(1, usuario.getId());
		insertPedidoStatement.setDate(2, new java.sql.Date(System.currentTimeMillis()));
		insertPedidoStatement.setFloat(3, totalPedido);
		insertPedidoStatement.setInt(4, estadoCodigo);
		insertPedidoStatement.setString(5, usuario.getDomicilio());
		insertPedidoStatement.executeUpdate();


		ResultSet generatedKeys = insertPedidoStatement.getGeneratedKeys();
		int pedidoId = 0;
		if (generatedKeys.next()) {
			pedidoId = generatedKeys.getInt(1);
		}
		


		String insertDetalleQuery = "INSERT INTO detalle (codigo_pedido, codigo_producto, unidades, precio_unitario) VALUES (?, ?, ?, ?)";
		PreparedStatement insertDetalleStatement = conexionBD.prepareStatement(insertDetalleQuery);
		for (Producto producto : productos) {
			insertDetalleStatement.setInt(1, pedidoId);
			insertDetalleStatement.setInt(2, producto.getCodigo());
			insertDetalleStatement.setInt(3, producto.getCantidad());
			insertDetalleStatement.setFloat(4, producto.getPrecio());
			insertDetalleStatement.executeUpdate();
		}
conexionBD.commit();
	return pedidoId;

		


    } catch (SQLException e) {

		conexionBD.rollback();
        throw e;
    }

}
public int obtenerStockDisponible(int codigoProducto) throws SQLException {
    abrirConexionBD();
    int stockDisponible = 0;

    try {
        String consulta = "SELECT existencias FROM productos WHERE codigo = ?";
        PreparedStatement statement = conexionBD.prepareStatement(consulta);
        statement.setInt(1, codigoProducto);
        ResultSet resultado = statement.executeQuery();

        if (resultado.next()) {
            stockDisponible = resultado.getInt("existencias");
        }
    } finally {
        
    }

    return stockDisponible;
}
public List<Pedido> cargarPedidos(int usuarioId) {

    abrirConexionBD();
    List<Pedido> pedidos = new ArrayList<>();

    try {
        String con = "SELECT codigo, persona, fecha, importe, estado FROM pedidos WHERE persona=?";
        PreparedStatement statement = conexionBD.prepareStatement(con);
        statement.setInt(1, usuarioId);
        ResultSet resultado = statement.executeQuery();
        
        while (resultado.next()) {
            Pedido pedido = new Pedido();
            pedido.setCodigo(resultado.getInt("codigo"));
            pedido.setPersona(resultado.getString("persona"));
            pedido.setFecha(resultado.getDate("fecha"));
            pedido.setImporte(resultado.getFloat("importe"));
            int codigoEstado = resultado.getInt("estado");
            

			String conEstado = "SELECT descripcion FROM estados WHERE codigo=?";
            PreparedStatement statementEstado = conexionBD.prepareStatement(conEstado);
            statementEstado.setInt(1, codigoEstado);
            ResultSet resultadoEstado = statementEstado.executeQuery();
            
            if (resultadoEstado.next()) {
                String descripcionEstado = resultadoEstado.getString("descripcion");
                pedido.setEstadoP(descripcionEstado);
            }
            
            pedidos.add(pedido);
        }
    } catch (SQLException e) {
        System.err.println("Error ejecutando la consulta a la base de datos");
        System.err.println(e.getMessage());
    }

    return pedidos;
}

public boolean cancelarPedido(int codigoPedido) {
    abrirConexionBD();
    boolean cancelado = false;
    
    try {

		String consultaCodigoEstado = "SELECT estado FROM pedidos WHERE codigo = ?";
        PreparedStatement statementCodigoEstado = conexionBD.prepareStatement(consultaCodigoEstado);
        statementCodigoEstado.setInt(1, codigoPedido);
        ResultSet resultadoCodigoEstado = statementCodigoEstado.executeQuery();
        
        int codigoEstado = -1; 
        if (resultadoCodigoEstado.next()) {
            codigoEstado = resultadoCodigoEstado.getInt("estado");
        }
        

		String consultaDetalle = "DELETE FROM detalle WHERE codigo_pedido = ?";
        PreparedStatement statementDetalle = conexionBD.prepareStatement(consultaDetalle);
        statementDetalle.setInt(1, codigoPedido);
        int filasDetalleEliminadas = statementDetalle.executeUpdate();
        

		if (filasDetalleEliminadas > 0) {

			String consultaPedido = "DELETE FROM pedidos WHERE codigo = ?";
            PreparedStatement statementPedido = conexionBD.prepareStatement(consultaPedido);
            statementPedido.setInt(1, codigoPedido);
            int filasPedidoEliminadas = statementPedido.executeUpdate();
            

			String consultaEstado = "DELETE FROM estados WHERE codigo = ?";
            PreparedStatement statementEstado = conexionBD.prepareStatement(consultaEstado);
            statementEstado.setInt(1, codigoEstado);
            int filasEstadoEliminadas = statementEstado.executeUpdate();
            

			cancelado = (filasPedidoEliminadas > 0 && filasEstadoEliminadas > 0);
        }
    } catch (SQLException e) {
        System.err.println("Error al cancelar el pedido en la base de datos: " + e.getMessage());
    } 
    
    return cancelado;
}

public List<ProductoBD> obtenerProductosPorLiga(String liga) {
    
	List<ProductoBD> productos = new ArrayList<>();
    abrirConexionBD();

    try {
        String consulta = "SELECT * FROM productos WHERE liga = ?";
        PreparedStatement statement = conexionBD.prepareStatement(consulta);
        statement.setString(1, liga);

        ResultSet resultado = statement.executeQuery();

        while (resultado.next()) {
            ProductoBD producto = new ProductoBD();
            producto.setCodigo(resultado.getInt("codigo"));
			producto.setDescripcion(resultado.getString("descripcion"));
			producto.setPrecio(resultado.getFloat("precio"));
			producto.setStock(resultado.getInt("existencias"));
			producto.setImagen(resultado.getString("imagen"));
			
            productos.add(producto);
        }
    } catch (SQLException ex) {
        ex.printStackTrace();
    } 

    return productos;
}

public void actualizarStock(int codigoProducto, int cantidadVendida) throws SQLException {
    String query = "UPDATE productos SET existencias = existencias - ? WHERE codigo = ?";
    PreparedStatement statement = conexionBD.prepareStatement(query);
    statement.setInt(1, cantidadVendida);
    statement.setInt(2, codigoProducto);
    statement.executeUpdate();
}

public List<DetallePedido> obtenerDetallesPedido(int codigoPedido) {
    abrirConexionBD();
    List<DetallePedido> detallesPedido = new ArrayList<>();

    try {
        String query = "SELECT * FROM detalle WHERE codigo_pedido=?";
        PreparedStatement statement = conexionBD.prepareStatement(query);
        statement.setInt(1, codigoPedido);
        ResultSet resultado = statement.executeQuery();

        while (resultado.next()) {
            int codigoProducto = resultado.getInt("codigo_producto");
            int cantidad = resultado.getInt("unidades");

            ProductoBD productobd = obtenerProductoPorCodigo(codigoProducto);
			

            DetallePedido detallePedido = new DetallePedido(codigoPedido, productobd, cantidad);
            detallesPedido.add(detallePedido);
        }
    } catch (SQLException e) {
        System.err.println("Error al obtener detalles del pedido: " + e.getMessage());
    } 
    return detallesPedido;
}

public ProductoBD obtenerProductoPorCodigo(int codigoProducto) {
    abrirConexionBD();
    ProductoBD producto = null;

    try {
        String query = "SELECT * FROM productos WHERE codigo=?";
        PreparedStatement statement = conexionBD.prepareStatement(query);
        statement.setInt(1, codigoProducto);
        ResultSet resultado = statement.executeQuery();

        if (resultado.next()) {
            producto = new ProductoBD();
            producto.setCodigo(resultado.getInt("codigo"));
            producto.setDescripcion(resultado.getString("descripcion"));
            producto.setPrecio(resultado.getFloat("precio"));
            producto.setStock(resultado.getInt("existencias"));
            producto.setImagen(resultado.getString("imagen"));
        }
    } catch (SQLException e) {
        System.err.println("Error al obtener producto por código: " + e.getMessage());
    } 

    return producto;
}

public void devolverStockProducto(int codigoProducto, int cantidad) throws SQLException {
    abrirConexionBD();

    try {
        String selectQuery = "SELECT existencias FROM productos WHERE codigo = ?";
        PreparedStatement selectStatement = conexionBD.prepareStatement(selectQuery);
        selectStatement.setInt(1, codigoProducto);
        ResultSet resultSet = selectStatement.executeQuery();

        int stockActual = 0;
        if (resultSet.next()) {
            stockActual = resultSet.getInt("existencias");
        }

        int nuevoStock = stockActual + cantidad;
        String updateQuery = "UPDATE productos SET existencias = ? WHERE codigo = ?";
        PreparedStatement updateStatement = conexionBD.prepareStatement(updateQuery);
        updateStatement.setInt(1, nuevoStock);
        updateStatement.setInt(2, codigoProducto);
        updateStatement.executeUpdate();

      
        conexionBD.commit();
    } catch (SQLException e) {
      
        conexionBD.rollback();
        throw e;
    }
}









}