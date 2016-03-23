<?php 
	require_once "conexion.php";

	class model extends conexion{

		public function _construct(){
			parent:: _construct();
		}

		public function login(){
			$login = $_POST['login'];
			$token = $_POST['token'];
			if ($login == "Valid") {
				session_start();
				$_SESSION['token'] = $token;
				$_SESSION['conectado']=true;
				echo $_SESSION['token'];
			} 
			else {
				echo "Usuario y contraseña equivocados";
			}
		}

		public function sessionToken(){
			session_start();
			echo $_SESSION['token'];
		}

		public function logout(){
			session_start();
			session_unset();
			session_destroy();
		}

	}

	$instance = new model();
	if ($_POST['type']=="login") {
		$instance->login();
	}
	elseif ($_POST['type']=="token") {
		$instance->sessionToken();
	}
	elseif ($_POST['type']=="logout") {
		$instance->logout();
	}
	else{
		echo "Error al llamar a la funcion";
	}
	
 ?>