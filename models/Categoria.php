<?php

require_once '../core/model.master.php';

class Categoria extends ModelMaster{

    public function cargarCategoria(){
        try{
          return parent::getRows("spu_categoria_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }

    public function cargarCategoriasFiltro(){
        try{
          return parent::getRows("spu_categoriaFiltro_cargar");
        }catch(Exception $error){
          die($error->getMessage());
        }
    }
    
    public function registrarCategoria(array $data){
        try{
          parent::execProcedure($data, "spu_categoria_registro", false);
        }catch(Exception $error){
          die($error->getMessage());
        }
      }
      
    public function categoriaExistente(array $data){
      try{
          return parent::execProcedureLogin($data, "spu_categoria_existente", true);
      }
      catch (Exception $error){
          die($error->getMessage());
      }
    }
    
    public function eliminarcategorias(array $data){
      try{
          parent::deleteRows($data, "spu_eliminar_categorias");
      }catch(Exception $error){
          die($error->getMessage());
      }
    }
    
    public function modificarCategoria(array $data){
      try{
          return parent::execProcedure($data, "spu_categoria_editar", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
    } 
    
    public function getCategoria(array $data){
        try{
            return parent::execProcedure($data, "spu_categoria_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
    
    public function filtrarCategoria(array $data){
      try{
          return parent::execProcedure($data, "spu_categoriass_filtrar_categorias", true);
      }catch(Exception $error){
          die($error->getMessage());
      }
    }
    
    public function listarCategorias(){
      try{
          return parent::getRows("spu_categorias_listar");
      }catch(Exception $error){
          die($error->getMessage());
      }
    }

}




?>
