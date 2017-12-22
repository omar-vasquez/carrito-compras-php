<?php 
/**
 * 
 */
use Core\System\Controller;
use Core\layout_library\Template as template;
 class Welcome extends Controller
 {
 	
 	protected static $urlBase = '?welcome';
 	# Arquitecura inicial del constructor
 	function __construct($ind)
 	{
 		$this->tpt = new template;
 		$this->__loadSession(); #acceso con el nombre de Session
 		$this->__loadModel('User_control'); #acceso con la referencia model
 		parent::__construct($ind);

 	}

 	// #href : localhost(dominio)/?welcome/hello
 		public function index($message = "",$correo="")
 	{
 		$this->Session->sessionActive(self::$urlBase."/panel");
 		switch ($message) {
 			case 'ERRORPASS':
 				$message = 
 				 "<p class='alert'>Contraseña incorrecta</p>";
 				break;
 			case 'ERRORUSER':
 				$message = 
 				 "<p class='alert alert-danger'>Usuario no registrado</p>";
 				break;
 			default:
 				$message =  "";
 				break;
 		}
 		$this->tpt->setBase('home/partials/login.html');
 		$objeto = 
 		[
 		"message" => $message,
 		"baseURL" => self::$urlBase.'/auth',
 		"email"   => $correo,
  		];
 		$this->tpt->parse($objeto);
 		$data['content'] = $this->tpt->getContent();
 		$this::HTML("home/login",$data);
 	}

 		public function auth()
 	{	
 		/**
 		 * Comprueba si no hay variables post para redi
 		 * rigirla.
 		 */
 		if (empty($_POST)) $this::GO(self::$urlBase.'/index::failled');

 		$user = $this->model->userMail($_POST['email']);
 		if ($user) {
 			$this -> Session -> setUser($user->email);
 			$this -> Session -> setPassword($user->password);
 			$this -> Session -> loginFail(
 				self::$urlBase.'/index::ERRORPASS::' . $_POST['email']);
 			$this -> Session -> loginSucces(self::$urlBase.'/panel');
 			// Creamos los parametros en array para el inicio del session.
			$parametros_user = 
			[
			'name'     => $user->name ,
			'lastname' => $user->lastname,
			'email'    => $user->email,
			'id'       => $user->id,
			'panel'    => $user->panel
			];

			$this -> Session -> params($parametros_user);
 			$auth = $this -> Session -> login(
 										 $_POST['email']
 										,$_POST['password']
 										,$user -> level 
 										,$redirect = true);
 		}
 		else $this::GO(self::$urlBase.'/index::ERRORUSER');
 	}


 		public function panel()
 	{
 		$this->Session->_SessionAccess( [1,2] , self::$urlBase . "/index");
 		switch ($_SESSION['panel']) {
 			case 'admin':
 				$this::GO("?panel/admin/index");
 				break;
 			case 'user':
 				$this::GO("?panel/user/index");
 				break;
 			default:
 				$this::GO(self::$urlBase . "/index");
 				break;
 		}
 	}

 	public function logout()
 	{
 		$this->Session->logout($redirect = self::$urlBase . "/index");
 	}


 	/*php*
 	* ------------------------ Enviar datos ---------------------------------
 	* puedes enviar N datos a la vista por ejemplo
 	* $datos["contenido"] = "el contenido" , $datos["titulo"] 
 	* acceder como $__data["contenido"] = "el contenido" , $__data["titulo"]
 	* Para los ejemplos de CRUD y sessiones leer REAMDE.MD
 	* -----------------------------------------------------------------------
 	**/
 } ?>