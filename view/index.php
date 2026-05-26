<?php
require_once 'datatable.php';
require_once 'acceso-seguro.php';
if ($_SESSION['nivelacceso'] != 'ADMINISTRADOR') {
    echo "<strong>No tiene el nivel de acceso requerido</strong>";
    exit();
}
?>

<style>
    .asignar {
        display: none !important;
    }
</style>


<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-3">
                <div class="card card-hover">
                    <div class="card-body d-flex align-items-center p-2">

                        <div class="mr-3">
                            <div class="bg-info icon-box p-3 bordered-3 elevation-1"><i class="fas fa-boxes fa-2x"></i></div>
                        </div>
                        <div>
                            <small class="secondary-text">Total de Activos</small>
                            <h4 class="mb-0" id="totalActivos">0</h4>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div class="card card-hover">
                    <div class="card-body d-flex align-items-center p-2">
                        <div class="mr-3">
                            <div class="bg-success icon-box p-3 bordered-3 elevation-1"><i class="fas fa-check-circle fa-2x"></i></div>
                        </div>
                        <div>
                            <small class="secondary-text">Operativos</small>
                            <h4 class="mb-0" id="activosOperativos">0</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-hover">
                    <div class="card-body d-flex align-items-center p-2">
                        <div class="mr-3">
                            <div class="bg-navy icon-box p-3 bordered-3 elevation-1"><i class="fas fa-handshake fa-2x"></i></div>
                        </div>
                        <div>
                            <small class="secondary-text">En Préstamo</small>
                            <h4 class="mb-0" id="prestamosActivos">0</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-hover">
                    <div class="card-body d-flex align-items-center p-2">
                        <div class="mr-3">
                            <div class="bg-warning icon-box p-3 bordered-3 elevation-1"><i class="fas fa-exchange-alt fa-2x"></i></div>
                        </div>
                        <div>
                            <small class="secondary-text">En Transferencia</small>
                            <h4 class="mb-0" id="prestamosTransferidos">0</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Estados de Activos</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                        <canvas id="chartEstados"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Movimientos por Mes</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartMovimientos"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Últimos Movimientos</h3>
                    </div>
                    <div class="card-body">
                        <table id="tablaDashboard" class="table table-bordered table-hover"></table>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/. container-fluid -->
</section>

<script src="plugins/chart.js/Chart.min.js"></script>
<script src="js/dashboard.js"></script>