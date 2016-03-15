<?php 
	class controller{

		public function content(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/view/contenido.html");
			} else
				header('Location: /tzotzil/');
		}

		public function panel2(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/view/auscultacion.html");
			} else
				header('Location: /tzotzil/');
		}

		public function error(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/view/404.shtml");
			} else
				header('Location: /tzotzil/');
		}
	}

 ?>