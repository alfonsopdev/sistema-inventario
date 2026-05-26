<?php

require_once '../core/model.master.php';

class Administrativo extends ModelMaster{

    public function cargarAdministrativo(){
        try{
          return parent::getRows("spu_administrativo_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function cargarAdministrativosFiltro(){
        try{
          return parent::getRows("spu_administrativoFiltro_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }


    public function cargarOtrosAdministrativos(array $data){
        try{
            return parent::execProcedure($data,"spu_administrativoOtros_cargar", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function cargarDependencia(){
        try{
          return parent::getRows("spu_dependencia_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }
    
    public function listarAdministrativo(){ 
        try{
          return parent::getRows("spu_administrativo_listar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function listarSedePorAdministrativo(array $data){
        try{
            return parent::execProcedure($data,"spu_sede_listar_por_administrativo", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function listarDependenciaPorAdministrativo(array $data){
        try{
            return parent::execProcedure($data,"spu_dependencia_listar_por_administrativo", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function registrarAdministrativo(array $data){
        try{
            parent::execProcedure($data, "spu_administrativo_registrar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function personaYaAsignada(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_administrativo_persona_asignada", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function getAdministrativo(array $data){
        try{
            return parent::execProcedure($data, "spu_administrativo_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function getAdministrativoActivo(array $data){
        try{
            return parent::execProcedure($data, "spu_administrativo_activo_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function getJefeOficina(array $data){
        try{
            return parent::execProcedure($data, "spu_administrativo_jefe_activo_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function modificarAdministrativo(array $data){
        try{
            parent::execProcedure($data,"spu_administrativo_editar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}