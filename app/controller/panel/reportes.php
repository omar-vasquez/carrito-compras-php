<?php 
/**
 * 
 */
use Core\layout_library\Template;
use App\controller\panel\interface_users;

class Reportes extends Interface_users
 {
 	/**
 	 * [self::$urlBase base para las redirecciones]
 	 * @var string
 	 */
 	protected static $urlBase = '?panel/reportes';

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

	public function venta($folio = ''){
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("reportes");
 		$data['content'] = $this->contentFolio($folio);
 	    $this::HTML('admin/layout',$data);
	}

	private function  contentFolio($folio = ''){
		$this->tpt->setBase('admin/reportes/ver_folio.html');
		if ($tik = $this->model->findFolio($folio)){
				$this->tpt->render_regex("listProd",$tik);
				$data =array_pop($tik);
				$fech = substr($data->fecha, 0, 10);
				$Ob = 
				[
					"titulo" => "Lista de venta",
					"script" => file_get_contents('assets/js/total.js'),
					"folio" =>$folio,
					"pago" =>$data->pago,
					"fecha" => $data->fecha,
					"message" =>'',
					"vendedor"=>$data->vendedor,
					"folios" => self::$urlBase."/reportediario::".$fech,
					"ventas" => self::$urlBase . "/listar",
					"imprimir" => "?panel/ticket/imprimir::",
					"urlCanvas" => "<a href='".self::$urlBase."/listar'>Reportes mensuales</a>
					<br><a href='".self::$urlBase."/reportediario::".$fech."'>Reporte por día</a><br>Reporte por folio",
				];}else {
							$Ob = 
							[
							"script" => "",
							"message" =>$this->MessagesApp('error',"<strong>Error: </strong> no existe folio o ha sido eliminado"),
							];
						}
		$this->tpt->parse($Ob);
		return  $this->tpt->getContent();
	}
	public function listar($MES = '' , $ANIO = ''){
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("reportes");
 		$data['content'] = $this->listarFolios($MES, $ANIO);
 	    $this::HTML('admin/layout',$data);
	}

	public function reportediario($fecha = ''){
		$data['nav'] = $this->nav();
 		$data['subnav'] = $this->subnav("reportes");
 		$data['content'] = $this->reporte($fecha);
 	    $this::HTML('admin/layout',$data);
	}

	private function reporte($fecha='')
	{
		$this->tpt->setBase("admin/reportes/reporte.html");
		$this->tpt->render_regex("listProd",$this->model->reporteDiario($fecha));
		$ob = 
		[
			"script" => file_get_contents('assets/js/total.js'),
			"verfolio" => self::$urlBase ."/venta::",
			"message" => "",
			"fecha_listado" => $fecha,
			"titulo" => "Reportes por dia",
			"urlCanvas" => "<a href='".self::$urlBase."/listar'>Reportes mensuales</a><br>Reporte por día",
		];
		$this->tpt->parse($ob);
		return $this->tpt->getContent();
	}


	private function listarFolios($MES = '' , $ANIO = ''){
		$meses =["ENE" => 1 , "FEB" => 2 , "MAR" => 3 , "ABR" => 4 , "MAY" => 5 , "JUN" => 6 ,
				 "JUL" => 7 , "AGO" => 8 , "SEP" => 9 , "OCT" => 10 , "NOV" => 11 , "DIC" => 12  ];
		if (empty($MES)){
			$MES = date("m");
			$ANIO = date("Y");
			$num = str_replace("0","",$MES);
			foreach ($meses as $key => $value)
				{if ($value == $num) { $BOTON = $key;} }
		}
		else 
		{
			$BOTON = $MES;
			$MES = $meses[$MES];
			$ANIO = $ANIO;
		}
		$this->tpt->setBase("admin/reportes/listar.html");
		$this->tpt->render_regex("listAnios",$this->model->listAnios());
		$this->tpt->render_regex("listProd",$this->model->reportes($MES,$ANIO));
		$ob = 
		[
			"script" => file_get_contents('assets/js/total.js'),
			"verfolio" => "?panel/ventas/venta::",
			"message" => "",
			"baseUrl" => self::$urlBase . "/listar::",
			$BOTON => "active",
			$ANIO."-active" => "selected",
			"urlReporte" => self::$urlBase . "/reportediario::",
			"titulo"=> "Reportes mensuales",
			"urlCanvas" => "Reportes mensuales"
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