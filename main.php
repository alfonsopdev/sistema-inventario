<?php 
  session_start();
  
  if ($_SESSION['acceso'] == false){
    //Login
    header('Location:index.php');
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Almacén</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="icon" href="images/favicon.png">

  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/user-account.css">
  <link rel="stylesheet" href="dist/css/switch-dark-mode.css">
  <link rel="stylesheet" href="dist/css/themes.css">
  <link rel="stylesheet" href="dist/css/preloader.css">
  <link rel="stylesheet" href="dist/css/loader.css">
  <link rel="stylesheet" href="css/util.css">
  <link rel="stylesheet" href="views/switch.css">
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">

  <link rel="stylesheet" href="css/me.css">

  <!-- <script src="https://cdn.tiny.cloud/1/4wqx4j9cpb6doqzr81ox1xrsx2e9fgddsqtvmdx1mwij8gr8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <div class="content-xbox">
        <div class="loader-xbox"></div>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-light text-sm">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <!-- Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <div id="reloj" style="font-size:20px;"></div> 
      </ul>
      <ul class="navbar-nav">
        <!-- Collapse Menu -->
        <li class="nav-item">
          <div class="d-inline-flex">
          <input  type="hidden" style="font-size:20px;padding-top: 12px;" id="numero"></input>
          </div>
        </li>
      </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="nav-item dropdown user user-menu">
          <a href="#" class="nav-link text-overflow" data-toggle="dropdown">
            <img src="images/perfil2.jpg" class="user-image user-image-top" alt="User Image">
            <span class="hidden-xs"><?= $_SESSION['apellidos']?> <?= $_SESSION['nombres']?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right user-menu">
            <!-- User image -->
            <li class="user-header bg-secondary">
              <img src="images/perfil2.jpg" class="img-circle" alt="User Image">
              <p>
              <?= $_SESSION['apellidos']?> <?= $_SESSION['nombres']?>
                <small><?= $_SESSION['nivelacceso']?></small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer row-flex">
              <div class="pull-left">
                <a href="main.php?view=usuariocambiarcontrasena" class="btn btn-default btn-flat">Contraseña</a>
              </div>
              <div class="pull-right">
                <a href="controllers/Usuario.controller.php?op=cerrar-sesion" class="btn btn-default btn-flat">Cerrar sesión</a>
              </div>
            </li>
          </ul>
        </li>

        <!-- Config -->
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->
    
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-navy elevation-4">
      <!-- Brand Logo -->
      <a href="main.php?view=home" class="brand-link">
        <img src="images/favicon.png" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-bold">Sistema de Almacén</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar" >
        <!-- Sidebar user panel (optional) -->
        <!-- SidebarSearch Form -->
        <div class="form-inline mt-3">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" style="background-color:#252525" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append" >
              <button class="btn btn-sidebar" style="background-color:#252525">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>
        
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar text-sm flex-column nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            
            <?php
              if($_SESSION['nivelacceso'] == 'ADMINISTRADOR' || $_SESSION['nivelacceso'] == 'OPERADOR'){
                echo "
                  <li class='nav-item mt-3'>
                    <a href='index.php' class='nav-link'>
                    <i class='fas fa-home nav-icon'></i>
                      <p>Home</p>
                    </a>
                  </li>
                  <li class='nav-header' style='background-color:#292929'>Activos</li>
                  <li class='nav-item'>
                    <a href='main.php?view=activo' class='nav-link'>
                    <i class='fas fa-box nav-icon'></i>
                      <p>Activos</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=garantia' class='nav-link'>
                    <i class='fa fa-shield-alt nav-icon'></i>
                      <p>Garantías</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=mantenimiento' class='nav-link'>
                    <i class='fa fa-tools nav-icon'></i>
                      <p>Mantenimientos</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=movimiento' class='nav-link'>
                    <i class='fa fa-truck nav-icon'></i>
                      <p>Movientos de Activos</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=categoria' class='nav-link'>
                    <i class='fas fa-tags nav-icon'></i>
                      <p>Categorías</p>
                    </a>
                  </li>
                  ";
              } 
              if($_SESSION['nivelacceso'] == 'ADMINISTRADOR'){
                echo "
                  <li class='nav-header' style='background-color:#292929'>Usuarios</li>
                  <li class='nav-item'>
                    <a href='main.php?view=usuario' class='nav-link'>
                      <i class='fas fa-users-cog nav-icon'></i>
                      <p>Usuarios</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=persona' class='nav-link'>
                      <i class='fas fa-users nav-icon'></i>
                      <p>Personas</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=administrativo' class='nav-link'>
                      <i class='fas fa-user-tie nav-icon'></i>
                      <p>Administrativos</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=cargo' class='nav-link'>
                      <i class='fas fa-solid fa-briefcase nav-icon'></i>
                      <p>cargo</p>
                    </a>
                  </li>
                  
                  <li class='nav-header' style='background-color:#292929'>Dependencia</li>
                  <li class='nav-item'>
                    <a href='main.php?view=dependencia' class='nav-link'>
                      <i class='fas fa-sitemap nav-icon'></i>
                      <p>Dependencias</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=sede' class='nav-link'>
                      <i class='fas fa-building nav-icon'></i>
                      <p>Sedes</p>
                    </a>
                  </li>
                  <li class='nav-item'>
                    <a href='main.php?view=depen_sede' class='nav-link'>
                      <i class='fas fa-link nav-icon'></i>
                      <p>Dependencia/Sede</p>
                    </a>
                  </li>
                  
                ";
              }  
            ?>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper text-sm" id="content-body">
      <!-- Content Header (Page header) -->
      
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content pt-3">
        <div class="container-fluid" id="contenido">
          <!-- Aqui se cargan los datos dinamicos -->        
        </div><!--/. container-fluid -->
      </section>
      <!-- /.content -->

      <!-- Subir al inicio -->
      <a id="back-to-top" href="#content-body" class="btn btn-dark back-to-top d-none" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark" style="overflow: hidden;">
      <!-- Control sidebar content goes here -->
      <div class="p-3 control-sidebar-content text-sm" style="height: fit-content;">
        <h5>Configuración</h5>
        <hr class="mb-2"/>

        <h6>Barra lateral izquierdo</h6>

        <div class="mb-1">
          <input type="checkbox" class="mr-1" checked id="cbox-sidebar-mini">
          <span>Pequeño</span>
        </div>
        <div class="mb-1">
          <input type="checkbox" class="mr-1" id="cbox-sidebar-flat-style">
          <span>Estilo flat</span>
        </div>
        <div class="mb-4">
          <input type="checkbox" class="mr-1" id="cbox-sidebar-disable-focus">
          <span>Deshabilitar autoexpansión</span>
        </div>

        <h6>Reducir el tamaño del texto</h6>

        <div class="mb-1">
          <input type="checkbox" class="mr-1" checked id="cbox-small-text-content-wrapper">
          <span>Contenido</span>
        </div>
        <div class="mb-1">
          <input type="checkbox" class="mr-1" id="cbox-small-text-sidebar" checked>
          <span>Barra lateral (Izq, Der)</span>
        </div>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer text-sm">
      <strong>Copyright &copy; 2022 <a href="https://www.facebook.com/Clinicafamisaludoficial/">Inventario OTI</a>.</strong>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  <!-- select2 -->
  <script src="plugins/select2/js/select2.full.min.js"></script>
  
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>

  <!-- SweetAlert2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

  <!-- Cargar pagina incrustada -->
  <script src="./dist/js/loadweb.js"></script>

  <!-- Config theme -->
  <script src="./dist/js/config.js"></script>

  <!-- horarios -->
  <script src="./dist/js/moment/moment-with-locales.js"></script>
  <script src="./dist/js/moment/moment.js"></script>

  <!-- Bootstrap autocomplete -->
  <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
  <!-- <script src="https://cdn.tiny.cloud/1/4wqx4j9cpb6doqzr81ox1xrsx2e9fgddsqtvmdx1mwij8gr8/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
  <script src="js/alertas.js"></script>

  <script>
    $(document).ready(function(){
      function activarMenu() {
        const urlParams = new URLSearchParams(window.location.search);
        let view = urlParams.get('view');

        // Quitar active
        $('.nav-sidebar .nav-link').removeClass('active');

        // HOME
        if (!view) {
          $('.nav-sidebar .nav-link[href="index.php"]').addClass('active');
          return;
        }

        // 🔥 QUEDARSE SOLO CON EL MÓDULO RAÍZ
        // movimiento/view_detalle_movimiento.php → movimiento
        const modulo = view.split('/')[0];

        $('.nav-sidebar .nav-link').each(function () {
          const href = $(this).attr('href');

          if (href && href.includes('view=' + modulo)) {
            $(this).addClass('active');

            // soporte treeview (si luego agregas submenús)
            $(this).parents('.nav-item').addClass('menu-open');
            $(this).parents('.nav-treeview').prev('a').addClass('active');
          }
        });
      }

      let content = getParam("view");
      
      if (!content) {
        $("#contenido").load("view/index.php", activarMenu);
      } else {
        
        // Si se pide un archivo .php → lo carga directamente
        if (content.includes(".php")) {
          $("#contenido").load("view/" + content, activarMenu);
        }
        // Si NO → carga el módulo/index.php
        else {
          $("#contenido").load("view/" + content + "/index.php", activarMenu);
        }
      }
    });

    function startTime(){
      today=new Date();
      h=today.getHours();
      m=today.getMinutes();
      s=today.getSeconds();
      m=checkTime(m);
      s=checkTime(s);
      document.getElementById('reloj').innerHTML=h+":"+m+":"+s;
      t=setTimeout('startTime()',500);
    }
    function checkTime(i){
      if (i<10) {i="0" + i;}return i;
    }
    window.onload=function(){
      startTime();
    }

    
  </script>

</body>
</html>
