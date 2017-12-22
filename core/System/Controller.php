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
namespace Core\System;

use Core\Auth\SessionAuth;
use Core\DataBase\crud;
use Core\DataBase\DB;

class Controller
{
	 public  $model;
	 private $__ind;
	 private $__numIndices;
	 public  $Session;
	function __construct($pag)
	 {	$this->__numIndices = count($pag);
	 	$this->__ind    	= $pag;
	 	$metodo             = ( method_exists($this , $pag[0]) )? $pag[0] : "index";
	 	$this->newMethod($metodo , $this->__numIndices , $this->__ind );
	 }

	// #Método para cargar  las vistas;
	public static function HTML($__View,$dato=null)
	{	
		$_content=null;
		$newdato = (object) $dato;
		if (isset($dato)) $_content=$newdato;
			if (file_exists(DIR_VIEWS. "/" . $__View . ".php"))
				include_once(DIR_VIEWS . "/" . $__View . ".php");
			else
		echo 'Error not exist  : __' . $__View .'__view__';
	}

	// #Métodos para cargar un modelo
	public function __loadModel($model)
	{
		include_once(DIR_MODELS . "/$model.php");
		$this->model= new $model;
	}

	// # Cargar database.
	public function __loadDataBase()
	{
		if (DATA_BASE) {
			include_once 'core/core_DB.php';
		}
		else{
			echo "DATA_BASE___ no inicializado compruebe su archivo __settings.php ";
			exit();
		}
	}

	public function __loadSession()
	{
		$this->Session = new SessionAuth;
	}

	// # Redirecciones
	public static function GO($__DIR__='')
	{
		header('Location: ' . $__DIR__);
	}

	public static function _print($data=null)
	{
		echo  $message = (empty($data)) ? $data : null ;
	}

	static public function __titleSlug($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text))
	  {
	    return 'n-a';
	  }

	  return $text;
	}

	private function newMethod($method='index' , $numInd , $_ind)
	{
		$reflection = new \ReflectionMethod($this, $method);
		if ($reflection->isPublic()) {
           $num = $numInd -1 ;
			switch ($num) {
				case 0:
					$this->$method();
					break;
				case 1:
					$this->$method($_ind[1]);
					break;
				case 2:
					$this->$method($_ind[1] ,
								   $_ind[2]);
					break;
				case 3:
					$this->$method($_ind[1] , 
								   $_ind[2] ,
								   $_ind[3] );
					break;
				case 4:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] );
					break;
				case 5:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] ,
							       $_ind[5] );
					break;
				case 6:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] ,
								   $_ind[5] ,
							       $_ind[6] );
					break;
				case 7:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] ,
								   $_ind[5] ,
								   $_ind[6] ,
							       $_ind[7] );
					break;
				case 8:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] ,
								   $_ind[5] ,
								   $_ind[6] ,
								   $_ind[7] ,
							       $_ind[8] );
					break;
				case 9:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] ,
								   $_ind[5] ,
								   $_ind[6] ,
								   $_ind[7] ,
								   $_ind[8] ,
							       $_ind[9] );
					break;
				case 10:
					$this->$method($_ind[1] ,
								   $_ind[2] ,
								   $_ind[3] ,
								   $_ind[4] ,
								   $_ind[5] ,
								   $_ind[6] ,
								   $_ind[7] ,
								   $_ind[8] ,
								   $_ind[9] ,
							       $_ind[10] );
					break;
				default:
					echo "Error -> Mala programación, excedes el numero de parametros permitidos";
					break;
			}
        }else $this::GO("?");
	}


}


/**
* 
*/

 ?>