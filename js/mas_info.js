var icono = document.querySelector("#icono");

/* =======================
   RESUMEN SUPERIOR
======================= */
function renderResumen(d) {

  if (d.bios.tipo_dispositivo === "Mobile") {
    icono.classList.remove("fa","fa-desktop");
    icono.classList.add("fas","fa-mobile-alt");
  } else {
    icono.classList.remove("fa","fa-mobile-alt");
    icono.classList.add("fas","fa-desktop");
  }

  $("#resumenActivo").html(`
    <div class="col-md-3">
      <div class="info-box bg-info d-block align-items-center" style='cursor:default;'>
        <small class="info-box-header" style="opacity: 0.8;"><b>Nombre del equipo:</b> </small>
        <span class="info-box-text text-center"><b> ${d.nombre_equipo}</b></span>
      </div>
    </div>

    <div class="col-md-3">
      <div class="info-box bg-success d-block align-items-center justify-content-center" style='cursor:default;'>
        <small class="info-box-header" style="opacity: 0.8;"><b>Usuario: </b></small>
        <span class="info-box-text text-center"><b>${d.user_equipo || "N/D"}</b></span>
      </div>
    </div>

    <div class="col-md-3">
      <div class="info-box bg-warning d-block align-items-center justify-content-center" style='cursor:default;'>
        <small class="info-box-header" style="opacity: 0.8;"><b>Sistema Operativo: </b></small>
        <span class="info-box-text text-center"><b>${d.os.nombre}</b></span>
      </div>
    </div>

    <div class="col-md-3">
      <div class="info-box bg-danger d-block align-items-center justify-content-center" style='cursor:default;'>
        <small class="info-box-header" style="opacity: 0.8;"><b>Último Inventario: </b></small>
        <span class="info-box-text text-center"><b>${d.ultimo_inventario}</b></span>
      </div>
    </div>
  `);
}

/* =======================
   TAB GENERAL
======================= */
function renderBios(d) {
  $("#tab_bios").html(`
    <table class="table table-bordered">
      <tr><th>Número de Serie</th><td>${d.bios.sn_dispositivo}</td></tr>
      <tr><th>Fabricante</th><td>${d.bios.fabricante_dispositivo}</td></tr>
      <tr><th>Modelo</th><td>${d.bios.modelo_dispositivo}</td></tr>
      <tr><th>Tipo</th><td>${d.bios.tipo_dispositivo}</td></tr>
      <tr><th>Fabricante Bios</th><td>${d.bios.fabricante_bios}</td></tr>
      <tr><th>Versión Bios</th><td>${d.bios.version_bios}</td></tr>
      <tr><th>Fecha Bios</th><td>${d.bios.fecha_lanzamiento_bios}</td></tr>
    </table>
  `);
}

/* =======================
   PROCESADOR
======================= */
function renderProcesador(d) {

  if (d.bios.tipo_dispositivo == "Mobile") {
    $("#tab_procesador").html(`
      <table class="table table-striped">
        <tr><th>Tipo</th><td>${d.procesador_equipo}</td></tr>
        <tr><th>Velocidad (MHz)</th><td>${d.velocidad_pro_equipo}</td></tr>
      </table>
    `);
    return;
  }

  $("#tab_procesador").html(`
    <table class="table table-striped">
      <tr><th>Fabricante</th><td>${d.cpu.fabricante_cpu}</td></tr>
      <tr><th>Tipo</th><td>${d.cpu.descripcion_cpu}</td></tr>
      <tr><th>Número de Serie</th><td>${d.cpu.sn_cpu}</td></tr>
      <tr><th>Frecuencia</th><td>${d.cpu.velocidad_cpu} MHz</td></tr>
      <tr><th>Número de Cores</th><td>${d.cpu.nucleos_cpu} Núcleos</td></tr>
      <tr><th>Tamaño cache L2</th><td>${d.cpu.cacheL2_cpu} MB</td></tr>
      <tr><th>Arquitectura</th><td>${d.cpu.arquitectura_cpu} bits</td></tr>
      <tr><th>Ancho de datos</th><td>${d.cpu.ancho_banda_cpu} bits</td></tr>
      <tr><th>CPUS Lógicas</th><td>${d.cpu.hilos_cpu} Hilos</td></tr>
      <tr><th>Voltaje</th><td>${d.cpu.voltaje_cpu}</td></tr>
      <tr><th>Tipo Socket</th><td>${d.cpu.socket_cpu}</td></tr>
    </table>
  `);
}

