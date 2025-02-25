package tienda;

public class DetallePedido {
    private int codigoPedido;
    private ProductoBD producto;
    private int cantidad;

    public DetallePedido(int codigoPedido, ProductoBD producto, int cantidad) {
        this.codigoPedido = codigoPedido;
        this.producto = producto;
        this.cantidad = cantidad;
    }

    // Getters y setters
    public int getCodigoPedido() {
        return codigoPedido;
    }

    public void setCodigoPedido(int codigoPedido) {
        this.codigoPedido = codigoPedido;
    }

    public ProductoBD getProducto() {
        return producto;
    }

    public void setProducto(ProductoBD producto) {
        this.producto = producto;
    }

    public int getCantidad() {
        return cantidad;
    }

    public void setCantidad(int cantidad) {
        this.cantidad = cantidad;
    }
}

