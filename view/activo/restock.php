<?php
    require_once '../datatable.php';
    require_once '../acceso-seguro.php';
?>
<div class="row">
    <div class="col-md-12">
        <div  class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title" style="font-size: 22px">Reporte de Restock</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table text-center" id="tablaRestock">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Categoría</th>
                            <th class="text-center">Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Detalle</th>
                            <th class="text-center">Fecha y hora</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="tablaRestocklistar">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="js/productos.js"></script>
