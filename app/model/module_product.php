<?php 
/**
 * 
 */
use Core\DataBase\crud;
use Core\DataBase\DB;

 class Module_product extends crud
 {
 	
 	protected $table = "tienda_productos";

 	public function __construct()
 	{
 		# code...
 	}
 	
 	public  function insert()
 	{
 	}

 	public function listAnios(){
 		$sql="SELECT
			YEAR(fecha) as anio
			FROM
			tienda_ventas
			GROUP BY
			anio
			ORDER BY
			anio DESC";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	public function listarFolios(){
 		$sql = "SELECT
				tienda_ventas.id,
				UPPER(tienda_ventas.folio) as folio,
				tienda_ventas.vendedor,
				Sum(tienda_ventas.precio) as precio,
				tienda_ventas.pago
				FROM tienda_ventas
				WHERE LEFT(fecha, 10) = CURDATE()
				GROUP BY
				tienda_ventas.folio
				ORDER BY
				tienda_ventas.fecha DESC ";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	public function reporteDiario($fecha = ''){
 		$sql = "SELECT
				tienda_ventas.id,
				UPPER(tienda_ventas.folio) as folio,
				tienda_ventas.vendedor,
				Sum(tienda_ventas.precio) as precio,
				tienda_ventas.pago
				FROM tienda_ventas
				WHERE LEFT(fecha, 10) = :fecha
				GROUP BY
				tienda_ventas.folio
				ORDER BY
				tienda_ventas.fecha DESC ";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(':fecha', $fecha);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	public function reportes($mes = '' , $anio = ''){
 		$sql = "SELECT
				YEAR(fecha) AS anio,
				month(fecha) AS mes,
				DAY(fecha) as dia,
				SUM(tienda_ventas.precio) as total_mes,
				LEFT(fecha, 10) as fecha
				FROM
				tienda_ventas
				where 
				YEAR(fecha)=:anio and month(fecha)=:mes
				GROUP BY
				dia
				";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(':anio', $anio);
		$stmt->bindParam(':mes', $mes);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	public function findFolio($folio = ''){
 		$sql  = "SELECT * FROM tienda_ventas WHERE folio = :folio";
 		$stmt = DB::prepare($sql);
 		$stmt->bindParam(':folio', $folio);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	public function update($id=0, $img_upload ="")
 	{
		$sql  = "UPDATE $this->table SET img_upload = :img_upload  WHERE id = :id";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(':img_upload', $img_upload);
		$stmt->bindParam(':id', $id);
		return $stmt->execute();
 	}

 	public function  getListaProducto(){
 		$sql  = "SELECT * FROM tienda_lista_producto";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	 public function  getListaPresentacion(){
 		$sql  = "SELECT * FROM tienda_presentacion_lista";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
 	}

	public function  getProductos($value = "", $inicio = 0 , $TAMANO_PAGINA = 1){
		if ($value == "")
			{
			$sql = "SELECT * FROM $this->table  ORDER BY id DESC LIMIT :inicio , :tamano ";
			$stmt = DB::prepare($sql);
			$stmt->bindParam(':inicio', $inicio ,PDO::PARAM_INT);
			$stmt->bindParam(':tamano', $TAMANO_PAGINA,PDO::PARAM_INT);
			}else 
				{
				$sql = "SELECT * FROM $this->table WHERE tipo = :tipo  ORDER BY id DESC LIMIT :inicio , :tamano ";
				$stmt = DB::prepare($sql);
				$stmt->bindParam(':tipo', $value);
				$stmt->bindParam(':inicio', $inicio ,PDO::PARAM_INT);
				$stmt->bindParam(':tamano', $TAMANO_PAGINA,PDO::PARAM_INT);
				}
		$stmt->execute();
		return $stmt->fetchAll();
 	}

	public function  getProductosAll($value = "", $inicio = 0 , $TAMANO_PAGINA = 1){
		if ($value == "")
			{
			$sql = "SELECT * FROM $this->table  ORDER BY id DESC";
			$stmt = DB::prepare($sql);
			}else 
				{
				$sql = "SELECT * FROM $this->table WHERE tipo = :tipo  ORDER BY id DESC";
				$stmt = DB::prepare($sql);
				$stmt->bindParam(':tipo', $value);
				}
		$stmt->execute();
		return $stmt->fetchAll();
 	}

 	public  function updateColum($id = 0, $colum = '', $dato = ''){
 		$sql  = "UPDATE $this->table SET $colum = :$colum  WHERE id = :id";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(":$colum", $dato, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id , PDO::PARAM_INT);
		return $stmt->execute();
 	}

 	public function  getNumRows($value = ""){
		if ($value == "")
			{
			$sql  = "SELECT * FROM $this->table";
			$stmt = DB::prepare($sql);
			}else 
				{
	 			$sql  = "SELECT * FROM $this->table WHERE tipo = :tipo";
				$stmt = DB::prepare($sql);
				$stmt->bindParam(':tipo', $value);
				}
		$stmt->execute();
		return $stmt -> rowCount();
 	}

 	public function insertVentas($datafields = array() , $data = array()){
 		DB::transaction(); // also helps speed up your inserts.
		$insert_values = array();
		foreach($data as $d){
		    $question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
		    $insert_values = array_merge($insert_values, array_values($d));
		}

		$sql = "INSERT INTO tienda_ventas (" . implode(",", $datafields ) . ") VALUES " . implode(',', $question_marks);

		$stmt = DB::prepare ($sql);
		try {
		    $stmt->execute($insert_values);
		} catch (PDOException $e){
		    echo $e->getMessage();
		}
		DB::commit();
 	}



public function placeholders($text, $count=0, $separator=","){
    $result = array();
    if($count > 0){
        for($x=0; $x<$count; $x++){
            $result[] = $text;
        }
    }

    return implode($separator, $result);
}

 } ?>
