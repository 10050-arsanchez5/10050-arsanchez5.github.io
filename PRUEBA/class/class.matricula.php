<?php
class matricula{
	private $id;
	private $placa;
	private $marca;
	private $anio;
	private $color;
	private $avaluo;

	private $fecha;
	private $agencia;
	private $anio2;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR VEHICULO<br><br>";
	}
		
	
	private function _get_combo_agencia($tabla,$valor,$valor2,$nombre){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$valor2 FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= '<option value="' . $row[$valor] . '">' . $row[$valor2] .'</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

	/*private function _get_combo_anio($nombre,$anio_inicial){
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= '<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}*/
private function _get_combo_anio($nombre, $anio_inicial) {
    $html = '<select name="' . $nombre . '">';

    // Obtener los años ya utilizados
    $sql = "SELECT DISTINCT anio FROM matricula";
    $result = $this->con->query($sql);
    $anios_usados = array();
    while ($row = $result->fetch_assoc()) {
        $anios_usados[] = $row['anio'];
    }

    $anio_actual = date('Y');
    for ($i = $anio_inicial; $i <= $anio_actual; $i++) {
        // Agregar el elemento de la lista solo si el año no se ha utilizado antes
        if (!in_array($i, $anios_usados)) {
            $html .= '<option value="' . $i . '">' . $i . '</option>' . "\n";
        }
    }

    $html .= '</select>';
    return $html;
}



	public function save_matricula(){
		
		$this->fecha = $_POST['fecha'];
		$this->id = $_POST['id'];
		$this->agencia = $_POST['agencia'];
		$this->anio2 = $_POST['anio2'];

		$sql = "INSERT INTO matricula VALUES(NULL,
											'$this->fecha',
											'$this->id',
											'$this->agencia',
											'$this->anio2');";
			
		//$validacion = "SELECT * FROM matricula WHERE vehiculo='$this->id'";
$validacion = "SELECT * FROM matricula WHERE vehiculo='$this->id' AND anio='$this->anio2' AND agencia='$this->agencia'";

		$res = $this->con->query($validacion);
		$row = $res->fetch_assoc();

		$carro = $row['vehiculo'];
		$anio_matriculado = $row['anio'];
		$agenc = $row['agencia'];
if($carro == $this->id && ($anio_matriculado == $this->anio2 || $agenc == $this->agencia)){
    echo $this->_message_error("No puede matricular dos veces el mismo vehiculo en un mismo anio o una misma agencia.");
} else {
    if($this->con->query($sql)){
        echo $this->_message_ok("guardó");
    } else {
        echo $this->_message_error("guardar");
    }	
}
									
										
	}
