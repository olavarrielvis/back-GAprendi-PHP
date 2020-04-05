<?php
	chdir(dirname(__DIR__));

	//cargando clases
	require_once(APP_UTIL."config.php");
	require_once(APP_UTIL."Form.php");
	require_once(APP_PATH."modelos/DB.php");
	require_once(APP_PATH."controles/Valida.php");
	require_once(APP_UTIL."App.php");
	require_once(APP_UTIL."Ruta.php");
	require_once(APP_PATH."http/rutas.php");
