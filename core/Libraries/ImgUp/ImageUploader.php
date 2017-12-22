<?php

namespace Core\Libraries\ImgUp;

class ImageUploader
{

 private $ruta;
 private $thumb;
 private $imgRes;


  /**
   * Class Constructor
   * @param    $ruta   
   * @param    $thumb   
   * @param    $imgRes   
   */
  public function __construct($ruta, $thumb = 170, $imgRes = 300)
  {
    $this->ruta = $ruta;
    $this->thumb = $thumb;
    $this->imgRes = $imgRes;
  }

public function  delete($arch = ''){
    $archivo = $arch;
    $directorio = $this->ruta;
    unlink($directorio.'/'.$archivo);         
}

public function  upload($img ,$img_error){
        $allowedExts = array("jpg", "jpeg", "gif", "png", "JPG", "GIF", "PNG");
        $extension = end(explode(".", $img["name"]));
        if ((($img["type"] == "image/gif")
                || ($img["type"] == "image/jpeg")
                || ($img["type"] == "image/png")
                || ($img["type"] == "image/pjpeg"))
                && in_array($extension, $allowedExts)) {
            // el archivo es un JPG/GIF/PNG, entonces...
            
            $extension = end(explode('.', $img['name']));
            $foto = substr(md5(uniqid(rand())),0,10).".".$extension;
            $directorio = $this->ruta; // directorio de tu elección
            
            // almacenar imagen en el servidor
            move_uploaded_file($img['tmp_name'], $directorio.'/'.$foto);
            $minFoto = 'min_'.$foto;
            $resFoto = 'res_'.$foto;
            $this->resizeImagen($directorio.'/', $foto, $this->thumb, $this->thumb,$minFoto,$extension);
            $this->resizeImagen($directorio.'/', $foto, $this->imgRes, $this->imgRes,$resFoto,$extension);
            unlink($directorio.'/'.$foto);
            return $foto;
  }else
  {
    return $img_error;
  }

}

private function resizeImagen($ruta, $nombre, $alto, $ancho,$nombreN,$extension){
    $rutaImagenOriginal = $ruta.$nombre;
    if($extension == 'GIF' || $extension == 'gif'){
    $img_original = imagecreatefromgif($rutaImagenOriginal);
    }
    if($extension == 'jpg' || $extension == 'JPG'){
    $img_original = imagecreatefromjpeg($rutaImagenOriginal);
    }
    if($extension == 'png' || $extension == 'PNG'){
    $img_original = imagecreatefrompng($rutaImagenOriginal);
    }
    $max_ancho = $ancho;
    $max_alto = $alto;
    list($ancho,$alto)=getimagesize($rutaImagenOriginal);
    $x_ratio = $max_ancho / $ancho;
    $y_ratio = $max_alto / $alto;
    if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho 
    $ancho_final = $ancho;
    $alto_final = $alto;
  } elseif (($x_ratio * $alto) < $max_alto){
    $alto_final = ceil($x_ratio * $alto);
    $ancho_final = $max_ancho;
  } else{
    $ancho_final = ceil($y_ratio * $ancho);
    $alto_final = $max_alto;
  }
    $tmp=imagecreatetruecolor($ancho_final,$alto_final);
    imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
    imagedestroy($img_original);
    $calidad=70;
    imagejpeg($tmp,$ruta.$nombreN,$calidad);
    
}

}
?>
