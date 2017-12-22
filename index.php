<?php
    /**
     *======= Framework MVC =========
     * @omFerito
     * Autor: José Omar Vasquez 
     * omar.vasquez.dev@gmail.com
     * @Ing. en Tics
     * ==============================
     */ 
    import('settings');
    import('vendor.autoload');
     #carpeta del controlador 
    define('DIR_NEXT' , DIR_CONTROLLER);
    // define('DIR_PAGE', BASENAME($_SERVER['QUERY_STRING']));
    //define('DIR_CLASS' , DIRNAME($_SERVER['QUERY_STRING']));


    define( 'ROOT_DIR', dirname(__FILE__) );
    define('DIR_PAGE', trim (BASENAME($_SERVER['QUERY_STRING']).PHP_EOL));
    define('DIR_CLASS' , trim (DIRNAME($_SERVER['QUERY_STRING']).PHP_EOL));
     /*
     * @DIR_NEXT se define el nombre de la carpeta del controlador
     **/
     #Autoload
     #echo  "#DIR PAGE". DIR_PAGE . " #METD" . DIR_CLASS . "#DIRNET". DIR_NEXT ."#ROOT DIR ".ROOT_DIR."<br>";

     $__controllerVar = explode('::' , DIR_PAGE);
     $__Modules = explode('/', DIR_CLASS);
     $accesIndex = trim($__controllerVar[0]);
     $accesClass = DIR_NEXT . "/" . DIR_CLASS  . ".php";
    
    if( empty($accesIndex) ){
        include( DIR_NEXT . "/". DEFAULT_VIEW . ".php");
        $default = DEFAULT_VIEW ; 
        new $default($__controllerVar);
    }
    else{
    if (file_exists($accesClass)) {
	   	require_once( $accesClass );
        $select_controller = 
                            trim( 
                                end($__Modules)
                                                );  
        try {
            new $select_controller($__controllerVar);
              } catch (Exception $e) {
                 echo $e->getMessage(), "\n";
             }
	}
    else{
	     echo "<p>FATAL ERROR: Not exist __" . end($__Modules) . ".class";
             }
    }

    # Importación rápida 
    function import($str='') {
        $file = str_replace('.', '/', $str);
        if(!file_exists("$file.php")) exit(
            "FATAL ERROR: No module named $str");
        require_once "./$file.php";
    }
 ?>