<?php
session_start();
require '../includes/functions.php';

switch ($_GET["op"]) {

	case 'changePassword':
		
		//Se extraen los datos recibidos por POST y se almacenan en variables con el mismo nombre
		extract($_POST);
		// se valida que las contraseñas recibidas sean iguales
		if ( $txtNewPassword == $txtReNewPassword ) {// si son iguales

			//se valida que tenga entre 6 y 16 caracteres
			if ( ( strlen( $txtNewPassword ) < 6) || ( strlen( $txtNewPassword ) > 16) ) { //si no esta en rango

				// se redirecciona y se envia mensaje de error
				header("location: changePassword.php?e=2");

			//se valida que tenga al menos un numero
			} else if ( !preg_match('`[0-9]`', $txtNewPassword ) ) { // si no tiene un numero

				// se redirecciona y se envia mensaje de error
				header("location: changePassword.php?e=3");

			} else { // si todo esta bien

				// se valida si los dias para cambiar la contraseña es 0, osea es valida por un año 
				if ( $_SESSION["user"]["daysToChangePassword"] == 0 ) { // valida por un año

					// se crea la proxima fecha de cambio de clave
					$nextChangePassword = date( "Y-m-d h:i:s", strtotime( "+1 year" ) ); 

				} else { //valida por x cantidad de dias

					// se crea la proxima fecha de cambio de clave
					$nextChangePassword = date( "Y-m-d h:i:s", strtotime( "+1 day" ) );

				}

				//se actualizan los campos en la base de datos 
				$password = $db->update("users", 
											[
												"password" => $txtNewPassword,
												"#lastChangePassword" => "NOW()",
												"nextChangePassword" => $nextChangePassword
											], 
											["id" => $_SESSION["user"]["id"]]);

				// se redirecciona al dashboard del sistema
				header("location: ../dashboard.php");

			}

		} else { // si no coinciden las contraseñas

			// se redireccina y se envia mensaje de error
			header("location: changePassword.php?e=1");

		}

		break;
	
	default:
		# code...
		break;
}
