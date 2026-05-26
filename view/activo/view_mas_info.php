<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] == 'Médico') {
  echo "<strong>No tiene el nivel de acceso requerido</strong>";
  exit();
}
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="main-text"><b>
            <i id="icono"></i>
            Información técnica del activo:&nbsp;</b>
          <span class="text-danger">
            <span id="badgeCodigo"></span>
          </span>
        </h3>
      </div>
      <div class="col-sm-6 text-right">
        <a id="btnRegresar" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </a>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">

    <!-- RESUMEN -->
    <div id="resumenActivo" class="row"></div>

    <!-- HARDWARE TABS -->
    <h4 class="main-text">Hardware</h4>
    <div class="card card-primary card-tabs">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#tab_bios">Bios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_procesador">Procesador</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_memorias">Memoria(s) RAM</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_almacenamiento">Almacenamiento</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_discos">Disco(s)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_videos">Tarjeta de Video</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_sonidos">Sonido</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_controladores">Controlador(es)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_ranuras">Ranura(s)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_puertos">Puerto(s)</a>
          </li>
        </ul>
      </div>

      <div class="card-body table-responsive">
        <div class="tab-content">

          <div class="tab-pane fade show active" id="tab_bios"></div>
          <div class="tab-pane fade" id="tab_procesador"></div>
          <div class="tab-pane fade" id="tab_memorias"></div>
          <div class="tab-pane fade" id="tab_almacenamiento"></div>
          <div class="tab-pane fade" id="tab_discos"></div>
          <div class="tab-pane fade" id="tab_videos"></div>
          <div class="tab-pane fade" id="tab_sonidos"></div>
          <div class="tab-pane fade" id="tab_controladores"></div>
          <div class="tab-pane fade" id="tab_ranuras"></div>
          <div class="tab-pane fade" id="tab_puertos"></div>
          <!-- <div class="tab-pane fade" id="tab_red"></div> -->
          <!-- <div class="tab-pane fade" id="tab_perifericos"></div> -->

        </div>
      </div>
    </div>

    <!-- RED -->
    <h4 class="main-text">Red</h4>
    <div class="card p-3 table-responsive">
      <table class="table table-bordered" id="tabla_redes">
        <thead style="background-color: #e4f5e3;">
          <th>Descripción</th>
          <th>Tipo</th>
          <th>Velocidad</th>
          <th>MTU</th>
          <th>Direccion MAC</th>
          <th>Estado</th>
          <th>Dirección IP</th>
          <th>Máscara de Red</th>
          <th>Gateway</th>
          <th>Nro Red</th>
          <th>IP DHCP</th>
        </thead>

        <tbody class="table" id="datosRedes">
            <!-- contenido de la tabla -->
          </tbody>
      </table>

    </div>

    <!-- DISPOSITIVOS TABS -->
    <h4 class="main-text">Dispositivos</h4>
    <div class="card card-primary card-tabs">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#tab_pda">PDA/Smartphone</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_monitores">Monitor(es)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_entradas">Dispositivos de Entrada</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_memorias">Dispositivos USB</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_impresoras">Impresora(s)</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#tab_memorias">Módem(s)</a>
          </li> -->
        </ul>
      </div>

      <div class="card-body table-responsive">
        <div class="tab-content">

          <div class="tab-pane fade show active" id="tab_pda"></div>
          <div class="tab-pane fade" id="tab_monitores"></div>
          <div class="tab-pane fade" id="tab_entradas"></div>
          <div class="tab-pane fade" id="tab_impresoras"></div>

        </div>
      </div>
    </div>

  </div>
</section>

<script>
  // 🔹 Obtener código patrimonial desde la URL REAL
  function getCodigoFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get("codigo");
  }

  const CODIGO = getCodigoFromURL();

  $(document).ready(function() {
    if (!CODIGO) {
      $("#resumenActivo").html(`
      <div class="alert alert-danger m-3">
        Código patrimonial no recibido
      </div>
    `);
      return;
    }

    // Mostrar código en el header
    $("#badgeCodigo").text(CODIGO);

    // Cargar datos desde OCS
    cargarActivoOCS(CODIGO);
  });
</script>

<script>
  function cargarActivoOCS(codigo) {

  $('#resumenActivo').html(`
    <div class="col-12 text-center p-5">
      <i class="fas fa-spinner fa-spin fa-2x"></i><br>
      Cargando información desde OCS Inventory...
    </div>
  `);

    $.getJSON('api/ocs/activo_proxy.php', {
        codigo: codigo
      })
      .done(function(d) {
        renderResumen(d);
        renderBios(d);
        renderProcesador(d);
        renderMemorias(d);
        renderAlmacenamiento(d);
        renderDiscos(d);
        renderVideos(d);
        renderSonidos(d);
        renderControladores(d);
        renderRanuras(d);
        renderPuertos(d);
        renderRed(d);
        renderPDA(d);
        renderMonitores(d);
        renderEntradas(d);
        renderImpresoras(d);
      })
      .fail(function() {
        $('#resumenActivo').html(`
        <div class="col-12">
          <div class="alert alert-danger">
            No se pudo obtener información desde OCS
          </div>
        </div>
      `);
      });
  }
</script>

<script>
  $(document).ready(function() {
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
<script src="js/mas_info.js"></script>