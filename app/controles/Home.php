<?php
	class Home{
		public function index(){
			//$form = new Form;
			//$form->setRules("nom","Nombre","required|letras");
			//$form->setRules("x","Edad","required|enteros|max[30]|min[0]");
			/*

			if($form->run()){

			}else*/
			echo password_hash("123456",PASSWORD_DEFAULT);
			//var_dump($form->errores);
			//echo "Hola mundo";
		}

		public function mision(){
			echo "Misión de prueba<br>
				<form method='delete' action='../home'>
				<button type='submit'>Enviar</button>
				</form>
			";
		}
		public function bd(){
			new DB();
		}
	}