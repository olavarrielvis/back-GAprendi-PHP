<?php
	Class Leccion extends DB{	

		public function idiomas($where = 1){
			return $this->getDatos("idioma","*",$where);
		}

		public function idioma($where = 1){
			return $this->getDato("idioma","*",$where);
		}
		
		public function niveles($idioma = 1){
			$sql="SELECT n.* FROM idioma_nivel as idni, nivel as n WHERE (idni.nivel = n.id) and idioma = $idioma";
			$datos =array();
			if ($this->solicitud($sql)) {
				$result = $this->result;
				while($r = $result->fetch_object()){
					$fila = $this->getDatos("leccion","*","nivel='$r->id'");
					$r->lecciones=$fila;
					$datos[]=$r;
				}
			}
			
			return $datos;
		}

		public function lecciones_diccionario($idioma = 1){
			$where = "(idni.nivel = n.id ) and (l.nivel = n.id ) and diccionario=1 and idioma=".$idioma;
			return $this->getDatos("idioma_nivel as idni, nivel as n, leccion as l", "l.*", $where);
		}


		public function busqueda($idioma = 1,$busqueda){
			$where = "(f.id = t.frase) and (t.idioma = $idioma) and f.diccionario=1 and (f.espanol LIKE '%$busqueda%' OR t.traduccion LIKE '%$busqueda%')";
			return $this->getDatos("frase as f, traduccion as t"
			, "f.id,f.espanol,f.imagen,f.leccion,t.traduccion,t.sonido",
			 $where);
		}

		public function palabras($idioma = 1,$leccion){
			$select = "*";
			$tablas = "frase as f, traduccion as t";
			$where = "(f.id = t.frase) and (t.idioma = $idioma) and f.diccionario=1 and leccion=$leccion";
			$sql = "SELECT $select FROM $tablas WHERE $where";

			$datos =array();
			if ($this->solicitud($sql)) {
				$result = $this->result;
				while($r = $result->fetch_object()){
					$fila = $this->getDatos("distraccion","id,palabra,imagen","frase=$r->frase AND idioma=$r->idioma");
					$r->distraccion=$fila;
					$datos[]=$r;
				}
			}
			
			return $datos;
		}


		public function login($correo,$contrasena){
			$where = "correo='$correo' and contrasena='$contrasena'";
			return $this->getDato("usuario"
			, "*",
			 $where);
		}

		public function usuario($id){
			$where = "u.id='$id' AND u.idiomaactual = i.id";
			return $this->getDato("usuario as u, idioma as i", 
			"u.*, i.nombre as idioname,i.nombre2 as idioname2 , i.img as idioimg",
			 $where);
		}

		public function usuarioAvance($id){
			$where = "u.id = iu.usuario AND u.idiomaactual = iu.idioma AND u.id = $id";
			return $this->getDato("usuario as u , idioma_usuario as iu"
			, "*",
			 $where);
		}


		public function addRegistro($nombre,$correo,$contrasena,$ubicacion,$idiomaactual,$imagen){
			$usuario = [
				"nombre"=>"'$nombre'",
				"correo"=>"'$correo'",
				"contrasena"=>"'$contrasena'",
				"ubicacion"=>"'$ubicacion'",
				"idiomaactual"=>$idiomaactual,
				"imagen"=>"'$imagen'",
				"codigo"=>"''",
			];
	
			return $this->insert("usuario",$usuario);
		}

		public function editUsuario($nombre,$ubicacion,$correo){
			$x = [
				"nombre"=>"'$nombre'",
				"ubicacion"=>"'$ubicacion'"
			];
			return $this->update("usuario", $x, "correo = '$correo'" );
		}

		public function editUsuarioContrasena($contrasena,$correo){
			$x = [
				"contrasena"=>"'$contrasena'",
			];
			return $this->update("usuario", $x, "correo = '$correo'" );
		}

		
		public function addIdioma_Usuario($idioma,$usuario){
			$i_u = [
				"idioma"=>$idioma,
				"usuario"=>$usuario,
				"nivel"=>1,
				"leccion"=>1,
				"avanceleccion"=>0,
				"puntos"=>0,
			];
	
			return $this->insert("idioma_usuario",$i_u);
		}


		function verificarCorreo($correo){
			return $this->getDato("usuario", "correo", "correo='$correo'");
		}

		public function getUsuario($correo){
			return $this->getDato("usuario", "*", "correo = '$correo'");
		}


		public function getCodigo($correo){
			return $this->getDato("usuario", "codigo", "correo='$correo'");
		}


		

		public function updateIdioma_Usuario($idioma,$usuario,$nivel,$leccion,$puntos){
			$x = [
				"nivel"=>$nivel,
				"leccion"=>$leccion,
				"puntos"=>$puntos,
			];
	
			return $this->update("idioma_usuario", $x, "idioma = $idioma AND usuario = $usuario " );
		}

		public function updateCodigoUsuario($correo,$codigo){
			$x = [
				"codigo"=>"'$codigo'",
			];
			return $this->update("usuario", $x, "correo = '$correo'" );
		}



		public function updateContrasenaUsuario($correo,$contrasena){
			$x = [
				"contrasena"=>"'$contrasena'",
			];
			return $this->update("usuario", $x, "correo = '$correo'" );
		}

		


		/*
		Primera consulta
		- tabla , id
		- tabla id
		*/

	}