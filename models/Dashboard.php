<?php

require_once '../core/model.master.php';

class Dashboard extends ModelMaster{

    public function kpis(){
        try{
            return parent::getRows("spu_dashboard_kpis");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function estadosActivos(){
        try{
            return parent::getRows("spu_dashboard_estados");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function movimientosMes(){
        try{
            return parent::getRows("spu_dashboard_movimientos_mes");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function ultimosMovimientos(){
        try{
            return parent::getRows("spu_dashboard_ultimos_movimientos");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

}