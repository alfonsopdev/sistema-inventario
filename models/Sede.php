<?php

require_once '../core/model.master.php';

class Sede extends ModelMaster{

    public function cargarSede(){
        try{
          return parent::getRows("spu_sede_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function cargarSedeFiltro(){
        try{
          return parent::getRows("spu_sede_filtro");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }
    
    public function listarSede(){
        try{
          return parent::getRows("spu_sede_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function registrarSede(array $data){
        try{
            parent::execProcedure($data, "	spu_sede_registrar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function sedeExistente(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_sede_existente", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function getSede(array $data){
        try{
            return parent::execProcedure($data, "spu_sede_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function modificarSede(array $data){
        try{
            return parent::execProcedure($data,"spu_sede_editar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function listarDependenciasPorSede(array $data){
        try{
            return parent::execProcedure($data,"spu_persona_editar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}