/* =======================
   MEMORIA
======================= */
// function renderMemorias(d) {

//   if (!d.discos || d.discos.length === 0) {
//     $("#tab_disco").html(`<div class="alert alert-info">Sin discos registrados</div>`);
//     return;
//   }

//   let html = `<table class="table table-bordered">
//     <tr>
//       <th>Dispositivo</th>
//       <th>Total (MB)</th>
//       <th>Libre (MB)</th>
//     </tr>`;

//   d.discos.forEach(disco => {
//     html += `
//       <tr>
//         <td>${disco.tipo_unidad}</td>
//         <td>${disco.espacio_total_disco}</td>
//         <td>${disco.espacio_libre_disco}</td>
//       </tr>
//     `;
//   });

//   html += `</table>`;
//   $("#tab_disco").html(html);
// }

function renderMemorias(d) {
  // if (!d.memorias || d.memorias.length === 0) {
  //   $("#tab_memorias").html(
  //     `<div class="alert alert-info">Sin memorias RAM registrados</div>`,
  //   );
  //   return;
  // }

  if (d.bios.tipo_dispositivo == "Mobile") {
    $("#tab_memorias").html(`
      <table class="table table-striped">
        <tr><th>Memoria Virtual (MB)</th><td>${d.memoriaswap_equipo}</td></tr>
        <tr><th>Memoria (MB)</th><td>${d.ram_equipo}</td></tr>
      </table>
    `);
    return;
  }

  let html = `<table class="table table-bordered">
    <tr>
      <th>Identificador</th>
      <th>Descripción</th>
      <th>Capacidad (MB)</th>
      <th>Propósito</th>
      <th>Velocidad</th>
      <th>Nro Ranura</th>
      <th>Número de serie</th>
    </tr>`;

  d.memorias.forEach((memoria) => {
    html += `
      <tr>
        <td>${memoria.identificador_ram}</td>
        <td>${memoria.descripcion_ram}</td>
        <td>${memoria.capacidad_ram} MB</td>
        <td>${memoria.proposito_ram}</td>
        <td>${memoria.velocidad_ram} MHz</td>
        <td>${memoria.nranura_ram}</td>
        <td>${memoria.sn_ram}</td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_memorias").html(html);
}

/* =======================
   ALMACENAMIENTO
======================= */
function renderAlmacenamiento(d) {
  if (!d.almacenamientos || d.almacenamientos.length === 0) {
    $("#tab_almacenamiento").html(
      `<div class="alert alert-info">Sin almacenamientos registrados</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered">
    <thead style="background-color: #f5f0e3;">
      <tr>
        <th>Nombre</th>
        <th>Fabricante</th>
        <th>Modelo</th>
        <th>Descripción</th>
        <th>Tipo</th>
        <th>Tamaño del disco</th>
        <th>Número de serie</th>
        <th>firmware</th>
      </tr>
    </thead>`;

  d.almacenamientos.forEach((almacenamiento) => {
    html += `
      <tr>
        <td>${almacenamiento.fabricante_almacenamiento}</td>
        <td>${almacenamiento.nombre_almacenamiento}</td>
        <td>${almacenamiento.model_almacenamiento}</td>
        <td>${almacenamiento.descripcion_almacenamiento}</td>
        <td>${almacenamiento.tipo_almacenamiento}</td>
        <td>${almacenamiento.tamano_almacenamiento} MB</td>
        <td>${almacenamiento.sn_almacenamiento}</td>
        <td>${almacenamiento.firmware_almacenamiento}</td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_almacenamiento").html(html);
}

/* =======================
   DISCOS
======================= */
function barraCapacidad(total, libre) {
  total = parseFloat(total);
  libre = parseFloat(libre);

  if (!total || total <= 0) return '';

  const usado = total - libre;
  const porcentaje = Math.round((usado / total) * 100);

  let color = 'bg-success';
  if (porcentaje >= 70) color = 'bg-warning';
  if (porcentaje >= 90) color = 'bg-danger';

  return `
    <div class="progress" style="height: 18px; border-radius:10px;">
      <div class="progress-bar ${color}"
           role="progressbar"
           style="width: ${porcentaje}%; border-radius:10px;"
           aria-valuenow="${porcentaje}"
           aria-valuemin="0"
           aria-valuemax="100">
        ${porcentaje}%
      </div>
    </div>
  `;
}

function renderDiscos(d) {
  if (!d.discos || d.discos.length === 0) {
    $("#tab_discos").html(
      `<div class="alert alert-info">Sin discos registrados</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered">
    <thead style="background-color: #E3ECF5;">
      <tr>
        <th>Letra</th>
        <th>Tipo</th>
        <th>Designación</th>
        <th>Sistema de archivos</th>
        <th>Libre (MB)</th>
        <th>Total (MB)</th>
        <th style="width: 180px;">Capacidad</th>
        </tr>
    </thead>`;

  d.discos.forEach((disco) => {
    html += `
      <tr>
        <td>${disco.letra_unidad_disco}</td>
        <td>${disco.tipo_unidad}</td>
        <td>${disco.etiqueta_volumen_disco}</td>
        <td>${disco.sistema_archivo}</td>
        <td>${disco.espacio_libre_disco} MB</td>
        <td>${disco.espacio_total_disco} MB</td>
        <td>
          ${barraCapacidad(
            disco.espacio_total_disco,
            disco.espacio_libre_disco
          )}
        </td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_discos").html(html);
}


/* =======================
   TARJETA DE VIDEOS
======================= */
function renderVideos(d) {
  if (!d.tarjeta_video || d.tarjeta_video.length === 0) {
    $("#tab_videos").html(
      `<div class="alert alert-info">Sin tarjetas de videos registrados</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered">
    <thead style="background-color: #e9e3f5;">
      <tr>
        <th>Nombre</th>
        <th>Chipset</th>
        <th>Memoria (MB)</th>
        <th>Resolución</th>
      </tr>
    </thead>`;

  d.tarjeta_video.forEach((video) => {
    html += `
      <tr>
        <td>${video.nombre_video || "N/D"}</td>
        <td>${video.chipset_video || "N/D"}</td>
        <td>${video.memoria_video || "N/D"} MB</td>
        <td>${video.resolucion_video || "N/D"}</td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_videos").html(html);
}


/* =======================
  SONIDOS
======================= */
function renderSonidos(d) {
  if (!d.sonidos || d.sonidos.length === 0) {
    $("#tab_sonidos").html(
      `<div class="alert alert-warning">No hay datos disponibles</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered">
    <thead style="background-color: #f5e9eb;">
      <tr>
        <th>Nombre</th>
        <th>Fabricante</th>
        <th>Descripción</th>
      </tr>
    </thead>`;

  d.sonidos.forEach((sonido) => {
    html += `
      <tr>
        <td>${sonido.nombre_sonido || "N/D"}</td>
        <td>${sonido.fabricante_sonido || "N/D"}</td>
        <td>${sonido.descripcion_sonido || "N/D"}</td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_sonidos").html(html);
}


/* =======================
  CONTROLADORES
======================= */
function renderControladores(d) {
  if (!d.controladores || d.controladores.length === 0) {
    $("#tab_controladores").html(
      `<div class="alert alert-warning">No hay datos disponibles</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>Fabricante</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Version</th>
      </tr>
    </thead>`;

  d.controladores.forEach((controlador) => {
    html += `
      <tr>
        <td>${controlador.fabricante_controlador || "N/D"}</td>
        <td>${controlador.nombre_controlador || "N/D"}</td>
        <td>${controlador.tipo_controlador || "N/D"}</td>
        <td>${controlador.version_controlador || "N/D"}</td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_controladores").html(html);
}


/* =======================
  RANURAS
======================= */
function renderRanuras(d) {
  if (!d.ranuras || d.ranuras.length === 0) {
    $("#tab_ranuras").html(
      `<div class="alert alert-warning">No hay datos disponibles</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Designación</th>
      </tr>
    </thead>`;

  d.ranuras.forEach((ranura) => {
    html += `
      <tr>
        <td>${ranura.nombre_ranura || "N/D"}</td>
        <td>${ranura.descricion_ranura || "N/D"}</td>
        <td>${ranura.designacion_ranura || "N/D"}</td>
      </tr>
    `;
  });

  html += `</table>`;
  $("#tab_ranuras").html(html);
}

/* =======================
  PUERTOS
======================= */
function renderPuertos(d) {
  if (!d.puertos || d.puertos.length === 0) {
    $("#tab_puertos").html(
      `<div class="alert alert-warning">No hay datos disponibles</div>`,
    );
    return;
  }

  let html = `<table class="table table-bordered" id="tabla_puertos">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>Tipo</th>
        <th>Nombre</th>
        <th>Interfaz</th>
        <th>Descripción</th>
      </tr>
    </thead>
    <tbody>`;

  d.puertos.forEach((puerto) => {
    html += `
      <tr>
        <td>${puerto.tipo_port || "N/D"}</td>
        <td>${puerto.nombre_port || "N/D"}</td>
        <td>${puerto.interfaz_port || "N/D"}</td>
        <td>${puerto.descripcion_port || "N/D"}</td>
      </tr>
    `;
  });

  html += `
      </tbody>
    </table>
  `;

  $("#tab_puertos").html(html);

  // Evitar reinicialización
  if ($.fn.DataTable.isDataTable('#tabla_puertos')) {
    $('#tabla_puertos').DataTable().destroy();
  }

  $("#tabla_puertos").DataTable({
    pageLength: 10,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
    autoWidth: false,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    }
  });
}

/* =======================
   RED
======================= */
function renderRed(d) {

  if (!d.redes || d.redes.length === 0) {
    $("#datosRedes").html(`
      <tr>
        <td colspan="11" class="text-center text-muted">
          No hay interfaces de red registradas
        </td>
      </tr>
    `);
    return;
  }

  let html = '';

  d.redes.forEach((red) => {
    html += `
      <tr>
        <td>${red.descripcion_red || 'N/D'}</td>
        <td>${red.tipo_red || 'N/D'}</td>
        <td>${red.velocidad_red || 'N/D'}</td>
        <td>${red.max_tamano_red || 'N/D'}</td>
        <td>${red.mac_red || 'N/D'}</td>
        <td>
          <span class="badge ${red.estado_reed === 'Up' ? 'badge-success' : 'badge-secondary'}">
            ${red.estado_reed || 'N/D'}
          </span>
        </td>
        <td>${red.ip_host_red || 'N/D'}</td>
        <td>${red.mascara_host_red || 'N/D'}</td>
        <td>${red.puerta_enlace_red || 'N/D'}</td>
        <td>${red.subnet_red || 'N/D'}</td>
        <td>${red.dhcp_red || 'N/D'}</td>
      </tr>
    `;
  });

  $("#datosRedes").html(html);

  // Evitar reinicialización
  if ($.fn.DataTable.isDataTable('#tabla_redes')) {
    $('#tabla_redes').DataTable().destroy();
  }

  $('#tabla_redes').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
    autoWidth: false,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    }
  });
}




/* =======================
   PERIFÉRICOS
======================= */

/* =======================
   SIM
======================= */
function renderPDA(d) {

  if (!d.sim || d.sim.length === 0) {
    $("#tab_pda").html(`
      <tr>
        <td colspan="11" class="text-center text-muted">
          No hay datos disponibles para mostrar
        </td>
      </tr>
    `);
    return;
  }

  let html = `<table class="table table-bordered" id="tabla_sim">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>ID del operador</th>
        <th>Nombre del operador</th>
        <th>País</th>
        <th>Número de Teléfono</th>
        <th>Nro de Serie</th>
        <th>IMEI/ID Dispositivo</th>
      </tr>
    </thead>
    <tbody>`;

  d.sim.forEach((s) => {
    html += `
      <tr>
        <td>${s.id_operador || 'N/D'}</td>
        <td>${s.nombre_operador || 'N/D'}</td>
        <td>${s.pais_operador || 'N/D'}</td>
        <td>${s.ncelular_operador || 'N/D'}</td>
        <td>${s.sn_celu || 'N/D'}</td>
        <td>${s.imei_celu || 'N/D'}</td>
      </tr>
    `;
  });

  $("#tab_pda").html(html);

  // Evitar reinicialización
  if ($.fn.DataTable.isDataTable('#tabla_sim')) {
    $('#tabla_sim').DataTable().destroy();
  }

  $('#tabla_sim').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
    autoWidth: false,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    }
  });
}

/* =======================
   MONITORES
======================= */
function renderMonitores(d) {

  if (!d.monitores || d.monitores.length === 0) {
    $("#tab_monitores").html(`
      <tr>
        <td colspan="11" class="text-center text-muted">
          No hay datos disponibles para mostrar
        </td>
      </tr>
    `);
    return;
  }

  let html = `<table class="table table-bordered" id="tabla_monitores">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>Fabricante</th>
        <th>Identificador</th>
        <th>Fabricado en (Semana/Año)</th>
        <th>Tipo</th>
        <th>Nro de Serie</th>
      </tr>
    </thead>
    <tbody>`;

  d.monitores.forEach((monitor) => {
    html += `
      <tr>
        <td>${monitor.fabricante_monitor || 'N/D'}</td>
        <td>${monitor.identificador_monitor || 'N/D'}</td>
        <td>${monitor.descripcion_monitor || 'N/D'}</td>
        <td>${monitor.tipo_monitor || 'N/D'}</td>
        <td>${monitor.sn_monitor || 'N/D'}</td>
      </tr>
    `;
  });

  $("#tab_monitores").html(html);

  // Evitar reinicialización
  if ($.fn.DataTable.isDataTable('#tabla_monitores')) {
    $('#tabla_monitores').DataTable().destroy();
  }

  $('#tabla_monitores').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
    autoWidth: false,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    }
  });
}

/* =======================
   ENTRADAS
======================= */
function renderEntradas(d) {

  if (!d.entradas || d.entradas.length === 0) {
    $("#tab_entradas").html(`
      <tr>
        <td colspan="11" class="text-center text-muted">
          No hay datos disponibles para mostrar
        </td>
      </tr>
    `);
    return;
  }

  let html = `<table class="table table-bordered" id="tabla_entradas">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>Tipo</th>
        <th>Fabricante</th>
        <th>Identificador</th>
        <th>Descripción</th>
        <th>Interfaz</th>
      </tr>
    </thead>
    <tbody>`;

  d.entradas.forEach((entrada) => {
    html += `
      <tr>
        <td>${entrada.tipo_dispositivo_entrada || 'N/D'}</td>
        <td>${entrada.fabricante_entrada || 'N/D'}</td>
        <td>${entrada.captura_entrada || 'N/D'}</td>
        <td>${entrada.descripcion_entrada || 'N/D'}</td>
        <td>${entrada.interfaz_entrada || 'N/D'}</td>
      </tr>
    `;
  });

  $("#tab_entradas").html(html);

  // Evitar reinicialización
  if ($.fn.DataTable.isDataTable('#tabla_entradas')) {
    $('#tabla_entradas').DataTable().destroy();
  }

  $('#tabla_entradas').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
    autoWidth: false,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    }
  });
}


