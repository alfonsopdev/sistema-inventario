<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] == 'Médico') {
  echo "<strong>No tiene el nivel de acceso requerido</strong>";
  exit();
}
?>


<!-- MODAL FULLSCREEN -->
<div id="modalFullscreen" class="modal-img-full">
  <span class="close-modal">&times;</span>
  <img id="modalImg">
</div>

<section class="content-header">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h3 class="main-text mb-1">
        <b>Detalle del Movimiento: </b>
        <span id="cod_movimiento" class="text-danger"></span>
      </h3>
    </div>

    <div class="col-md-4 text-right">
      <a href="main.php?view=movimiento/index.php" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Regresar
      </a>
    </div>
  </div>
</section>

<div class="row mb-1 fixed-cards-row">
  <div class="col-md-2">
    <div class="card card-body py-2 fixed-card">
      <small class="text-muted">Fecha del movimiento</small>
      <div class="d-flex align-items-center">
        <i class="far fa-calendar-alt text-info mr-2"></i>
        <span id="fecha" class="font-weight-bold"></span>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card card-body py-2 fixed-card">
      <small class="text-muted">Responsable anterior</small>
      <div class="d-flex align-items-center">
        <i class="fas fa-user-clock text-secondary mr-2"></i>
        <span id="responsable_origen" class="font-weight-bold"></span>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card card-body py-2 fixed-card">
      <small class="text-muted">
        Responsable actual
        <span id="tipo_responsable" class="badge badge-light ml-1"></span>
      </small>
      <div class="d-flex align-items-center">
        <i class="fas fa-user-check text-primary mr-2"></i>
        <span id="responsable_destino" class="font-weight-bold"></span>
      </div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card card-body text-center py-2 fixed-card">
      <small class="text-muted">Tipo</small>
      <span id="tipo_mov" class="badge badge-info"></span>
    </div>
  </div>
  
  <div class="col-md-2">
    <div class="card card-body text-center py-2 fixed-card">
      <small class="text-muted">Estado</small>
      <span id="mov_estado" class="badge badge-success"></span>
    </div>
  </div>
</div>


<div class="card mb-3">
  <div class="card-header">
    <h5 class="card-title mb-0 text-navy">
      <i class="fas fa-info-circle text-navy"></i> Información del Movimiento
    </h5>
  </div>

  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <p><b>Motivo:</b>&nbsp; <span id="motivo"></span></p>
        <p><b>Documento / Informe:</b>&nbsp; <a target="_blank" id="informe_doc"></a></p>
        <p><b>Tiempo:</b>&nbsp; <span id="tiempo_mov"></span></p>
      </div>

      <div class="col-md-6">
        <p><b>Ubicación Origen:</b>&nbsp; <span id="ubicacion_origen"></span></p>
        <p><b>Ubicación Destino:</b>&nbsp; <span id="ubicacion_destino"></span></p>
        <p><b>Fecha devolución:</b>&nbsp; <span id="fecha_devolucion"></span></p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title text-navy">Activos Asociados</h5>
      </div>
      <div class="card-body table-responsive">
        <table id="tablaActivosMovimiento" class="table table-bordered table-hover">
          <thead class="thead-dark">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Código</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Descripción</th>
              <th class="text-center">Imagen</th>
              <th class="text-center">Estado</th>
              <th class="text-center">Seguimiento</th>
            </tr>
          </thead>
          <tbody class="table" id="datosActivosMovimiento">
            <!-- contenido de la tabla -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script src="js/detalle_movimiento.js"></script>

<!-- SCRIPT FULLSCREEN -->
<!-- <script>
  const img = document.getElementById("foto_activo");
  const modal = document.getElementById("modalFullscreen");
  const modalImg = document.getElementById("modalImg");
  const closeModal = document.querySelector(".close-modal");

  img.onclick = function() {
    modal.style.display = "flex";
    modalImg.src = this.src;
  }

  closeModal.onclick = function() {
    modal.style.display = "none";
  }

  modal.onclick = function(e) {
    if (e.target === modal) modal.style.display = "none";
  }
</script> -->