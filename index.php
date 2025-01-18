<?php

require_once('historial.class.php');
$objHistorial = new Historial;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Underhood Adm</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes"> <!-- Esconde la adress bar en iOS -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black">


    <!-- Iconos Apps -->
    <link href="/jqtouch.png" sizes="72x72" rel="apple-touch-icon">


    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0-rc.1/jquery.mobile.structure-1.3.0-rc.1.min.css"/>

    <link rel="stylesheet" href="jquery.mobile-1.3.0-rc.1.css"/>


    <link rel="stylesheet" type="text/css" href="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.min.css"/>
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="jquery.mobile-1.3.0.js"></script>
    <script src="jquery.autocomplete.js"></script>
    <script src="listavehiculos.js"></script>
    <script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.core.min.js"></script>

    <script type="text/javascript"
            src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.mode.calbox.min.js"></script>
    <script type="text/javascript"
            src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.mode.datebox.min.js"></script>
    <script type="text/javascript"
            src="http://dev.jtsage.com/cdn/datebox/i18n/jquery.mobile.datebox.i18n.es-ES.utf8.js"></script>
    <style>
        .ui-li-count {
            float: right;
            font-size: 11px;
            font-weight: bold;
            padding: .2em .5em;
            top: 50%;
            margin-top: 0em;
            right: 10px; }
    </style>
<body>
<div data-role="page" id="one">

    <div data-role="header" data-theme="b" data-position="fixed">
        <a href="javascript: location.reload()" data-role="button" data-icon="refresh" data-rel="refresh" data-theme="c"
           class="ui-btn-left">Actualizar</a>
        <h1>Horas Generadas</h1>
    </div>

    <div data-role="content">
        <ul data-role="listview" data-divider-theme="b" data-inset="false">
            <li data-role="list-divider" role="heading">Comisiones a Pagar (S-V)</li>
        </ul>
        <div data-role="collapsible-set" data-theme="c" data-content-theme="d" data-inset="false">
            <?php
            $consulta = $objHistorial->mostrarempleadosactivos();
            while ($Empleado = mysqli_fetch_array($consulta)) {
                $comisionmes = date("Y-m-d", strtotime("-" . date("d", mktime(0, 0, 0)) - 1 . " days")); //nos da el primer dia del mes
                $comisiondesde = date("Y-m-d", strtotime("-" . date("w", mktime(0, 0, 0)) - 1 . " days")); //nos da el primer dia de comision de la semana
                $consulta2 = $objHistorial->mostrarempleadoscomisiones($Empleado['ID'], $comisiondesde);
                $consulta4 = $objHistorial->mostrarempleadosgarantias($Empleado['ID'], $comisiondesde);
                $consulta5 = $objHistorial->mostrarempleadoscomisiones($Empleado['ID'], $comisionmes);
                $consulta6 = $objHistorial->mostrarempleadosgarantias($Empleado['ID'], $comisionmes);
                $Comision = mysqli_fetch_array($consulta2);
                $Garantia = mysqli_fetch_array($consulta4);
                $ComisionMes = mysqli_fetch_array($consulta5);
                $GarantiaMes = mysqli_fetch_array($consulta6);
                $consulta3 = $objHistorial->mostrarempleadostrabajos($Empleado['ID'], $comisiondesde);
                ?>

                <div class="ui-collapsible ui-collapsible-collapsed" data-role="collapsible">
                    <h3>
                        <?php echo $Empleado['Nombre']; ?>
                        <?php
                            $TotalC = is_null($Comision['Total']) ? "0" : $Comision['Total'];
                            $TotalG = is_null($Garantia['Total']) ? "0" : $Garantia['Total'];
                            $TotalCMes = is_null($ComisionMes['Total']) ? "0" : $ComisionMes['Total'];
                            $TotalGMes = is_null($GarantiaMes['Total']) ? "0" : $GarantiaMes['Total'];
                            ?>
                        <span
                                class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo ($TotalC - $TotalG) ?> hrs | <?php echo ($TotalCMes - $TotalGMes) ?> hrs</span>
                    </h3>
                    <ul data-role="listview" data-inset="false">
                        <?php while ($Servicio = mysqli_fetch_array($consulta3)) { ?>
                            <li data-theme="d">
                                <h3 class="ui-li-heading"
                                    style="text-overflow: ellipsis; overflow: visible; white-space: normal;"><?php echo $Servicio['Folio'] ?></h3>
                                <p class="ui-li-desc"
                                   style="text-overflow: ellipsis; overflow: visible; white-space: normal;">
                                    <strong><?php echo $Servicio['Fecha'] ?></strong></p>
                                <p class="ui-li-desc"
                                   style="text-overflow: ellipsis; overflow: visible; white-space: normal;">
                                    <strong><?php echo $Servicio['Auto'] ?></strong></p>
                                <p class="ui-li-desc"
                                   style="text-overflow: ellipsis; overflow: visible; white-space: normal;"><?php echo $Servicio['Descripcion'] ?>
                                    .</p>
                                <span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo number_format($Servicio['Abono'] / .08, 2); ?> hrs</span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <?php
            }
            ?>
        </div>
    </div>
</div>
