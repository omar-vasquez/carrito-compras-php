<?php 
/**

	TODO:
	- @author Omar Vasquez<omar.vasquez.dev@gmail.com>
	- Diseño basico para el control de sesiones
	-Omua Framework
 */

 /*=============================================
 =            Class Auth omua framework        =
 =============================================*/

namespace Core\Auth;
class SessionAuth
{	
	private $password = "";
	private $auth     = "";
	private $params ;
	private $level    = 0;
	private $fail     = "";
	private $succces  = "";

	/**
	 * [__construct ]
	 */
	function __construct(){
		session_start();
	}

	/**
	 * [getUser Retorna la session en un objeto]
	 * @return [object] [Objeto session]
	 */
	public function getUser(){
		return (object) $_SESSION;
	}
	/**
	 * [setUser 
	 * @param string $user original
	 */
	public function setUser($user='')
	{
		$this -> auth = $user;
	}

	/**
	 * [setPassword 
	 * @param string $pass original
	 */
	public function setPassword($pass='')
	{
		$this -> password = $pass;
	}

	/**
	 * @param  array - Params session
	 * @return [type]
	 */
	public function params($params=array())
	{
		$this -> params = $params;
	}

	/**
	 * [loginFail URL if not access
	 * @param  string $dir URL
	 * @return 
	 */
	public function loginFail($dir='')
	{
		$this ->fail = $dir; 
	}

	/**
	 * [loginSucces URL ACESS
	 * @param  string $dir URL
	 * @return 
	 */
	public function loginSucces($dir='')
	{
		$this -> succces  = $dir;
	}

	/**
	 * [sessionActive check the current session
	 * @param  string $redirect URL
	 * @return
	 */
	public function sessionActive($redirect='')
	{
		if 	(!empty($_SESSION["__auth_session_user_9384hfrod"]))
			header("Location: ". $redirect);
	}

	/**
	 * @param  string     user-database
	 * @param  string password-database
	 * @param  integer   level-session
	 * @param  boolean     Control de redireccion
	 * @return [boolean]       session
	 */
	public function login($user = '', $password = '' , $level = 0 , $redirect=false)
	{
	  if ($this -> auth   == $user 
      	&& $this -> password == $password)
	    {
	    //Inicializar las cabeceras de sesion
	      $_SESSION["__auth_session_user_9384hfrod"] =true;
	      $_SESSION['__level_access_f3d9d'] = $level;
	      //Comprovar si hay parametros para crear 
		      foreach ($this -> params as $key => $value) 
		      {
			      $_SESSION[$key] = $value;
		      }
		      //comprovar si el usuarios necesita una redirección
		      if ($redirect)
		      	 header("Location: " . $this -> succces);
		      else 
		       	 return true;
	    }
	    else 
	    {
	    //Comprovar si el usuario necesita una redicción
	    //o simplemente recibir un falso
	     if ($redirect)
	     	 header("Location: " . $this -> fail);
		 else 	
		 	return false;
	    }
	}

	/**
	 * [logout description]
	 * @param  string $redirect [redirecction logout]
	 * @return [null]           [description]
	 */
	public function logout($redirect = 'index.php')
	{
		$_SESSION = array();
		// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
		// Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}

		// Finalmente, destruir la sesión.
		session_destroy();
		header("Location: ".$redirect);
	}

	/**
	 * _SessionLevel description
	 * @param  integer $level    [level of access allowed
	 * @param  string  $redirect [redirect if the level does not match
	 * @return boolean           [true if access
	 */
	public function _SessionAccess($level = array() , $redirect = "" )
	{
	 $level_acces = false;

	   if (isset($_SESSION['__auth_session_user_9384hfrod']))
	    {
	    	foreach ($level as  $value) 
	    	{
	    		//comprovarel nivel para ver
	    		// si se autoriza ver el contenido
			 	if ( $_SESSION['__level_access_f3d9d'] == $value) 
			 		$level_acces = true;
			 }

			 //comprueva las concidencias de acceso
			 if ($level_acces) 
	   		 {
	   		 	return true;
	   		 }
	   		 else
	   		 {
	   		 	//Comprueba que la cadena no este vacia
	   		 	//para dedirigar y simplemente cortar el proceso
	   		 	if (strlen($redirect)>0) {
	   		 		header("Location:" . $redirect);
	   		 	}else{
				    echo 'ACCESS___ denied - level'; 
				    exit();
	   		 	}
	   		 }

	 	}else if (strlen($redirect)>0) 
	   		header("Location:" . $redirect);
	   	else
	 		return false;
	 
	}

}
   
 ?>