/* =======================
   IMPRESORAS
======================= */
function renderImpresoras(d) {

  if (!d.impresoras || d.impresoras.length === 0) {
    $("#tab_impresoras").html(`
      <tr>
        <td colspan="11" class="text-center text-muted">
          No hay datos disponibles para mostrar
        </td>
      </tr>
    `);
    return;
  }

  let html = `<table class="table table-bordered" id="tabla_impresoras">
    <thead style="background-color: #e9f5f5;">
      <tr>
        <th>Nombre</th>
        <th>Controlador</th>
        <th>Puerto</th>
        <th>Descripción</th>
        <th>Servidor de Impresora Compartida</th>
        <th>Compartir Impresora en el Servidor</th>
        <th>Sentido (Horizontal/Vertical)</th>
        <th>Comentarios</th>
        <th>Compartido</th>
        <th>Local/Redes</th>
      </tr>
    </thead>
    <tbody>`;

  d.impresoras.forEach((impresora) => {
    html += `
      <tr>
        <td>${impresora.nombre_impresora || 'N/D'}</td>
        <td>${impresora.controlador_impresora || 'N/D'}</td>
        <td>${impresora.puerto_impresora || 'N/D'}</td>
        <td>${impresora.descripcion_impresora || 'N/D'}</td>
        <td>${impresora.servidor_impresora || 'N/D'}</td>
        <td>${impresora.compartir_impresora || 'N/D'}</td>
        <td>${impresora.resolucion_impresora || 'N/D'}</td>
        <td>${impresora.comentario_impresora || 'N/D'}</td>
        <td>${impresora.compartido_impresora}</td>
        <td>${impresora.red_local_impresora}</td>
      </tr>
    `;
  });

  $("#tab_impresoras").html(html);

  // Evitar reinicialización
  if ($.fn.DataTable.isDataTable('#tabla_impresoras')) {
    $('#tabla_impresoras').DataTable().destroy();
  }

  $('#tabla_impresoras').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    paging: true,
    autoWidth: false,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    }
  });
}