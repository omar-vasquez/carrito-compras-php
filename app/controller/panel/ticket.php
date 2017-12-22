<?php 
/**
 * 
 */
use Core\layout_library\Template;
use Core\Libraries\ImgUp\ImageUploader;
use App\controller\panel\interface_users;

class Ticket extends Interface_users
 {
 	/**
 	 * [self::$urlBase base para las redirecciones]
 	 * @var string
 	 */
 	protected static $urlBase = '?panel/ticket';

 	function __construct($ind)
 	{
 		$this->__loadModel('module_product'); #acceso con la referencia model
 		$this->tpt = new Template;
 		$this->__loadSession(); #acceso con el nombre de Session
 		$this->Session->_SessionAccess( [1,2] , "?welcome/index");#Solo aceptar en toda la clase nivel de session 1 y 2
 		parent::__construct($ind); #Obligatorio hacer referencia< c
 	}

 	public function index()
 	{
 		$this->listar();
	}

	public function listar(){
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("ticket");
 		$data['content'] = $this->listarFolios();
 	    $this::HTML('admin/layout',$data);
	}

	public function imprimir($folio='')
	{
		$this->tpt->setBase('admin/ticket/formato.html');
		if ($tik = $this->model->findFolio($folio)){
			$total = 0;
			foreach ($tik as $value){
				$total += $value->precio;
			}
			$this->tpt->render_regex("contenido",$tik);
				$data =array_pop($tik); 
				$Ob = 
				[
					"folio" =>$folio,
					"pago" =>$data->pago,
					"fecha" => $data->fecha,
					"vendedor"=>$data->vendedor,
					"total" => $total,
					"mensaje" =>"",
					"titulo" =>"Coperativa del Valle",
					"subtitulo" =>"Direccion aldama Desconocido",
				];
		$this->tpt->parse($Ob);
		}
	    $html2pdf = new HTML2PDF('P','A4','fr');
	    $html2pdf->WriteHTML($this->tpt->getContent());
	    $html2pdf->Output('exemple.pdf');
	}

	private function listarFolios(){
		$this->tpt->setBase("admin/ticket/listar.html");
		$this->tpt->render_regex("listProd",$this->model->listarFolios());
		$ob = 
		[
			"script" => file_get_contents('assets/js/total.js'),
			"verfolio" => "?panel/ventas/venta::",
			"message" => "",
			"titulo"  =>"VENTAS DIARIAS",
			"url-dir" => "Listado de ticket<br> [".date("d-m-Y")."]",
		];
		$this->tpt->parse($ob);
		return $this->tpt->getContent();
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