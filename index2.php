<?php 
	require "app/controller/controller.php";
	$mvc = new controller();

	if (empty($_GET['action'])) {
		$_GET['action'] = "home";
	}
	if ($_GET["action"]=="home") {
		$mvc->home();
	}
	elseif($_GET['action']=="login"){
		$mvc->pageLogin();
	}
	else
		$mvc->error();

 ?>