<?php

require_once '../core/model.master.php';

class Mantenimiento extends ModelMaster
{
    public function listarPorActivo(array $data)
    {
        try {
            return parent::execProcedure($data, "spu_mantenimiento_listar_por_activo", true);
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }

    public function registrar(array $data)
    {
        try {
            return parent::execProcedure($data, "spu_mantenimiento_registrar", true);
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }

    public function editar(array $data)
    {
        try {
            parent::execProcedure($data, "spu_mantenimiento_editar", false);
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }

    public function eliminar(array $data)
    {
        try {
            parent::execProcedure($data, "spu_mantenimiento_eliminar", false);
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }

    public function cargar(array $data)
    {
        try {
            return parent::execProcedure($data, "spu_mantenimiento_cargar", true);
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }

    public function listarTodos()
    {
        try {
            return parent::getRows("spu_mantenimiento_listar_todos");
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }

    public function resumen()
    {
        try {
            return parent::getRows("spu_mantenimiento_resumen");
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }
}
