<?php 
/**
 * 
 */
use Core\DataBase\crud;
use Core\DataBase\DB;

 class User_control extends crud
 {
 	
  protected $table = 'tienda_users';

  function __construct()
  {
  	$this->title = '';
  	$this->slug = '';
  	$this->message = '';
  	$this->date = date("Y-m-d");
  }

  public function insert()
  {
     $sql  = "INSERT INTO $this->table (title, slug, message, post) VALUES (:title, :slug, :message , :post)";
      $stmt = DB::prepare($sql);
      $stmt->bindParam(':title', $this->title);
      $stmt->bindParam(':slug', $this->slug);
      $stmt->bindParam(':message',$this->message);
      $stmt->bindParam(':post',$this->date);
      return $stmt->execute(); 
  }

  public function update($id)
  {
  	$sql  = "UPDATE $this->table SET title = :title, slug = :slug, message = :message , post = :post WHERE id = :id";
      $stmt = DB::prepare($sql);
      $stmt->bindParam(':title', $this->title);
      $stmt->bindParam(':slug', $this->slug);
      $stmt->bindParam(':message',$this->message);
      $stmt->bindParam(':post',$this->date);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
  }

  public function findpost($post){
		$sql  = "SELECT * FROM $this->table WHERE slug = :slug";
		$stmt = DB::prepare($sql);
		$stmt->bindParam(':slug', $post);
		$stmt->execute();
		return $stmt->fetch();
   }

   /*----------  Subsection buscar usuarios  ----------*/
   
   public function userMail($CORREO){
   $sql  = "SELECT * FROM  $this->table  WHERE email = :email";
   $stmt = DB::prepare($sql);
   $stmt->bindParam(':email', $CORREO);
   $stmt->execute();
   return $stmt->fetch();
   }

 } ?>