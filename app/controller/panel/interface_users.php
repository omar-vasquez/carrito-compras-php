<?php
/**
 * 
 */
namespace App\controller\panel;
use Core\System\Controller;

class Interface_users  extends Controller{

 	protected static $ModuleBase = '?panel';

 	protected  function nav(){
 		//-->creacion de nav
 		$usr = $this-> Session ->getUser();
 		$this->tpt->setBase('admin/partials/nav.html');
 		$object = 
 		[
 			"user" => $usr->email ,
 			"logout" => "?welcome/logout",
 		];
 		$this-> tpt ->parse($object);
 		return $this-> tpt ->getContent();
 	}

 	protected function subnav($active=""){
 		$usr = $this-> Session ->getUser();
 		//-->Creacion de navegador
 		switch ($usr->panel) {
 			case 'admin':
 				$panel = "subnav_admin.html";
 				break;

 			case 'user':
 				$panel = "subnav_user.html";
 				break;
 			default:
 				$panel = "subnav_user.html";
 				break;
 		}
 		$active.="-active";
 		$this->tpt->setBase('admin/partials/' . $panel);
 		$object = 
 		[
			"principal" => self::$ModuleBase . "/admin/index",
			"usuarios"  => self::$ModuleBase . "/admin/usuarios", 
			"reportes"  => self::$ModuleBase . "/reportes/listar", 
			"productos" => self::$ModuleBase . "/producto/listar", 
			"ventas"    => self::$ModuleBase . "/ventas/listar", 
			"ticket"    => self::$ModuleBase . "/ticket/listar", 
			$active     => "active",
 		];
 		$this-> tpt ->parse($object);
 		return $this-> tpt ->getContent();
 		//--> Fin de Creacion de navegador
 	}

 	protected  static function MessagesApp($accion='',$message = ''){
 		switch ($accion) {
 			case 'error':
 				return'<p class="alert alert-danger">'.$message.'</p>';
 				break;
 			case 'info':
 				return'<p class="alert alert-info">'.$message.'</p>';
 				break;
 			case 'warning':
 				return'<p class="alert alert-warning">'.$message.'</p>';
 				break;
 			case 'success':
 				return'<p class="alert alert-success">'.$message.'</p>';
 				break;
 			default:
 				return''.$message.'';
 				break;
 		}
 	}
 	
}

?>