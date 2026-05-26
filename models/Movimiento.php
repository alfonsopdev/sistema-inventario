<?php

require_once '../core/model.master.php';

class Movimiento extends ModelMaster{
    
  public function listarMovimiento(){
        try{
          return parent::getRows("spu_movimiento_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

  public function traerActivosDevolucion(array $data){
      try{
          return parent::execProcedure($data, "spu_movimiento_traerActivosDevolucion", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function cargarActivos(array $data){
      try{
          return parent::execProcedure($data, "spu_movimiento_cargarActivos", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function filtrarMovimiento(array $data){
      try{
          return parent::execProcedure($data, "spu_movimiento_aplicarfiltro", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function obtenerDocumentoMovimiento(array $data){
      try{
          return parent::execProcedure($data, "spu_movimiento_verDocumento", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
  }

  public function registrarMovDevolucionActivos(array $data){
        try{
          return parent::execProcedure($data, "spu_movimiento_registrarDevolucionActivos", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
  }

  /* ===============================
     TICKET - CABECERA
     =============================== */
    public function obtenerMovimientoTicket(array $data){
      try{
        return parent::execProcedure(
          $data,
          "spu_movimiento_ticket_cabecera",
          true
        );
      }catch(Exception $error){
        die($error->getMessage());
      }
    }

    /* ===============================
      TICKET - ACTIVOS
      =============================== */
    public function obtenerActivosMovimiento(array $data){
      try{
        return parent::execProcedure(
          $data,
          "spu_movimiento_ticket_activos",
          true
        );
      }catch(Exception $error){
        die($error->getMessage());
      }
    }
  
}