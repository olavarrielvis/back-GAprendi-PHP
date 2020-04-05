<?php
require_once(APP_PATH."modelos/Leccion.php");
class ControlLeccion extends Valida{
	private $leccion;
	
	function __construct(){
		parent::__construct();
		$this->leccion = new Leccion;
	}

	function idiomas(){
		$idiomas = $this->leccion->idiomas();
		return $idiomas;
	}

	function idioma(){
		$b=Form::getValue("idioma");
		$idiomas = $this->leccion->idioma("id=$b");
		return $idiomas;
	}

	function niveles(){
		$b=Form::getValue("idioma");
		$niveles = $this->leccion->niveles($b);
		return $niveles;
	}

	function lecciones(){
		$b=Form::getValue("idioma");
		$lecciones = $this->leccion->lecciones_diccionario($b);
		return $lecciones;
	}

	function buscar(){
		$idi=Form::getValue("idioma");
		$busq=Form::getValue("busqueda");
		$lecciones = $this->leccion->busqueda($idi,$busq);
		return $lecciones;
	}

	function palabras(){
		$idi=Form::getValue("idioma");
		$lec=Form::getValue("leccion");
		$lecciones = $this->leccion->palabras($idi,$lec);
		return $lecciones;
	}

	function login(){
		$correo=Form::getValue("correo");
		$contrasena=Form::getValue("contrasena");
		$usuario = $this->leccion->login( $correo,$contrasena );
		$a = array();
		
		$a["resp"] = true;
		if($usuario ==  null){
			$a["resp"] = false;
			
		}
		$a["usuario"] = $usuario;		
		$a["mens"] = " " . $correo . " " . $contrasena;		
		return $a;
	}



	function usuario(){
		$id=Form::getValue("id");
		$usuario = $this->leccion->usuario( $id );	
		return $usuario;
	}

	function usuarioAvance(){
		$id=Form::getValue("id");
		$usuario = $this->leccion->usuarioAvance( $id );	
		return $usuario;
	}


	function registrate(){
		$a = array();
		$resp = false;
		$usuario = null;
		$email =  Form::getValue("correo");
		$idioma =  Form::getValue("idiomaactual");

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			if($this->leccion->verificarCorreo( $email ) == null ){ 
			
				$insertResp = $this->leccion->addRegistro(
					Form::getValue("nombre"),
					Form::getValue("correo"),
					Form::getValue("contrasena"),
					Form::getValue("ubicacion"),
					Form::getValue("idiomaactual"),
					Form::getValue("imagen")
				);
				if($insertResp){
					$resp = true;
					$usuario = $this->leccion->getusuario($email);
					$ie = $this->leccion->addIdioma_Usuario( $idioma,  $usuario->id  );
					$a["mens"] = "Usuario registrado corectamente, ".$usuario->id;
		
				}else{
					$a["mens"] = "Error al insertar usuario";
				}
			}else{
				$a["mens"] = "Correo previamente registrado";
			}
		}else{
			$a["mens"] = "Correo no valido";
		}

