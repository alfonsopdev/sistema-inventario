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

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="row">
    <div class="col-sm-6">
      <h3 style="font-size: 22px"><b>Detalle del Activo</b></h3>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Activo</li> -->
        <a id="btnRegresar" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </a>
      </ol>
    </div>
  </div>
</section>

<div class="row">
  <div class="col-md-12">

    <div class="card">
      <div class="card-body">

        <div class="row">

          <!-- FOTO DEL ACTIVO -->
          <div class="col-md-4">
            <div class="foto-card">
              <img id="foto_activo" class="card-img-top" src="img/default.png" alt="Foto del activo">
              <!-- Ícono ampliación -->
              <div class="zoom-icon">
                <i class="fas fa-search-plus"></i>
              </div>
            </div>
          </div>

          <!-- DETALLES DEL ACTIVO -->
          <div class="col-md-8">

            <h3 id="nombre_activo" class="text-primary"></h3>
            <p id="categoria_activo" class="text-muted mb-3"></p>

            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th style="width: 200px;">Código Patrimonial:</th>
                  <td id="codigo_activo"></td>
                </tr>
                <tr>
                  <th style="width: 200px;">Nro Serie:</th>
                  <td id="numeroserie"></td>
                </tr>
                <tr>
                  <th>Estado:</th>
                  <td id="estado_activo"></td>
                </tr>
                <tr>
                  <th>Ubicación / Sede:</th>
                  <td id="sede_activo"></td>
                </tr>
                <tr>
                  <th>Área / Dependencia:</th>
                  <td id="dependencia_activo"></td>
                </tr>
                <tr>
                  <th>Responsable:</th>
                  <td id="responsable_activo"></td>
                </tr>
                <tr>
                  <th>Fecha de Adquisición:</th>
                  <td id="fecha_adquisicion_activo"></td>
                </tr>
                <tr>
                  <th>Fecha de Registro:</th>
                  <td id="fecha_activo"></td>
                </tr>
                <tr>
                  <th>Orden de Compra:</th>
                  <td id="orden_compra_activo"></td>
                </tr>
                <tr>
                  <th>Garantía:</th>
                  <td id="garantia_activo"></td>
                </tr>

              </tbody>
            </table>

            <h5 class="mt-4">Descripción:</h5>
            <p id="descripcion_activo"></p>

          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

<!-- TABS: GARANTÍAS, MANTENIMIENTOS Y MOVIMIENTOS -->
<div class="row mt-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header p-0">
        <ul class="nav nav-tabs" id="detalleTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="tab-movimientos" data-toggle="tab" href="#content-movimientos" role="tab">
              <i class="fa fa-truck"></i> Movimientos
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-garantias" data-toggle="tab" href="#content-garantias" role="tab">
              <i class="fa fa-shield-alt"></i> Garantías
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-mantenimientos" data-toggle="tab" href="#content-mantenimientos" role="tab">
              <i class="fa fa-tools"></i> Mantenimientos
            </a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="content-movimientos" role="tabpanel">
            <div id="timeline_movimientos" class="timeline"></div>
          </div>
          <div class="tab-pane fade" id="content-garantias" role="tabpanel">
            <div id="contenido_garantias">Cargando...</div>
          </div>
          <div class="tab-pane fade" id="content-mantenimientos" role="tabpanel">
            <div id="contenido_mantenimientos">Cargando...</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="js/detalle_activo.js"></script>
<script src="js/garantias.js"></script>
<script src="js/mantenimientos.js"></script>

<script>
$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const idActivo = urlParams.get('id');

    $('#tab-garantias').on('shown.bs.tab', function () {
        $('#contenido_garantias').load('view/activo/view_garantias.php', function () {
            iniciarGarantias(idActivo);
        });
    });

    $('#tab-mantenimientos').on('shown.bs.tab', function () {
        $('#contenido_mantenimientos').load('view/activo/view_mantenimientos.php', function () {
            iniciarMantenimientos(idActivo);
        });
    });
});
</script>

<!-- SCRIPT FULLSCREEN -->
<script>
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

  $(document).ready(function () {
      const params = new URLSearchParams(window.location.search);
      const returnUrl = params.get('return');

      if (returnUrl) {
          $('#btnRegresar').attr('href', decodeURIComponent(returnUrl));
      } else {
          // fallback por si entran directo
          $('#btnRegresar').attr('href', 'main.php?view=activo/index.php');
      }
  });

</script>