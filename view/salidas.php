<?php
    require_once 'datatable.php';
    require_once 'acceso-seguro.php';
?> 


<div class="row">
    <div class="col-md-4">
        <div class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title mt-1" style="font-size: 22px" id="Aviso">Registro de Salidas</p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" id="formularioSalidas">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="idcategoriasalida">Categoría</label>
                                <select class="form-control form-control-border" name="idcategoriasalida" id="idcategoriasalida">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="idproductosalida">Producto</label>
                                <select class="form-control form-control-border" id="idproductosalida">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="cantidadsalida">Cantidad:</label>
                                <input type="number" min="1" id="cantidadsalida" class="form-control form-control-border">
                            </div> 
                            <div class="col-md-12 mt-3">
                                <label for="detalle">Detalle</label>
                                <textarea class="form-control form-control-border" id="detalle"></textarea>
                            </div>      
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right bg-white">
                <button type="button" class="btn bg-gradient-secondary" id="cancelarSalidas">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="btnRegistrarSalidas">Guardar</button>
            </div>
            <!-- /.card-footer -->
        </div>
    </div>
    <div class="col-md-8">
        <div  class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title" style="font-size: 22px">Lista de Salidas</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table text-center" id="tablaSalidas">
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
                    <tbody class="table" id="tablaSalidaslistar">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/productos.js"></script>
