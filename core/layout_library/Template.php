<?php 
/**
* 
*/

namespace Core\layout_library;
class Template
{
	private $str;
    private $dict;
	public  function setBase($base)
	{
          $this->str = 
                file_get_contents(DIR_VIEWS . "/" . $base);  

	}

    public function getContent()
    {
        return $this->str;
    }

	function render_regex($key='REGEX', $stack=array()) {
        $originalstr = $this->str;
        $match = $this->get_regex($key, False);
        $this->str = $this->get_regex($key);
        $render = '';
        foreach($stack as $dict)  $render .= $this->render($dict);
        $this->str = str_replace($match, $render, $originalstr);
        return  str_replace($match, $render, $originalstr);
        
    }
    
    function get_regex($key, $eliminar_keys=True) {
	   $regex= '<!--'.$key.'-->';
		$posInicial=strpos($this->str, $regex);
		if ($posInicial<0) return '';
		$posFinal=strpos($this->str, $regex,$posInicial + strlen($regex));
		$texto=substr($this->str,$posInicial,($posFinal+ strlen($regex))-$posInicial);
      $sinkeys = str_replace('<!--'.$key.'-->', '', $texto);
      return ($eliminar_keys) ? $sinkeys : $texto;
        
    }

    public function render($dict=array()) {
        settype($dict, 'array');
        $this->set_dict($dict);
        return str_replace(array_keys($this->dict), array_values($this->dict), $this->str);
    }

    public function parse($dict=array()) {
        settype($dict, 'array');
        $this->set_dict($dict);
        $this->str = str_replace(array_keys($this->dict), array_values($this->dict), $this->str);
    }


    protected function set_dict($dict=array()) {
        $this->sanitize($dict);
        $keys = array_keys($dict);
        $values = array_values($dict);
        foreach($keys as &$key) {
            $key = "{{$key}}";
        }
        $this->dict = array_combine($keys, $values);
    }
    
    private function sanitize(&$dict) {
        foreach($dict as $key=>&$value) {
            if(is_array($value) or is_object($value)) {
                $value = print_r($value, True);
                if(strlen($value) > 100) {
                    $value = substr($value, 0, 100) . chr(10) . "(...)";
                    $value = nl2br($value);
                }
            }
        }
    }
}

 ?>