<?php 
/**
 * 
 */
use Core\layout_library\Template;
use Core\Libraries\ImgUp\ImageUploader;
use App\controller\panel\interface_users;


 class Ventas extends Interface_users
 {
 	/**
 	 * [self::$urlBase base para las redirecciones]
 	 * @var string
 	 */
 	protected static $urlBase = '?panel/ventas';

 	function __construct($ind)
 	{
 		$this->__loadModel('module_product'); #acceso con la referencia model
 		$this->tpt = new Template;
 		$this->__loadSession(); #acceso con el nombre de Session
 		$this->Session->_SessionAccess( [1,2] , "?welcome/index");#Solo aceptar en toda la clase nivel de session 1
 		parent::__construct($ind); #Obligatorio hacer referencia
 	}

 	 public function index()
 	{
		$this->listar();
 	}

 	/**
	 * Listado y paginacion de todos los productos
	 * @param  string  $producto Producto a listar
	 * @param  string  $option   Opcion para inicializar
	 * @return Null
	 */
 	public function listar($producto="", $option = '',$pagina = 1 ){
 		$TAMANO_PAGINA = 100; 
 		switch ($option){
			case 'success':
				$inicio = 0;
				$message = $this -> MessagesApp($option, "Producto agregado correctamente");
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
 		$data['subnav'] = $this->subnav("ventas");
 	    $this->tpt->setBase('admin/ventas/ventas.html');
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
	 		"baseUrlCart" =>self::$urlBase."/agregar::",
	 		"shopcart" =>self::$urlBase."/shopcart",
 	    	"total_cart" => $this->cookie_exist(),
 	    	"url-dir" =>$ls = (empty($producto)) ? 'Productos[listar]<br>Cat[Todos]' :'<a href="'.self::$urlBase."/listar".'">Productos[listar]</a><br>Cat['.$producto .']',
 			"pagination" => $this->pagination($this->model->getNumRows($producto)
 															,$TAMANO_PAGINA,$pagina),
 		];
 		$this -> tpt -> parse($object);
 		$this -> tpt -> parse(["tipo"=>$producto]);
 		$data['content'] = $this -> tpt -> getContent();
 	    $this::HTML('admin/layout',$data);
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
 		$data['subnav'] = $this->subnav("ventas");
 		$this -> tpt -> setBase('admin/producto/ver.html');
 		$product =  $this->model->find($id);
 	    $this-> tpt ->parse($product);
 	    $object = 
	 		[
	 			"baseUrl" => self::$urlBase,
	 			"message" => $this -> MessagesApp($opcion, $message),
	 			"cancelar"=> self::$urlBase."/listar",
	 			"editar"  => self::$urlBase."/editproducto::".$id,
	 			"editarSelect"  => "style='display:none;'",
	 			"url-dir" => "<a href='".self::$urlBase."/listar"."'>Productos[listar]<br></a>",
	 		];
	 		$this -> tpt -> parse($object);
 		$data['content'] = $this -> tpt -> getContent();
 	    $this::HTML('admin/layout',$data);
	}
	
	public  function agregar($indice = 0 , $cant = 0 ){
		var_dump($_COOKIE);
		if ($dato = $this->model->find($indice))
		{
			if (isset($_COOKIE["shop_cart"][$indice]) ){
				$str = $_COOKIE["shop_cart"][$indice];
				$data = explode("$",$str);
				if ($dato->bodega >= ( $cantidad = $data[0] + $cant) ){
					setcookie("shop_cart[$indice]" , $this->addCart($dato,$cantidad,($dato->precio * $cantidad)) );
					$this::GO(self::$urlBase."/listar::::edit-add");
				}	
				else
				$this::GO(self::$urlBase."/listar::::error-insufficient");
			}else{
				if ($dato->bodega >=$cant){
					setcookie("shop_cart[$indice]" , $this->addCart($dato,$cant,($dato->precio * $cant)) );
					$this::GO(self::$urlBase."/listar::::success-add");
				}
				else 
				{
					$this::GO(self::$urlBase."/listar::::error-insufficient");
				}
			}
		}
		else 
		$this::GO(self::$urlBase."/listar::::error-product");
	}

	public function shopCart(){
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("ventas");
 		$data['content'] = $this->formCart();
 	    $this::HTML('admin/layout',$data);
	}

	public function venta($folio = ''){
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("ticket");
 		$data['content'] = $this->contentFolio($folio);
 	    $this::HTML('admin/layout',$data);
	}

	private function  contentFolio($folio = ''){
		$this->tpt->setBase('admin/ventas/venta_folio.html');
		if ($tik = $this->model->findFolio($folio)){
				$this->tpt->render_regex("listProd",$tik);
				$data =array_pop($tik); 
				$Ob = 
				[
					"script" => file_get_contents('assets/js/total.js'),
					"folio" =>$folio,
					"pago" =>$data->pago,
					"fecha" => $data->fecha,
					"message" =>'',
					"vendedor"=>$data->vendedor,
					"folios" => "?panel/ticket/listar",
					"ventas" => self::$urlBase . "/listar",
					"imprimir" => "?panel/ticket/imprimir::",
					"titulo" => "INFORMACIÓN DE VENTA",
					"url-dir" => "<a href='?panel/ticket/listar'>Listado de ticket</a><br> [".date("d-m-Y")."]",
				];}else {
							$Ob = 
							[
							"titulo" =>"ERROR",
							"script" => "",
							"message" =>$this->MessagesApp('error',"<strong>Error: </strong> no existe folio o ha sido eliminado"),
							];
						}
		$this->tpt->parse($Ob);
		return  $this->tpt->getContent();
	}


	public function comprar(){
		if (isset($_POST)){
			$data = array();
			$date = date('Y-m-d H:i:s',time());
			$folio = md5($date);
			$pago = $_POST['pago'];
			unset($_POST['pago']);
			foreach ($_POST as $key =>$val){
				$fracment = explode("-" , $key);
				$id = $fracment[0];
				$cantidad = $fracment[1];
				$product = $this->model->find($id);
				$user = $this->Session->getUser();
				$data[] = [
						  "folio" => $folio 
						  ,"vendedor" => $user->name
						  ,"fecha" => $date 
						  ,"nombre" => $product->nombre 
						  ,"cantidad" => $cantidad 
						  , "precio" =>$val
						  , "pago" =>$pago
						];
				$this->model->updateColum($id, "bodega" , ($product->bodega) - $cantidad );
			}
			$datafields =["folio","vendedor","fecha","nombre","cantidad","precio","pago"];
			$this->model->insertVentas($datafields,$data);
			$this->delete_all(true, $folio);
		}
		else 
		{
			$this::GO(self::$urlBase . '/shopcart');
		}
	}
	private function addCart($dato = null , $cantidad = 0 , $precio = 0 ){
		$this ->tpt->setBase('admin/ventas/addCart.html');
 	    $this->tpt->parse($dato);
 	    $Ob =
 	    [
 	     "cantidad" => $cantidad,
 	     "precio_total"   => $precio,
 	    ];
 	    $this->tpt->parse($Ob);
 	    return $this ->tpt->getContent();
	}

	private function formCart(){
		$this->tpt->setBase('admin/ventas/formCart.html');
		$content = "";
		if (isset($_COOKIE['shop_cart']))
		{

			foreach ($_COOKIE['shop_cart'] as $key => $value)
			{
				$str = explode("$$", $value);
				$content .= $str[1];
			}
			$enabled_cart = "enabled=''";
			$comprar = self::$urlBase . "/comprar";
			$script = file_get_contents('assets/js/total.js');
			$eliminar = self::$urlBase . "/delete_all";
		}
		else 
		{
			$content .= "<tr><td colspan='7'>Sin compra registrada</td></tr>"; 
			$enabled_cart = "disabled=''";
			$comprar ="#";
			$script = '';
			$eliminar = "#";
		}
		$cart = [
		"contentcart" => $content , 
		"delete_cookie" => self::$urlBase . "/delete_cart::",
 	    "cancelar" => self::$urlBase . "/listar",
 	    "eliminar" => $eliminar,
 	    "enabled_cart" => $enabled_cart,
 	    "comprar" => $comprar,
 	    "script" => $script,
 	    "message" =>"",
 	    "url-dir" => "<a href='".self::$urlBase."/listar'>Listado</a><br>Carrito "
		];
		$this->tpt->parse($cart);
		return $this->tpt->getContent();
	}

	private function cookie_exist(){
		if (isset($_COOKIE['shop_cart']))
		{
			return count($_COOKIE['shop_cart']);
		}
		else 
		{
			return 0;
		}
	}

	public  function show(){
		echo $this->cookie_exist();
		d($_COOKIE);
	}


	public  function delete_all($compra = false , $folio){
		if (isset($_COOKIE['shop_cart'])){
			foreach ($_COOKIE['shop_cart'] as $key => $value)
			{
				setcookie("shop_cart[$key]","", time()-3600);
			}
			if ($compra)
				$this::GO(self::$urlBase . "/venta::".$folio);
			else
				$this::GO(self::$urlBase . "/shopcart::error");
		}
		else 
		{
			$this::GO(self::$urlBase . "/shopcart::error");
		}
	}

	public  function delete_cart($indice = 0 ){
		if (setcookie("shop_cart[$indice]", "", time()-3600))
		{
			$this::GO(self::$urlBase . "/shopcart::success");
		}
		else 
		{
			$this::GO(self::$urlBase . "/shopcart::error");
		}
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

 } 
/*=====  End of Section  ======*/
 ?>