<?php 
/**
 * 
 */
use Core\layout_library\Template;
use Core\Libraries\ImgUp\ImageUploader;
use App\controller\panel\interface_users;

class Producto extends Interface_users
 {
 	/**
 	 * [self::$urlBase base para las redirecciones]
 	 * @var string
 	 */
 	protected static $urlBase = '?panel/producto';

 	function __construct($ind)
 	{
 		$this->__loadModel('module_product'); #acceso con la referencia model
 		$this->tpt = new Template;
 		$this->__loadSession(); #acceso con el nombre de Session
 		$this->Session->_SessionAccess( [1] , "?welcome/index");#Solo aceptar en toda la clase nivel de session 1 y 2
 		parent::__construct($ind); #Obligatorio hacer referencia< c
 	}

 	public function index()
 	{
 		$this->listar();
	}
	/**
	 * [nuevo Producto]
	 * @param  string $option Mensaje 
	 * @return null
	 */
	public function nuevo($option = ''){
		switch ($option){
 			case 'error':
 				$message = "Ha habido un error al crear el producto";
 			break;
 			case 'success':
 				$message = "Producto creado correctamente";
 			break;
 			default:
 				$message = "";
 			break;
 		}
		$data['nav']    = $this -> nav();
		$data['subnav'] = $this -> subnav("productos");
		$this->tpt->setBase('admin/producto/nuevo_producto.html');
    	$this->tpt->render_regex("ListaProducto",
						 			$this->model->getListaProducto() );
		$this->tpt->render_regex("ListaPresentacion",
									$this->model->getListaPresentacion() );
		$object = 
 		[
 			"form_url" => self::$urlBase."/create",
 			"nombre" => "",
 			"descripcion" => "",
 			"color" => "",
 			"precio" =>0,
 			"bodega" =>0,
 			"message" =>$this->MessagesApp($option,$message),
 			"operacion" =>"create",
	 		"cancelar"=> self::$urlBase."/listar",
	 		"url-dir" => "<a href='".self::$urlBase."/listar"."'>Listado de producto</a> Nuevo ",
	 		"titulo" => "AGREGAR",


 		];
 		$this->tpt->parse($object);
		$data['content'] = $this->tpt->getContent();
		$this::HTML('admin/layout',$data);
	}
	/**
	 * Metodo para eliminar un producto 
	 * @param  integer $id   Id del producto a eliminar
	 * @param  string  $img  Nombre de la imagen  para elimar
	 * @param  string  $tipo Tipo de producto 
	 * @return null
	 */
	public  function  delete($id = 0 , $img ='', $tipo='' ){
		$this->model->delete($id);
		$imageUploader = 
	  		new ImageUploader("assets/upload");
	  	$imageUploader->delete("res_".$img);
	  	$imageUploader->delete("min_".$img);
 		$this::GO(self::$urlBase . '/listar::'.$tipo.'::success');
	}
	/**
	 * Editar un producto
	 * @param  integer $id  Id del producto
	 * @param  string  $img Nombre de la imagen en caso de editar
	 * @return null
	 */
	public function edit($id = 0 , $img = ''){
	if ( isset($_POST) ) 
	  {
		if ($this->model->smartCrud($_POST,$id))
			{
				if ($_FILES["img_upload"]["name"]!="")
					{
					$nameimg = $this -> uploadImage($_FILES,true,$img);
					$this->model->update($id,$nameimg);
					}
				$this::GO(self::$urlBase . "/ver::$id::success");
			}
			else $this::GO(self::$urlBase . "/ver::$id::error");
	  }
		else $this::GO(self::$urlBase . "/ver::$id::error");
	}
	/**
	 * Crear un nuevo producto
	 * @param  null $id   opcion para saber que tiene que crear
	 * @return null
	 */
	public function create($id=null){
	  if (isset($_POST)) 
	  {
		if ($this->model->smartCrud($_POST,$id))
			{
	  			$r = $this->model->lastid(); 
	  			$nameimg =  "img-default.png";
				if ($_FILES["img_upload"]["name"]!="")
				$nameimg = $this -> uploadImage($_FILES); 
				$this->model->update($r->id,$nameimg);
				$this::GO(self::$urlBase . "/nuevo::success");
			}else $this::GO(self::$urlBase . "/nuevo::error");
	  }else $this::GO(self::$urlBase . "/nuevo::error");
	}
	/**
	 * Editar Producto
	 * @param  integer $id     Id del producto a mostrar 
	 * @param  string  $option Mensajes de aviso
	 * @return null          
	 */
	public function editproducto($id = 0 , $option = ''){
		switch ($option){
 			case 'error':
 				$message = "Ha habido un error al crear el producto";
 			break;
 			case 'success':
 				$message = "Producto creado correctamente";
 			break;
 			default:
 				$message = "";
 			break;
 		}
		$data['nav'] = $this->nav();
		$data['subnav'] = $this->subnav("productos");
		$this->tpt->setBase('admin/producto/nuevo_producto.html');
		$this->tpt->render_regex("ListaProducto",
								  $this->model->getListaProducto());
		$this->tpt->render_regex("ListaPresentacion",
									$this->model->getListaPresentacion() );
 	    $this->tpt->parse($this->model->find($id));
		$object = 
 		[
 			"form_url" => self::$urlBase."/edit::".$id."::".$this->model->find($id)->img_upload,
 			"message" =>$this->MessagesApp($option,$message),
 			"operacion" =>"update",
 			"cancelar" => self::$urlBase."/ver::".$id,
 			"titulo" => "EDITAR",
 			"url-dir" => "<a href='".self::$urlBase."/listar"."'>Listado de producto</a><br>Producto [".$id."]",

 		];
 		$this->tpt->parse($object);
		$data['content'] = $this-> tpt ->getContent();
		$this::HTML('admin/layout',$data);
	}

	/**
	 * Funcion para subir una imagen basada en una libreria
	 * @param  $_FILES  $files Array de file  
	 * @param  boolean $delete Comprobar si tiene que eliminarse una imagen
	 * @param  string  $img    Nombre de la imagen
	 * @return boolean         Confirmar exito
	 */
	private function  uploadImage($files, $delete = false, $img = ''){
		$res = "";
	    $imageUploader = 
	  		new ImageUploader("assets/upload");
	  	if ($delete) $imageUploader->delete($img);
	    $res = $imageUploader->upload($files["img_upload"],"img-default.png");
		return $res;
	}
	/**
	 * Visualizar los productos
	 * @param  integer $id     [description]
	 * @param  string  $opcion [description]
	 * @return [type]          [description]
	 */
	public function ver($id = 0 , $opcion = ''){
		switch ($opcion){
			case 'success':
			$message = "Bodega editada correctamente";
			break;
			default:
			$message = "";
			break;
		}
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("productos");
 		$this -> tpt -> setBase('admin/producto/ver.html');
 		$product =  $this->model->find($id);
 	    $this-> tpt ->parse($product);
 	    $object = 
	 		[
	 			"baseUrl" => self::$urlBase,
	 			"message" => $this -> MessagesApp($opcion, $message),
	 			"cancelar"=> self::$urlBase."/listar",
	 			"editar"  => self::$urlBase."/editproducto::".$id,
	 			"editarSelect"  => "",
	 			"url-dir" => "<a href='".self::$urlBase."/listar"."'>Listado de producto</a><br>",

	 		];
	 		$this -> tpt -> parse($object);
 		$data['content'] = $this -> tpt -> getContent();
 	    $this::HTML('admin/layout',$data);
	}
	/**
	 * Listado y paginacion de todos los productos
	 * @param  string  $producto Producto a listar
	 * @param  string  $option   Opcion para inicializar
	 * @param  integer $pagina   Entero para saber la pagina 
	 * @return Null
	 */
 	public function listar($producto="", $option = '',$pagina = 1){
 		$TAMANO_PAGINA = 100; 
 		switch ($option){
			case 'success':
				$inicio = 0;
				$message = $this -> MessagesApp($option, "Registro eliminado correctamente");
			break;
			case 'page':
				$inicio = ($pagina - 1) * $TAMANO_PAGINA;
				$message = "";
			break;
			default:
			$inicio = 0;
			$message = "";
			break;
		}
 		$data['nav']    = $this->nav();
 		$data['subnav'] = $this->subnav("productos");
 	    $this->tpt->setBase('admin/producto/listar_producto.html');
 		$this->tpt->render_regex("ListaProducto",
 										$this->model->getListaProducto());
 		$this->tpt->render_regex("listProd",
 										$this->model->getProductos($producto,$inicio,$TAMANO_PAGINA));
		$object = 
 		[
 			"baseUrl" => self::$urlBase."/listar",
 			"eliminarurl" => self::$urlBase."/delete::",
 			"editurl" => self::$urlBase."/editproducto::",
 			"verurl" => self::$urlBase."/ver::",
 			"nuevoproducto" => self::$urlBase."/nuevo",
 			"message" => $message,
 			"url-dir" => "<a href='".self::$urlBase."/listar"."'>Listado de producto</a> " . $producto,
 			"titulo" => "LISTA DE PRODUCTOS  ". strtoupper($producto) ,
 			"pagination" => $this->pagination($this->model->getNumRows($producto)
 															,$TAMANO_PAGINA,$pagina),
 		];
 		$this -> tpt -> parse($object);
 		$this -> tpt -> parse(["tipo"=>$producto]);
 		$data['content'] = $this -> tpt -> getContent();
 	    $this::HTML('admin/layout',$data);
 	}

 	/**
 	 * Método para crear un paginador
 	 * @param  integer $num_total_registros Numero total de registro
 	 * @param  integer $TAMANO_PAGINA       Tamaño de lista por por pagina
 	 * @param  integer $pagina              Donde iniciar la pagina
 	 * @return Html paginador
 	 */
 	private function  pagination($num_total_registros = 0, 
 									   $TAMANO_PAGINA = 0,
 									          $pagina = 0){
		//calculo el total de páginas
		$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);
		$pagination = "";
		if ($total_paginas > 1) {
		   if ($pagina != 1)
		      $pagination .= '<li><a href="'.self::$urlBase."/listar::{tipo}::page::".($pagina-1).'"><i class="icon-large icon-circle-arrow-left"></i></a></li>';
		      for ($i=1;$i<=$total_paginas;$i++) {
		         if ($pagina == $i)
		            //si muestro el índice de la página actual, no coloco enlace
		            $pagination .= '<li class="active"><a href="">'.$pagina.'</a></li>';
		         else
		            //si el índice no corresponde con la página mostrada actualmente,
		            //coloco el enlace para ir a esa página
		           $pagination .= '<li>  <a href="'.self::$urlBase."/listar::{tipo}::page::".$i.'">'.$i.'</a>  <li>';
		      }
		      if ($pagina != $total_paginas)
		         $pagination .=  '<li><a href="'.self::$urlBase."/listar::{tipo}::page::".($pagina+1).'"><i class="icon-large icon-circle-arrow-right"></i></a></li>';
		}
		return $pagination ;
 	}



 } /*/class_ed*/	
/*=====  End of Section  ======*/
 ?>