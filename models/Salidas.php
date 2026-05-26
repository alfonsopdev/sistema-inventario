<?php

require_once '../core/model.master.php';

class Salidas extends ModelMaster{


    public function registrarSalidas(array $data){
        try{
            parent::execProcedure($data, "spu_salidas_registro", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function listarSalidas(){
        try{
            return parent::getRows("spu_salidas_listar");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}
?>