//************************************* PARTE II ****************************************************	

	public function get_form($id){

			$sql = "SELECT * FROM vehiculo WHERE id='$id'";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de matricular el vehiculo con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   

			$this->placa = $row['placa'];
			$flag = "disabled";
			$op = "matricular";
			}
				
		
		$html = ' 
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th colspan="2">DATOS DEL VEHICULO</th>
			</tr>
			
			<tr>
				<td>Placa: </td>
				<td>'. $row['placa'] .'</td>
			</tr>
			<tr>
				<td>Marca: </td>
				<td>'. $row['marca'] .'</td>
			</tr>
			<tr>
				<td>Motor: </td>
				<td>'. $row['motor'] .'</td>
			</tr>
			<tr>
				<td>Chasis: </td>
				<td>'. $row['chasis'] .'</td>
			</tr>
			<tr>
				<td>Combustible: </td>
				<td>'. $row['combustible'] .'</td>
			</tr>
			<tr>
				<td>Anio: </td>
				<td>'. $row['anio'] .'</td>
			</tr>
			<tr>
				<td>Color: </td>
				<td>'. $row['color'] .'</td>
			</tr>
			<tr>
				<td>Avalúo: </td>
				<th>$'. $row['avaluo'] .' USD</th>
			</tr>
			<tr>
				<td>Valor Matrícula: </td>
				<th>$'. $this->_calculo_matricula($row['avaluo']) .' USD</th>
			</tr>			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
			</tr>	
			</div>
		
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>		
		<form name="Form_vehiculo" method="POST" action="matriculacion.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table table-striped">
				<tr>
					<th colspan="2">DATOS DE MATRICULA</th>
				</tr>
				<tr>
					<td>Placa:</td>
					<td><input type="text" size="6" name="placa" value="' . $this->placa . '" '. $flag .'></td>
				</tr>
				<tr>
					<td>Fecha:</td>
					<td><input type="date" size="6" name="fecha"  required></td>
				</tr>
				<tr>
					<td>Agencia:</td>
					<td>' . $this->_get_combo_agencia("agencia","id","descripcion","agencia") . '</td>
				</tr>	
				<tr>
					<td>Anio:</td>
					<td>' . $this->_get_combo_anio("anio2",2000, $this->id) . '</td>
				</tr>
				<tr>
					<th class="text-center" colspan="2"><input type="submit" name="Guardar_Matricula" value="Guardar" class="btn btn-primary"></th>
					<th class="text-center" colspan="2"><a class="btn btn-danger" href="matriculacion.php">Cancelar</a></th>
				</tr>										
			</table></div></div>';
		return $html;
	}
	
	

	public function get_list_matricula(){
		$html = '
		<div class="container">
			 <div class="row justify-content-md-center">
			 <br>
		<table border="0" align="center" class="table table-striped">
			<tr>
				<th colspan="8" class="text-center">Lista de Vehículos</th>
			</tr>
			<tr class="text-center">
				<th>Placa</th>
				<th>Marca</th>
				<th>Color</th>
				<th>Año</th>
				<th>Avalúo</th>
				<th>Accion</th>
			</tr></div></div>';
		$sql = "SELECT v.id, v.placa, m.descripcion as marca, c.descripcion as color, v.anio, v.avaluo  FROM vehiculo v, color c, marca m WHERE v.marca=m.id AND v.color=c.id;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_matri = "matri/" . $row['id'];
			$d_matri_final = base64_encode($d_matri);				
			$html .= '
				<tr class="text-center">
					<td>' . $row['placa'] . '</td>
					<td>' . $row['marca'] . '</td>
					<td>' . $row['color'] . '</td>
					<td>' . $row['anio'] . '</td>
					<td>' . $row['avaluo'] . '</td>
					<td><a class="btn btn-primary"  href="matriculacion.php?d=' . $d_matri_final . '">Matricular</a></td>
					</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	public function get_list_matriculados(){
		$html = '
		<br>
		<table border="0" align="center" class="table table-striped table-hover" >
			<tr>
				<th colspan="8" class="text-center">Lista de Vehículos Matriculados</th>
			</tr>
			<tr class="text-center">
				<th>Fecha</th>
				<th>Vehiculo</th>
				<th>Agencia</th>
				<th>Año</th>
			</tr>';
		
		/*$sql2 = "SELECT v.id, v.placa, m.vehiculo AS datos_vehiculo, a.id, a.descripcion, m.agencia AS datos_agencia FROM vehiculo v, matricula m, agencia a WHERE m.vehiculo=v.id AND m.agencia=a.id;";*/

		$sql = "SELECT * FROM matricula";
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			//$d_matri = "matri/" . $row['id'];
			//$d_matri_final = base64_encode($d_matri);	
			$html .= '
				<tr class="text-center">
					<td>' . $row['fecha'] . '</td>
					<td>' . $row['vehiculo'] . '</td>
					<td>' . $row['agencia'] . '</td>
					<td>' . $row['anio'] . '</td>
					</tr>';
		}
		$html .= '  
		</table>
		<center>
		<a class="btn btn-primary" href="index.html">Regresar</a></center>';
		
		return $html;
		
	}

	
//*************************************************************************	
	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	

//*************************************************************************	
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a class="btn btn-danger" href="matriculacion.php">Cancelar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a class="btn btn-danger" href="matriculacion.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

