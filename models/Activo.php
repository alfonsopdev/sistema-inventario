<?php

require_once '../core/model.master.php';

class Activo extends ModelMaster{

    public function cargarCalidadMovFiltro(){
        try{
          return parent::getRows("spu_activo_cargarCalidadMovFiltro");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

  public function registrarActivo(array $data){
    parent::execProcedure($data, "spu_activo_registro", false);
  }
    
  public function activoYaRegistrado(array $data){
    return parent::execProcedureLogin($data, "spu_activo_existente_cod_patrimonial", true);
  }

  public function eliminarProducto(array $data){
    try{
        parent::deleteRows($data, "spu_eliminar_producto");
    }catch(Exception $error){
        die($error->getMessage());
    }
  }
  
  public function registrarMovPrestamo(array $data){
        try{
           return parent::execProcedure($data, "spu_movimientoPrestamo_registrar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
  }

  public function registrarMovPrestamoActivos(array $data){
        try{
           return parent::execProcedure($data, "spu_movimientoPrestamoActivos_registrar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
  }

  public function registrarMovTransferencia(array $data){
        try{
           return parent::execProcedure($data, "spu_movimientoTransferencia_registrar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
  }

  public function registrarMovTransferenciaActivos(array $data){
        try{
           return parent::execProcedure($data, "spu_movimientoTransferenciaActivos_registrar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
  }

  public function registrarMovDevolucion(array $data){
        try{
           return parent::execProcedure($data, "spu_movimiento_registrarDevolucion", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
  }

  public function modificarActivo(array $data){
    try{
        return parent::execProcedure($data,"spu_activo_editar", true);
    }catch(Exception $error){
        die($error->getMessage());
    }
  } 

  public function cargarActivo(array $data){
      try{
          return parent::execProcedure($data, "spu_activo_cargardata", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function getActivo(array $data){
      try{
          return parent::execProcedure($data, "spu_activo_getdata", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function traerActivoDevolucion(array $data){
      try{
          return parent::execProcedure($data, "spu_activo_traerActivoDevolucion", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function consultarTimeline(array $data){
      try{
          return parent::execProcedure($data, "spu_mov_consultarTimeline", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function filtrarActivo(array $data){
        try{
            return parent::execProcedure($data, "spu_activo_aplicarfiltro", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

  public function filtrarCategoria(array $data){
    try{
        return parent::execProcedure($data, "spu_productos_filtrar_categorias", true);
    }catch(Exception $error){
        die($error->getMessage());
    }
  }

  public function listarActivo(){
    try{
        return parent::getRows("spu_activo_listar");
    }catch(Exception $error){
        die($error->getMessage());
    }
  }

  public function listarConGarantia(){
    try{
        return parent::getRows("spu_activo_listar_con_garantia");
    }catch(Exception $error){
        die($error->getMessage());
    }
  }

  public function filtrarConGarantia(array $data){
    try{
        return parent::execProcedure($data, "spu_activo_filtrar_con_garantia", true);
    }catch(Exception $error){
        die($error->getMessage());
    }
  }

  public function cargaMasiva(array $data){
    return parent::execProcedure($data, "spu_activo_carga_masiva", true);
  }

}
?>