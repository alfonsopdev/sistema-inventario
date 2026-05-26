<?php

require_once '../core/model.master.php';

class Usuario extends ModelMaster{

    public function login(array $data){
        try{
        return parent::execProcedureLogin($data, "spu_usuarios_login", true);
        } catch (Exception $error){
        die($error->getMessage());
        }
    }
  
    public function registrarUsuario(array $data){
        try{
            parent::execProcedure($data, "spu_usuarios_registro", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function reporteAsistencia(){
        try{
            return parent::getRows("spu_asistencia");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
    
    /** @return array<int, object> */
    public function listarUsuarios(){
        try{
            return parent::getRows("spu_usuarios_listar");
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function actualizarClave(array $data){
        try {
          parent::execProcedureLogin($data, "spu_usuarios_actualizarclave", false);
        } catch (Exception $error) {
            die($error->getMessage());
        }
    }
    
    public function eliminarUsuario(array $data){
        try{
            parent::execProcedure($data, "spu_usuarios_desactivar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function reactivarUsuario(array $data){
        try{
            parent::execProcedure($data, "spu_usuarios_reactivar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }
    
    public function modificarUsuario(array $data){
        try{
            parent::execProcedure($data, "spu_usuarios_modificar", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function getUsuario(array $data){
        try{
            return parent::execProcedure($data, "spu_usuarios_getdata", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function nombreusuarioYaRegistrado(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_nombreusuario_registrado", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function personaYaAsignada(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_persona_asignada", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function codverificacion(array $data){
        try{
            parent::execProcedure($data, "spu_usuario_codverificacion", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function eliminarCodverificacion(array $data){
        try{
            parent::execProcedure($data, "spu_usuario_eliminarcodverificacion", false);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function validarCorreoContraseña(array $data){
        try{
            return parent::execProcedure($data, "spu_usuario_verificarcorreo", true);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function emailNoRegistrado(array $data){
        try{
            return parent::execProcedureLogin($data, "spu_emailnoexiste_registrado", true);
        }
        catch (Exception $error){
            die($error->getMessage());
        }
    }

    public function obtenerEstadoPorId(int $id): ?string {
    $stmt = $this->pdo->prepare("SELECT usu_estado FROM usuario WHERE id_usuario = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['usu_estado'] : null;
    }
}
?>