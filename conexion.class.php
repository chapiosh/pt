<?php

class DBManager {
    private $conect;
    private $BaseDatos;
    private $Servidor;
    private $Usuario;
    private $Clave;

    function __construct() {
        $this->BaseDatos = "";
        $this->Servidor = "";
        $this->Usuario = "";
        $this->Clave = "";
        $this->con = mysqli_connect($this->Servidor, $this->Usuario, $this->Clave, $this->BaseDatos);
    }

    function conectar() {
        if (!($con = mysqli_connect($this->Servidor, $this->Usuario, $this->Clave, $this->BaseDatos))) {
            echo"<h1> [:(] Error al conectar a la base de datos</h1>";
            exit();
        }
        if (!mysqli_select_db($con, $this->BaseDatos)) {
            echo "<h1> [:(] Error al seleccionar la base de datos</h1>";
            exit();
        }
        $this->conect = $con;
        mysqli_query($con, "SET SESSION time_zone = 'America/Monterrey'");
        mysqli_query($con,"SET NAMES 'utf8'");
        return true;
    }

}

function mayusculas($words) {
$words = ucfirst(strtolower($words));
    $words = preg_replace_callback('/[.!?].*?\w/', function($matches){
        return strtoupper($matches[0]);
    }, $words);

    return $words;
}
?>
