<?php
require_once(APP_PATH."modelos/Usuario.php");
class ControlUsuario extends Valida{

	function login(){
		$form = new Form;
		$form->setRules("us","Usuario","required");
		$form->setRules("pass","Contraseña","required");

		$arreglo = array();

		if(count($form->errores) > 0 ){
			$arreglo["error"] = 1;
			$arreglo["errores"] = $form->errores;
		}else{
			$us = new Usuario;
			$u = $us->mostrar("us = '".Form::getValue("us")."'");
			if(count($u) > 0){
				$u = $u[0];
				$pass = Form::getValue("pass");
				if(password_verify($pass,$u->pass)){
					$arreglo["error"] = 0;
					$arreglo["id"] = base64_encode($u->idUs);
					$arreglo["token"] = password_hash($arreglo["id"].date("d-m-Y:h:g:s"),PASSWORD_DEFAULT);
					$us->setToken($u->idUs,$arreglo["token"]);
				}else{
					$arreglo["error"] = 1;
					$arreglo["msj"] = "Contraseña incorrecta";
				}
			}else{
				$arreglo["error"] = 1;
				$arreglo["msj"] = "Usuario no encontrado";
			}

		}

		return json_encode($arreglo);
	}
}