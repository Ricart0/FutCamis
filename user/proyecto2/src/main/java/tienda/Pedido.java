package tienda;

import java.sql.Date;




public class Pedido {
    private int codigo;
    private String persona;
    private Date fecha;
    private float importe;
    private int estado;
    private String EstadoP;

   
    // Getters y setters
    public int getCodigo() {
        return codigo;
    }

    public void setCodigo(int codigo) {
        this.codigo = codigo;
    }

    public String getPersona() {
        return persona;
    }

    public void setPersona(String persona) {
        this.persona = persona;
    }
    public String getEstadoP() {
        return EstadoP;
    }

    public void setEstadoP(String EstadoP) {
        this.EstadoP = EstadoP;
    }

    public Date getFecha() {
        return fecha;
    }

    public void setFecha(Date fecha) {
        this.fecha = fecha;
    }

    public float getImporte() {
        return importe;
    }

    public void setImporte(float importe) {
        this.importe = importe;
    }

    public int getEstado() {
        return estado;
    }

    public void setEstado(int estado) {
        this.estado = estado;
    }
}
