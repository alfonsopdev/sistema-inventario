<?php

require_once '../core/model.master.php';

class Persona extends ModelMaster{

    public function cargarPersona(){
        try{
          return parent::getRows("spu_personas_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }
    
    public function listarPersona(){
        try{
          return parent::getRows("spu_persona_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function registrarPersona(array $data){
        try{
            parent::execProcedure($data, "	spu_persona_registrar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function dniYaExiste(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_dnipersona_existente", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function getPersona(array $data){
        try{
            return parent::execProcedure($data, "spu_persona_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function modificarPersona(array $data){
        try{
            return parent::execProcedure($data,"spu_persona_editar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}