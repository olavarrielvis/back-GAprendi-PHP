<?php
	Ruta::get("home","Home@index");

	Ruta::get("idiomas","ControlLeccion@idiomas");
	Ruta::get("idioma","ControlLeccion@idioma");
	Ruta::get("niveles","ControlLeccion@niveles");
	Ruta::get("lecciones","ControlLeccion@lecciones");
	Ruta::get("buscar","ControlLeccion@buscar");
	Ruta::get("palabras","ControlLeccion@palabras");
	Ruta::post("login","ControlLeccion@login");
	Ruta::get("usuario","ControlLeccion@usuario");
	Ruta::get("usuarioAvance","ControlLeccion@usuarioAvance");
	Ruta::post("registro","ControlLeccion@registrate");
	Ruta::post("editUsuario","ControlLeccion@editUsuario");
	Ruta::post("editUsuarioContrasena","ControlLeccion@editUsuarioContrasena");
	Ruta::post("updateAvance","ControlLeccion@actualizarIdiomaUsuario");
	Ruta::get("recoverPass","ControlLeccion@recuperarPassword");
	Ruta::get("enviarCorreo","ControlLeccion@enviarCorreo");
	Ruta::post("updatePassCodigo","ControlLeccion@updatePassCodigo");