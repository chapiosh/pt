<?php

include_once("conexion.class.php");
date_default_timezone_set('America/Monterrey');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class Historial {

    //constructor
    var $con;

    function __construct() {
        $this->con = new DBManager;
    }

//Modulo Paquete General ---------------------------------------------------------------->

    function mostrar($Matricula) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Cuentas_Cobrar where Matricula ='" . $Matricula . "'");
        }
    }

    function mostrarempleado($folio) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select Nombre, Descripcion From View_Abonos Where Folio='" . $folio . "'");
        }
    }

    function mostrarempleadosactivos() { //Muestra todos los empleados que generan comision
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"SELECT * FROM Empleados WHERE Baja='0000-00-00' and Hora <> '0' Order By Nombre");
        }
    }

    function mostrarempleadoscomisiones($id, $fecha) { //De la fecha insertada en adelante muestra comisiones
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select Sum(Tiempo) as Total From View_Abonos WHERE Empleado='" . $id . "' and Fecha>='" . $fecha . "' and Tipo = 'Realizado'");
        }
    }

    function mostrarempleadosgarantias($id, $fecha) { //De la fecha insertada en adelante muestra comisiones
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select Sum(Tiempo) as Total From View_Abonos WHERE Empleado='" . $id . "' and Fecha>='" . $fecha . "' and Tipo = 'Garantia'");
        }
    }

    function mostrarempleadostrabajos($id, $fecha) { //De la fecha insertada en adelante muestra comisiones
        if ($this->con->conectar() == true) {

            return mysqli_query($this->con->con,"Select * From View_Abonos WHERE Empleado='" . $id . "' and Fecha>='" . $fecha . "' Order by Fecha");
        }
    }

    function cliente($Matricula) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Clientes_Vehi left Join Clientes on Clientes_Vehi.Cliente = Clientes.ID where Matricula ='" . $Matricula . "'");
        }
    }

    function clienteid($id) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Clientes where ID ='" . $id . "'");
        }
    }

    function clienteagregado() {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Clientes Order By ID DESC Limit 1");
        }
    }

    function clientesvehiculos($id) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Clientes_Vehi Left Join Vehiculos_Master on Clientes_Vehi.Vehiculo = Vehiculos_Master.ID where Clientes_Vehi.Cliente ='" . $id . "'");
        }
    }

    function vehiculo($Matricula) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Clientes_Vehi Left Join Vehiculos_Master on Clientes_Vehi.Vehiculo = Vehiculos_Master.ID where Matricula ='" . $Matricula . "'");
        }
    }

    function vehiculoservicios($Matricula) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select count(*) as Servicios From Cuentas_Cobrar where Matricula ='" . $Matricula . "'");
        }
    }

    function entaller($id) {
        if ($this->con->conectar() == true) {
            if ($id == null) {
                return mysqli_query($this->con->con,"Select Empleados.Nombre, Taller.Tecnico, Taller.id, Taller.Matricula, Ano, Marca, Modelo, Motor, Color, Servicio, HoraE, HoraP, Taller.Estado From Taller left Join Clientes_Vehi on Taller.Matricula = Clientes_Vehi.Matricula and Taller.Cliente = Clientes_Vehi.Cliente Left Join Vehiculos_Master on Clientes_Vehi.Vehiculo = Vehiculos_Master.ID left Join Empleados on Taller.Tecnico = Empleados.ID Order by Taller.id");
            } else {
                return mysqli_query($this->con->con,"Select Empleados.Nombre, Taller.Tecnico, Taller.id,Taller.Matricula, Ano, Marca, Modelo, Motor, Color, Servicio, HoraE, HoraP, Taller.Estado From Taller left Join Clientes_Vehi on Taller.Matricula = Clientes_Vehi.Matricula and Taller.Cliente = Clientes_Vehi.Cliente Left Join Vehiculos_Master on Clientes_Vehi.Vehiculo = Vehiculos_Master.ID left Join Empleados on Taller.Tecnico = Empleados.ID Where Taller.id = '" . $id . "'");
            }
        }
    }

    function entallercontar() {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select count(*) as Vehiculos From Taller where Estado != 'Entregado'");
        }
    }

    function entallerestado($estado, $id) {
        switch ($estado) {
            case 1:
                $estado = "En Fila";
                break;
            case 2:
                $estado = "En Diagnostico";
                break;
            case 3:
                $estado = "Cotizando";
                break;
            case 4:
                $estado = "En Proceso";
                break;
            case 5:
                $estado = "Listo";
                break;
            case 6:
                break;
        }
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Update Taller Set Estado ='" . $estado . "' where id ='" . $id . "'");
        }
    }

    function entallertecnico($tecnico, $id) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Update Taller Set Tecnico ='" . $tecnico . "' where id ='" . $id . "'");
        }
    }

    function ventas($fecha) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select Sum(Total) as Total, Count(Total) as Servicios  From Cuentas_Cobrar Where Fecha = '" . $fecha . "' Group by Fecha");
        }
    }

    function ventas_detalles($fecha) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select * From Cuentas_Cobrar Where Fecha = '" . $fecha . "'");
        }
    }

    function compras($fecha) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"Select Sum(Total) as Total From Cuentas_Pagar Where Fecha = '" . $fecha . "' Group by Fecha");
        }
    }

    function insertar($Cliente, $Matricula, $Folio, $HoraE, $FechaE, $HoraP, $FechaP, $Servicio) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"INSERT INTO Taller (
                        Cliente,
			Matricula,
                        Folio,
                        HoraE,
			HoraP,
                        Servicio,
                        Estado)
			VALUES (
                        '" . $Cliente . "',
			'" . $Matricula . "',
                        '" . $Folio . "',
                        '" . $FechaE . " " . $HoraE . "',
                        '" . $FechaP . " " . $HoraP . "',
                        '" . $Servicio . "',
                        'En Fila')");
        }
    }

    function mostrar_anos() {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"SELECT Ano FROM Vehiculos_Master group by Ano DESC");
        }
    }

    function mostrar_marcas($ano) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"SELECT Marca FROM Vehiculos_Master where Ano='" . $ano . "' group by Marca");
        }
    }

    function mostrar_modelos($marca, $ano) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"SELECT Modelo FROM Vehiculos_Master where Ano='" . $ano . "' and Marca='" . $marca . "' group by Modelo");
        }
    }

    function mostrar_motores($modelo, $marca, $ano) {
        if ($this->con->conectar() == true) {
            return mysqli_query($this->con->con,"SELECT Motor, ID FROM Vehiculos_Master where Ano='" . $ano . "' and Modelo='" . $modelo . "' and Marca='" . $marca . "' group by Motor");
        }
    }

}

?>

