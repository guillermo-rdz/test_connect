<?php 
	require_once "conexion.php";
	$login = $_POST['login'];

	class model extends conexion{

		public function _construct(){
			parent:: _construct();
		}

		public function login($login){
			if ($login == "Valid") {
				session_start();
				$_SESSION['conectado']=true;
				echo "Valido";
			} 
			else {
				echo "Usuario y contraseña equivocados";
			}
		}
	}

	$instance = new model();
	$instance->login($login);
 ?>