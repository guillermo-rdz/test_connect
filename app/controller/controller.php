<?php 
	class controller{
		
		public function pageLogin(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/views/index.html");
			}
			else
				include("app/views/login.html");
		}

		public function main(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/views/index.html");
			} else
				//include("app/views/index.html");
				header('Location: /test_connect/');
		}

		public function settings(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/views/config.html");
			} else
				//include("app/views/index.html");
				header('Location: /test_connect/');
		}
		public function reports(){
			session_start();
			if (isset($_SESSION["conectado"])) {
				include("app/views/reportes.html");
			} else
				//include("app/views/index.html");
				header('Location: /test_connect/');
		}

		public function error(){
			header('Location: /test_connect/');
		}

	}

 ?>