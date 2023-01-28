<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mi examen</title>
</head>

<body>
	<?php 
		
		//la parte de conexión.....
		include_once('./adodb5/adodb.inc.php');
	
		$conn = NewADOConnection('mysqli');
	
		$db_host = "localhost";
		$db_user = "root";
		$db_pass = "";
		$db_nombre = "pruebas";
		
		$conn->connect($db_host,$db_user,$db_pass,$db_nombre);
		$conn->setCharset('utf8');
		//......
		
		if(empty($_POST)){//Aqui muestramos el primer formulario......
			?>
			<center>
				<h1>Elige Opción</h1>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table >
					<tr>
						<td><input type="submit" value="Mostrar registros" name="most_reg"/></td>
						<td><input type="submit" value="Añadir registros" name="ania_reg"/></td>
						<td><input type="submit" value="Borrar registros" name="borr_reg"/></td>
						<td><input type="submit" value="Modificar registros" name="mod_reg"/></td>
					</tr>
				</table>
			</form>
			</center>	
	<?php
		///...........
		} 
		/// esa es la parte serve para añader registros ......
		if(isset($_POST["ania_reg"]) && !empty($_POST)){
		?>
		<center>
			<h1>Insertar Datos</h1>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<table>
				<tr>
					<td><label for="nombre">Nombre<label></td>
					<td><input type="text" name="nombre" id="nombre" maxlength="75" required/></td>
				<tr>
				<tr>
					<td><label for="dni">DNI<label></td>
					<td><input type="text" name="dni" id="dni" maxlength="9" reguired/></td>
				<tr>
				<tr>
					<td><label for="fechnac">Fecha de nacimiento<label></td>
					<td><input type="date" name="fechnac" id="fechnac" required/></td>
				<tr>
				<tr>
					<td><label for="tele">Teléfono<label></td>
					<td><input type="text" name="tele" id="tele" maxlength="13" required/></td>
				<tr>
				<tr>
					<td><label for="mail">E-Mail<label></td>
					<td><input type="email" name="mail" id="mail" maxlength="75" required/></td>
				<tr>
				<tr>
					<td><label for="etnia">Etnia<label></td>
					<td>
						<select name="etnia" id="etnia">
						<option value="" hidden>Elige etnia</option>
						<?php
							$sql = 'SELECT * FROM  etnias';
							$res =  $conn->execute($sql);
							if($res){
								foreach($res as $clave=>$valor){
						?>
						<option value="<?php echo $valor["cod_etnia"];?>"><?php echo $valor["nombre"];?></option>
						<?php
							}
								$res = null;unset($res);
								$con = null; unset($con);
							}
						?>
						</select>
					</td>
				<tr>
				<tr>
					<td><label for="observ">Observaciones<label></td>
					<td><textarea  name="observ" id="observ"  maxlength="500" rows="5" cols="25"></textarea></td>
				<tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Guardar Datos" name="guardar_datos"></td>
				</tr>
			</table>
		</form>
		
	<?php
		echo "<br/><a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
		echo "</center>";
		///..............
			
		}
		if(isset($_POST["guardar_datos"]) && !empty($_POST)){//aquí recivimos los datos que vienen del formulario para guardarlos en la base de datos
			
			extract($_POST,EXTR_OVERWRITE);
			
			$sql = "INSERT INTO personas(nombre, dni, fechnac, telefono, email, etnia, observaciones) VALUES ('$nombre','$dni','$fechnac','$tele','$mail','$etnia','$observ')";
			
			$res =  $conn->execute($sql);
			if($res){
				echo "<p>Has insertado los datos correctamente</p>";
				echo "<a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
			}
			else{
				echo "<p>Error en ensertar los datos</p>";
				echo "<a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
			}
			$res = null;unset($res);
			$con = null; unset($con);
		}
		///........
		if(isset($_POST["most_reg"]) && !empty($_POST)){//este código para mostrar nuestros datos que estan en  la tabla  personas en la base de datos pruebas
			
			$sql ='SELECT * FROM personas';
			$res = $conn->execute($sql);
			if($res){
				echo "<center>"."<h1>Los datos</h1>";
				echo "<table border='4px'>";
				echo "<tr><th>Código</th><th>Nombre</th><th>Teléfono</th><th>Fecha</th><th>NIE</th><th>E-Mail</th><th>Etnia</th><th>Observaciones</th></tr>";
					while($r = $res->fetchRow()){
						echo "<tr>";
						for($i=0; $i<8;$i++){ 
							echo "<td>".$r[$i]."</td>";
						}  
					  	echo "<tr>";
					}
				echo "</table>";
				echo "<br/><a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
				echo"</center>";
			}
				$res = null;unset($res);
				$con = null; unset($con);
		}
	// esta parte para borrar.....
		if(isset($_POST ["borr_reg"]) && !empty($_POST)){
				$sql ="SELECT * FROM personas";
				$res = $conn->execute($sql);
			
			if($res){	
				echo "<center>"."<h1>Los datos para borrar</h1>";
				echo "<table border='4px'>";
				echo "<tr><th>Código</th><th>Nombre</th><th>Teléfono</th><th>Fecha</th><th>NIE</th><th>E-Mail</th><th>Etnia</th><th>Observaciones</th><th>Borra aquí</th></tr>";
					while($r = $res->fetchRow()){
						echo "<tr>";
						for($i=0; $i<8;$i++){ 
							echo "<td>".$r[$i]."</td>";
						}  
						echo"<td>
								<form method='post' action=".$_SERVER['PHP_SELF'].">
									<input type='hidden' value='".$r[0]."' name='codigo'>
									<input type='submit' name='confirmar_borrar' value='Delete (X)' >
								</form>
						     </td>";
						
					  	echo "<tr>";
					}
				echo "</table>";
				echo "<br/><a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
				echo "</center>";
			}
				$res = null;unset($res);
				$con = null; unset($con);
		}
	
		if(isset($_POST["confirmar_borrar"]) && !empty($_POST)){
			
			$codigo = $_POST["codigo"];
			
			$sql ="DELETE FROM personas WHERE codigo = $codigo";
			
			$res = $conn->execute($sql); 
			
			if($res ){
				echo "<p>Has borrado registro</p>";
				echo "<a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";

			}
			else{
				echo "<p>Fallo (No has borrado nada)</p>";
				echo "<a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
			}
			
			$res = null;unset($res);
			$con = null; unset($con);
		
		}
	
		///.......
	///esta parte para hacer modificaciones 
		if(isset($_POST ["mod_reg"]) && !empty($_POST)){
			
			$sql ="SELECT * FROM personas";
			$res = $conn->execute($sql);
			
			if($res){	
				echo "<center>"."<h1>Los datos para modificar</h1>";
				echo "<table border='4px'>";
				echo "<tr><th>Código</th><th>Nombre</th><th>Teléfono</th><th>Fecha</th><th>NIE</th><th>E-Mail</th><th>Etnia</th><th>Observaciones</th><th>Modifica aquí</th></tr>";
					while($r = $res->fetchRow()){
						echo "<tr>";
						for($i=0; $i<8;$i++){ 
							echo "<td>".$r[$i]."</td>";
						}  
						echo"<td>
								<form method='post' action=".$_SERVER['PHP_SELF'].">
									<input type='hidden' value='".$r[0]."' name='codigoID'>
									<input type='submit' name='mostrar_modificar' value='Update * Data' >
								</form>
						     </td>";
						
					  	echo "<tr>";
					}
				echo "</table>";
				echo "<br/><a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
				echo "</center>";
			}
			$res = null;unset($res);
			$con = null; unset($con);
		}
	
	if(isset($_POST["mostrar_modificar"]) && !empty($_POST)){
		
		$codigoID = $_POST["codigoID"];
		$selected = "";
		$codigo = '';			$nombre_pers = '';
		$dni = '';				$fechnac = '';
		$telefono ='';			$email = '';
		$etnia = '';			$observaciones = '';
		$nombre_etn = '';		$cod_etnia = '';
		
		$sql1 ="SELECT * FROM personas WHERE codigo = $codigoID";
		
		$res = $conn->execute($sql1);
		
		if($res){
			while($row = $res->fetchRow()){
				//el codigo no lo voy a modificar
				$codigo = $row["codigo"];		$nombre_pers = $row["nombre"];
				$dni = $row["dni"];				$fechnac = $row["fechnac"];
				$telefono = $row["telefono"];	$email = $row["email"];
				$etnia = $row["etnia"];			$observaciones = $row["observaciones"];

			}
		}

		?>
						
		<center>
			<h1>Modificar Datos</h1>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			
					<input type="hidden" value="<?php echo $codigoID;?>" name="codigoID">
			
			<table>
				<tr>
					<td><label for="nombre">Nombre<label></td>
					<td><input type="text" name="nombre" id="nombre" value="<?php echo $nombre_pers;?>" maxlength="75" required/></td>
				<tr>
				<tr>
					<td><label for="dni">DNI<label></td>
					<td><input type="text" name="dni" id="dni" value="<?php echo $dni;?>" maxlength="9" required/></td>
				<tr>
				<tr>
					<td><label for="fechnac">Fecha de nacimiento<label></td>
					<td><input type="date" name="fechnac" id="fechnac" value="<?php echo $fechnac;?>" required/></td>
				<tr>
				<tr>
					<td><label for="tele">Teléfono<label></td>
					<td><input type="text" name="tele" id="tele"/ value="<?php echo $telefono;?>" maxlength="13" required></td>
				<tr>
				<tr>
					<td><label for="mail">E-Mail<label></td>
					<td><input type="email" name="mail" id="mail" value="<?php echo $email;?>" maxlength="75" required/></td>
				<tr>
				<tr>
					<td><label for="etnia">Etnia<label></td>
					<td>
						<select name="etnia" id="etnia">
						<option value="" hidden>Elige etnia</option>
						<?php
							$sql = 'SELECT * FROM  etnias';
							$res =  $conn->execute($sql);
							if($res){
								
								foreach($res as $clave=>$valor){
									
									if( $valor["cod_etnia"] == $etnia){
										 $selected = ' selected';
									}
						?>
						<option value="<?php echo $valor["cod_etnia"];?>" <?php echo $selected;?>>
							<?php echo $valor["nombre"];?></option>
						<?php
								}
								
							}
						?>
						</select>
					</td>
				<tr>
				<tr>
					<td><label for="observ">Observaciones<label></td>
					<td><textarea  name="observ" id="observ" maxlength="500" rows="5" cols="25"><?php echo $observaciones;?></textarea></td>
				<tr>
				<tr>
					<td></td>
					<td><input type="submit" value="guardar_modificaciones" name="guardar_modificacion"></td>
				</tr>
			</table>
		</form>
		
	<?php
		$res = null;unset($res);
		$con = null; unset($con);
		echo "<br/><a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
		echo "</center>";

	}
	
	if(isset($_POST["guardar_modificacion"]) && !empty($_POST)){
		extract($_POST,EXTR_OVERWRITE);
		
		$sql = "UPDATE personas SET nombre = '$nombre', dni = '$dni', fechnac = '$fechnac', telefono = '$tele', email = '$mail' , etnia = '$etnia', observaciones = '$observ' WHERE codigo = $codigoID";

  
		 $res =  $conn->execute($sql);
			
		  if($res){
			   echo "<p>Has modificado registros</p>";
			   echo "<a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
		   }
				else{
					echo "Error";
					echo "<a href='".$_SERVER['PHP_SELF']."'><button>Volver</button></a>";
				}
		
		$res = null;unset($res);
		$con = null; unset($con);
	
	}
	//........
	?>
</body>
</html>