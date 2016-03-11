<?php 
class conexion{
	protected $mysqli;
	public function __construct(){
		$this->mysqli = new mysqli("localhost","root","qaz","base_tramas");

		if ($this->mysqli->connect_errno) {
			echo "Falló conexión con servidor MySQL, llamar a su proveedor de base de datos ".
			$this->mysqli->connect_error;
			return;
		}

		$this->mysqli->set_charset("utf-8");
	}
}
 ?>