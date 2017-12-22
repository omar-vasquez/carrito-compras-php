<?php 
/**
 * 
 */
	use core\system\controller;
	use Core\layout_library\Template;
	use App\controller\panel\interface_users;

 class User extends Interface_users
 {
 	/**
 	 * [self::$urlBase base para las redirecciones]
 	 * @var string
 	 */
 	protected static $urlBase = '?panel/user';

 	function __construct($ind)
 	{
 		$this->__loadModel('User_control'); #acceso con la referencia model
 		$this->tpt = new Template;
 		$this->__loadSession(); #acceso con el nombre de Session
 		$this->Session->_SessionAccess( [2] , "?welcome/index");#Solo aceptar en toda la clase nivel de session 1
 		parent::__construct($ind); #Obligatorio hacer referencia
 	}

 	 	public function index()
 	{
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("principal");
 		$data['content'] = "content";
 	    $this::HTML('admin/layout',$data);
 	}

 	


 } 
/*=====  End of Section  ======*/
 ?>