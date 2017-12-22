<?php
/**
 * 	TODO:
	- @author Omar Vasquez<omar.vasquez.dev@gmail.com>
	- Base Controlador 
	- Omua framework
 */

/*==========================================
=            Section CONTROLLER            =
==========================================*/
namespace Core\DataBase;

use \PDO;
use Core\DataBase\DB;

abstract class Crud extends DB{

	protected $table;


	/**
	 * Class Constructor
	 * @param    $table   
	 */
	public function __construct($table)
	{
		$this->table = $table;
	}

	abstract public function insert();
	abstract public function update($id);

//Método que se quedará satico para buscar datos.
	//BUSCADOR
	public function find($id){
		$sql  = "SELECT * FROM $this->table WHERE id = :id";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
	//SACAR TODOS LOS DATOS DE LAS TABLAS
	public function findAll(){
		$sql  = "SELECT * FROM $this->table";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	//ELIMINAR UN DATO DE UNA TABLA POR SU ID 
	public function delete($id){
		$sql  = "DELETE FROM $this->table WHERE id = :id";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		return $stmt->execute(); 
	}


  	public function smartCrud($post=null, $id=null)
	{
		if (count($post) > 0) {
			$selection_option = array_pop($post);
			switch ($selection_option) {
				case 'create':
					return $this->smartInsert($post);
					break;
				
				case 'update':
					if ($id!=null) {
				 	return $this->smartUpdate($post,$id);
					}else return false;
					break;
				default:
					return false; 
				break;
			}
		} return false;
	}

	private function smartInsert($post)
	{	
		$limite    = count($post);
		$i         = 1 ;
		$colums    = "" ;
		$reference = "" ;

		foreach ($post as $key => $value) {
			if ($limite>$i) {
				$colums    .= $key . "," ;
				$reference .= " :" . $key . " ,";
			}else{
				$colums    .= $key ;
				$reference .= " :" . $key ;
			}
			$i++;
		}
		 $sql  = "INSERT INTO $this->table (" . $colums . ") VALUES (" . $reference . ")";
		 $stmt = DB::prepare($sql);
		 foreach ($post as $key => $value) {
		 	$refer = ':' . $key;
		 	$stmt->bindValue($refer,$value);
		 }
		 return $stmt->execute(); 
	}

	public function lastid(){
		$sql  ="SELECT MAX(id) AS id FROM $this->table";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetch();
	}

	private function smartUpdate($post, $id)
	{
		$limite    = count($post);
		$i         = 1 ;
		$colums    = "" ;
		foreach ($post as $key => $value) {
			if ($limite>$i) {
				$colums    .= $key . "= :" . $key . " ," ;
			}else{
				$colums    .= $key . "= :" . $key ;
			}
			$i++;
		}

	  	$sql  = "UPDATE $this->table SET ".$colums." WHERE id = :id";
  		$stmt = DB::prepare($sql);
	 	$stmt->bindValue(':id', $id);
		 foreach ($post as $key => $value) {
		 	$stmt->bindValue(':' . $key , $value);
		 }
		return  $stmt->execute();
	}
}