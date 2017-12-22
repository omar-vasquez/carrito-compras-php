<?php
/**

	TODO:
	- @author Omar Vasquez<omar.vasquez.dev@gmail.com>
	- Diseño basico para el control de base de datos

 */
namespace Core\DataBase;
use \PDO;

import('core.config');
class DB {

	private static $instance;

	public static function getInstance(){

		if(!isset(self::$instance)){

			try {
				self::$instance = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
				self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			} catch (PDOException $e) {
				echo $e->getMessage();
			}

		}
		return self::$instance;
	}
 	
	public static function prepare($sql){
		return self::getInstance()->prepare($sql);
	}

	public static function transaction(){
		return self::getInstance()->beginTransaction();
	}

	public static function commit(){
		return self::getInstance()->commit();
	}
}