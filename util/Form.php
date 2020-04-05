<?php

class Form{

	public $errores;

	public function __construct(){
		$this->errores = array();
	}

	static function letras($str){
		return (bool) preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/", $str);
	}

	static function enteros($str){
		return (bool) preg_match("/^[\-+]?[0-9]+$/", $str);
	}

	static function letras_numeros($str){
		return (bool) preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9 ]+$/", $str);
	}

	public function setRules($campo,$etiqueta,$validacion){
		$validacion = explode("|", $validacion);
		if(is_array($validacion)){
			foreach ($validacion as $v) {
				$g = static::getValue($campo);
				if($g != null){
					$msj = $this->validar($g,$etiqueta,$v);
					if(strlen($msj) > 0)
						$this->errores[] = $msj;
				}else
					$this->errores[] = "El campo $campo no existe";
			}

			$this->errores = array_unique($this->errores);

		}
	}

	static function getValue($campo,$slash=true,$sc_html=true){
		if(isset($_REQUEST[$campo])){
			$valor = trim($_REQUEST[$campo]);
			if($sc_html) $valor = htmlspecialchars($valor); //scapar codigo html
			if($slash) $valor = addslashes($valor); //scapar comillas y diagonales
			return $valor;
		}
		return null;
	}
	//max[5]
	public function validar($g,$etiqueta,$v){
		switch ($v) {
			case "required":
				if(strlen($g) == 0)
					return "El campo $etiqueta es requerido";	
			break;
			case "letras":
				if(!static::letras($g))
					return "El campo $etiqueta debe contener letras";
			break;
			case "enteros":
				if(!static::enteros($g))
					return "El campo $etiqueta solo debe contener numeros enteros";
			break;
			default:
				// "required|enteros|max[20]|lon[10]"
				$extrae = substr($v, 0,3);
				if(strtolower($extrae) == "max"){
					$nu = (int) static::match($v);
					if($g > $nu)
						return "El campo $etiqueta debe ser menor o igual a $nu";
				}
				//min[5]
				if(strtolower($extrae) == "min"){
					$nu = (int) static::match($v);
					if($g < $nu)
						return "El campo $etiqueta debe ser mayor o igual a $nu";
				}
				if(strtolower($extrae) == "lon"){
					$nu = (int) static::match($v);
					if(strlen($g) >= $nu)
						return "El campo $etiqueta su longitud maxima es $nu caracteres";
				}

			break;
		}

		return "";
	}

	private static function match($v){
		preg_match_all("/\[([^\]]*)\]/", $v, $m);
		
		$m = $m[1][0];
		return $m;
	}
}