<?php 
	require "app/controller/controller.php";
	$mvc = new controller();

	if (empty($_GET['action'])) {	
		$_GET['action'] = "login";
	}

	if ($_GET["action"]=="login") {
		$mvc->pageLogin();
	}

	elseif($_GET['action']=="main"){
		$mvc->main();
	}
	elseif($_GET['action']=="config"){
		$mvc->settings();
	}
	elseif($_GET['action']=="reports"){
		$mvc->reports();
	}
	else{
		$mvc->error();
	}

 ?>