<?php

require_once '../core/model.master.php';

class Dependencia extends ModelMaster{

    public function cargarDependencia(){
        try{
          return parent::getRows("spu_dependencia_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function cargarDependenciaFiltro(){
        try{
          return parent::getRows("spu_dependencia_filtro");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }
    
    public function listarDependencia(){
        try{
          return parent::getRows("spu_dependencia_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function registrarDependencia(array $data){
        try{
            parent::execProcedure($data, "spu_dependencia_registrar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function dependenciaExistente(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_dependencia_existente", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function getDependencia(array $data){
        try{
            return parent::execProcedure($data, "spu_dependencia_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function modificarDependencia(array $data){
        try{
            return parent::execProcedure($data,"spu_dependencia_editar", true);
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