		$a["resp"] = $resp;
		$a["usuario"] = $usuario;
		return $a;
	}

	function editUsuario(){
		$a = array();
		$resp = false;
		$edit = $this->leccion->editUsuario(
			Form::getValue("nombre"),
			Form::getValue("ubicacion"),
			Form::getValue("correo")
		);
		if($edit){
			$resp = true;
			$a["mens"] = "Datos de ".Form::getValue("correo"). "Actualizados";
		}else{
			$a["mens"] = "Error al editar usuario ".Form::getValue("correo");
		}

		$a["resp"] = $resp;
		return $a;
	}

	function editUsuarioContrasena(){
		$a = array();
		$resp = false;
		$contrasenaanterior = Form::getValue("contrasenaanterior");
		$usuario = $this->leccion->getusuario( Form::getValue("correo") );

		if($usuario->contrasena == $contrasenaanterior){
			$edit = $this->leccion->editUsuarioContrasena(
				Form::getValue("contrasena"),
				Form::getValue("correo")
			);
			if($edit){
				$resp = true;
				$a["mens"] = "Contraseña de ".Form::getValue("correo"). "Actualizados";
			}else{
				$a["mens"] = "Error al actualizar la contraseña";
			}
		}else{
			$a["mens"] = "Las Contraseñas no coinciden $usuario->contrasena  == $contrasenaanterior";
		}
		
		$a["resp"] = $resp;
		return $a;
	}


	function verifyExistCorreo($email){
		if($this->leccion->verificarCorreo( $email ) == null )
			return false;
		return true;
	}



	function recuperarPassword(){
		$a = array();
		$codigo = $this->generarCodigo(6);
		$email =  Form::getValue("correo");
		$msj = "";
		$resp = false;
		if( ! $this->verifyExistCorreo( $email ) ){ //Sino Existe el correo
			$msj = "Correo no encontrado";
		}else {
			if( ! $this->enviarCorreo( $email )){
				$msj = "No se pudo enviar el mail";
			}else{
				$update =  $this->leccion->updateCodigoUsuario ( $email , $codigo);
				if( !$update ){
					$msj = "Hubo un error en el proceso";
				}else{
					$msj = "Codigo enviado al correo";
					$resp = true;
				}
			}
		}
			
		$a["resp"] = $resp;
		$a["mens"] = $msj ." ". $email;
		return $a;
	}

	function updatePassCodigo(){
		
		$a = array();
		$email =  Form::getValue("correo");
		$codigo =  Form::getValue("codigo");
		$contrasena =  Form::getValue("contrasena");
		$msj = "";
		$resp = false;

		$getCode =  $this->obtenerCodigo( $email );

		if( ! $getCode["resp"] ){ //Si no se obtuvo el codigo
			$msj = "Codigo no localizado";
		}else {
			if( $getCode["codigo"] != $codigo ){
				$msj = "El codigo no coincide";
			}else{
				$update =  $this->leccion->updateContrasenaUsuario ( $email , $contrasena);
				if( !$update ){
					$msj = "Hubo un error en el proceso";
				}else{
					$msj = "Contraseña actualizada";
					$resp = true;
				}
			}
		}
			
		$a["resp"] = $resp;
		$a["mens"] = $msj ." ". $email;
		return $a;
	}

	function obtenerCodigo($correo){
		$x = $this->leccion->getCodigo($correo);
		if($x != null)
			return [ "resp"=>true, "codigo" => $x->codigo ];
		return [ "resp"=>false, "codigo"=> null];
	}

	


	function actualizarIdiomaUsuario(){
		$a = array();
		$update = $this->leccion->updateIdioma_Usuario(
			Form::getValue("idioma"),
			Form::getValue("usuario"),
			Form::getValue("nivel"),
			Form::getValue("leccion"),
			Form::getValue("puntos")
		);

		if($update){
			$a["resp"] = true;
			$a["mens"] = "Actualizacion de Avance corectamente, ".$update;

		}else{
			$a["resp"] = false;
			$a["mens"] = "Error al actualzar usuario_idioma ".$update;
		}
	
		return $a;
	}


	function prueba(){
		$a = array();
		$a["resp"] = true;
		$correo = Form::getValue("correo");
		$usuario = $this->leccion->getUsuario($correo);
		$imp = $usuario->id;
		$a["mens"] = "Error al insertar usuario, $imp";

		return $a;
	}

	function enviarCorreo( $email ){ 
		return true;

		$to      = $email;
		$subject = 'Codigo - App Idiomas Indigenas';
		$message = 'Hola, aqui tienes tu codigo, guardala en un lugar seguro. Codigo: '.$codigo;
		$headers = 'From: olavarri.elvis@gmail.com' . "\r\n" .
			'Do not Reply' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		//return mail($to, $subject, $message, $headers);
		return true;
	}

	function generarCodigo($longitud) {
		$key = '';
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
		$max = strlen($pattern)-1;
		for($i=0;$i < $longitud;$i++) 
			$key .= $pattern{mt_rand(0,$max)};
		return $key;
	   }

	







	

	
}