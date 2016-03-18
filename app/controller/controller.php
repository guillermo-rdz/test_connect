<?php 
	class controller{


		public function pageLogin(){
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

		/*public function error(){
			header('Location: /test_connect/');
		}*/

	}

 ?>