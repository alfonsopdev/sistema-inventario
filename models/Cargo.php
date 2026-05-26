<?php

require_once '../core/model.master.php';

class Cargo extends ModelMaster{

    public function cargarCargo(){
        try{
          return parent::getRows("spu_cargo_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }
    
    public function listarCargo(){
        try{
          return parent::getRows("spu_cargo_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function registrarCargo(array $data){ 
        try{
            parent::execProcedure($data, "	spu_cargo_registrar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function cargoExistente(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_cargo_existente", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function getCargo(array $data){
        try{
            return parent::execProcedure($data, "spu_cargo_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function modificarCargo(array $data){
        try{
            return parent::execProcedure($data,"spu_cargo_editar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}