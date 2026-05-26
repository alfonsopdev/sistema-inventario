<?php

require_once '../core/model.master.php';

class Restock extends ModelMaster{


    public function registrarRestock(array $data){
        try{
            parent::execProcedure($data, "spu_restock_registro", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function listarRestock(){
        try{
            return parent::getRows("spu_restock_listar");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
}
?>