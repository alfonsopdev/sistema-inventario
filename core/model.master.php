<?php

require_once 'conexion.php';

class ModelMaster extends Conexion{

  protected $pdo;

  public function __CONSTRUCT(){
    $this->pdo = parent::getConnect();
  }
  
  /**
   * Obtiene una lista de parámetros de un array asociativo pasado como valor
   * @return string
   */
  public function getParameters(array $array){
    $string = "";     //Lista de parámetros
    $i = 0;           //Contador de elementos del arreglo

    //En esta estructura extraemos la CLAVE del array asociativo
    foreach($array as $key => $value){
      if ($i != count($array) - 1){
        $string .= ":" . $key . ", ";
      } else {
        $string .= ":" . $key;
      }
      $i++;
    }

    return $string;
  }


  /**
   * Obtiene un número indeterminado de filas a partir de una consulta (LISTAR SIN VARIABLES)
   * @return array<int,object>  // o array<object> si tu herramienta lo admite
   */
  public function getRows(string $storeProcedure): array {
    $commandSQL = $this->pdo->prepare("CALL $storeProcedure()");
    $commandSQL->execute();
    return $commandSQL->fetchAll(PDO::FETCH_OBJ); // array de stdClass
  }

  /**
   * Elimina uno o más registros dependiendo de los criterios establecidos en la matriz
   * @return void retorna valores
   */
  public function deleteRows(array $array, $storeProcedure){
    try{
      //Primero debemos obtener todos los parámetros del arreglo
      $parameters = self::getParameters($array);

      //Preparamos la consulta a ejecutar
      $commandSQL = $this->pdo->prepare("call $storeProcedure($parameters)");

      //Debemos asociar los valores al comando a ejecutar
      foreach($array as $key => $value){
        $commandSQL->bindValue(":" .  $key, $value);
      }

      $commandSQL->execute();

    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

  /**
   * Ejecuta un procedimiento almacenado con el conjunto de parámetros definidos
   * en el arreglo de entrada. Se puede establecer si este proceso retorna o no datos
   */
  public function execProcedure(array $array, $storeProcedure, $return = false){
    try{
      //Se obtiene las claves contenidas en el arreglo
      $parameters = self::getParameters($array);

      //Ejecución del procedimiento almacenado
      $commandSQL = $this->pdo->prepare("call $storeProcedure($parameters)");

      //Enviamos todos los valores asociados
      foreach($array as $key => $value){
        $commandSQL->bindValue(":" . $key, $value);
      }

      //Ejecutamos el comando
      $commandSQL->execute();

      //Solo si se solicitó retorno de datos
      if ($return){
        return $commandSQL->fetchAll(PDO::FETCH_OBJ);
      }

    }catch (Exception $e){
      throw $e;
    }
  }



  public function retornoconsulta(array $array, $storeProcedure, $return = false){
    try{
      //Se obtiene las claves contenidas en el arreglo
      $parameters = self::getParameters($array);

      //Ejecución del procedimiento almacenado
      $commandSQL = $this->pdo->query("$storeProcedure($parameters)");

      //Enviamos todos los valores asociados
      foreach($array as $key => $value){
        $commandSQL->bindValue(":" . $key, $value);
      }

      //Ejecutamos el comando
      $commandSQL->execute();

      //Solo si se solicitó retorno de datos
      if ($return){
        return $commandSQL->fetchAll(PDO::FETCH_OBJ);
      }

    }catch (Exception $e){
      die($e->getMessage());
    }
  }

  public function execProcedureLogin(array $array, $storeProcedure, $return = false){

    try{

      //Se obtiene las claves contenidas en el arreglo 

      $parameters = self::getParameters($array);

      //Ejecución del procedimiento almacenado

      $commandSQL = $this->pdo->prepare("call $storeProcedure($parameters)");



      //Enviamos todos los valores asociados

      foreach($array as $key => $value){

        $commandSQL->bindValue(":" . $key, $value);

      }



      //Ejecutamos el comando

      $commandSQL->execute();



      //Solo si se solicitó retorno de datos

      if ($return){

        return $commandSQL->fetchAll(PDO::FETCH_ASSOC);

      }

    }catch (Exception $e){
      throw $e;
    }

  }



public function execProcedurePositional(array $params, string $sp){
    try{
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL $sp($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($params));
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt->closeCursor(); // 🔥 OBLIGATORIO en SP
        return $data;
    }catch(Exception $e){
        die($e->getMessage());
    }
}



  


}

?>