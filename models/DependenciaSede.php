<?php

require_once '../core/model.master.php';

class DependenciaSede extends ModelMaster{

    public function cargarDependencia(){
        try{
          return parent::getRows("spu_dependencia_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    
    
    public function listarDependenciaSede(){
        try{
          return parent::getRows("spu_dependenciasede_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    // public function cargarUbicacionFiltro(){
    //     try{
    //       return parent::getRows("spu_dependenciasede_filtro");
    //     }catch(Exception $error){
    //       die($error->getMessage());
    //     }
    // }

    public function registrarDependenciaSede(array $data){
        try{
            parent::execProcedure($data, "spu_dependenciasede_registrar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function relacionDependenciaSedeExistente(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_dependenciasede_existente", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function getDependenciaSede(array $data){
        try{
            return parent::execProcedure($data, "spu_dependenciasede_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function modificarDependenciaSede(array $data){
        try{
            return parent::execProcedure($data,"spu_dependenciasede_editar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function listarDependenciasPorSede(array $data){
        try{
            return parent::execProcedure($data,"spu_dependencia_listar_por_sede", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}