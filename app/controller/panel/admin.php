<?php 
/**
 * 
 */
use Core\System\Controller;
use Core\layout_library\Template;
use App\controller\panel\interface_users;

 class Admin extends Interface_users
 {
 	/**
 	 * [self::$urlBase base para las redirecciones]
 	 * @var string
 	 */
 	protected static $urlBase = '?panel/admin';

 	function __construct($ind)
 	{
 		$this->__loadModel('User_control'); #acceso con la referencia model
 		$this->tpt = new Template;
 		$this->__loadSession(); #acceso con el nombre de Session
 		$this->Session->_SessionAccess( [1] , "?welcome/index");#Solo aceptar en toda la clase nivel de session 1
 		parent::__construct($ind); #Obligatorio hacer referencia
 	}

 	public function index()
 	{
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("principal");
 		$data['content'] = "content";
 	    $this::HTML('admin/layout',$data);
 	}


  	 public function usuarios($message="")
 	{	
 		switch ($message) {
 			case 'error_mail':
 				$message=$this->MessagesApp("warning","Correo ya registrado");
 				break;
 			case 'success':
 				$message=$this->MessagesApp("info","Usuario creado correctamente");
 				break;
 			case 'delete_success':
 				$message=$this->MessagesApp("success","Usuario eliminado correctamente");
 				break;
 			case 'success_edit':
 				$message=$this->MessagesApp("success","Usuario editado correctamente");
 				break;
 			default:
 				$message = $this->MessagesApp();
 				break;
 		}
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("usuarios");
 		$this->tpt->setBase('admin/content_admin/usuarios.html');
 	    $contenido=$this->model->findAll();
 	    $this->tpt->render_regex("listuser",$contenido);
 	    $msn = $this->MessagesApp($message);
 	    $object = 
 		[
 			"baseurl" => self::$urlBase,
 			"message" => $message,
 		];
 		$this-> tpt ->parse($object);
 	    $data["content"] = $this-> tpt ->getContent();
 	    $this::HTML('admin/layout',$data);
 	}


 	  public function edituser($id = 0)
	 {

 	    $data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("usuarios");
 		$this->tpt->setBase('admin/content_admin/edit_user.html');
 	    $contenido=$this->model->findAll();
 	    $this->tpt->render_regex("listuser",$contenido);
 	    $object = 
 		[
 			"baseurl" => self::$urlBase,
 			"message" => '',
 			"cancelar" => self::$urlBase ."/usuarios",
 		];
 		$this-> tpt ->parse($object);
 		$user =  $this->model->find($id);
 	    $this-> tpt ->parse($user);
 	    $data["content"] = $this-> tpt ->getContent();
 	    $this::HTML('admin/layout',$data);
	 }

 	 public function smartCreate($id=null)
	 {
	 	if (isset($_POST)) {
		 	if (isset($_POST['create'])){
		 	if (!$this->model->userMail($_POST['email'])){
	 			 	$this->model->smartCrud($_POST,$id);
		 			$this::GO(self::$urlBase . "/usuarios::success");
	 				}
	 			 	else {
	 			 		$this::GO(self::$urlBase . "/usuarios::error_mail");
	 			 	}
		 	}
		 	else{
		 	$this->model->smartCrud($_POST,$id);
		 	$this::GO(self::$urlBase . "/usuarios::success_edit");
		 	}
	 	}else $this::GO(self::$urlBase . "/usuarios::error");
	 }

	  public function deleteuser($id)
 	{
 		if ($this->model->delete($id))
 		{$this::GO(self::$urlBase . '/usuarios::delete_success');}
 		else {
 			$this::GO(self::$urlBase . '/usuarios::error');
 		}
 	}

 	public function nav(){
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

 
 } 
/*=====  End of Section  ======*/
 ?>