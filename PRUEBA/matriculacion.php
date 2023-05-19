<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Matriculas Vehículos</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
	<?php
	    include_once("constantes.php");
		require_once("class/class.matricula.php");
		
		$cn = conectar();
		$mt = new matricula($cn);
		//vehiculo::MetodoEstatico();
		
		
    // Codigo necesario para realizar pruebas.
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "matri"){
				echo $mt->get_form($id);
            }
		}else{
		
			if(isset($_POST['Guardar_Matricula']) && $_POST['op']=="matricular"){
				$mt->save_matricula();
			}else{
				echo $mt->get_list_matricula();
				echo $mt->get_list_matriculados();
			}	
		}
		
		
//*******************************************************
		function conectar(){
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
			}else{
				echo "<div class='container'>
						<nav class='navbar navbar-expand-lg navbar-dark bg-primary'>
						<div class='container-fluid'>
						<a class='navbar-brand' href='index.html'>Home</a>
						<a class='navbar-brand' href='vehiculo.php'>Vehiculo</a>
						<a class='navbar-brand' href='agencia.php'>Agencia</a>
						<a class='navbar-brand' href='marca.php'>Marca</a>
						</div>
						</nav>
						</div>
				";
			}
			
			$c->set_charset("utf8");
			return $c;
		}
//**********************************************************
		
	
	?>	
	
<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
