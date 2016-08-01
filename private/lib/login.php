<?php
require '../includes/functions.php';

//Se extraen los datos recibidos por POST y se almacenan en variables con el mismo nombre
extract($_POST);

//Se compureba que el usuario y la contraseña hayan sid enviados
if ( ( $txtUserName != "" ) && ( $txtPassword != "" ) ) {
	//se consulta si el usuario y la contraseña son validos en la base de datos. 
	//vw_login es una vista en la base de datos que devuelve todos los datos necesarios para el inicio de sesion
	$user = $db->get("vw_login", "*", ["AND" => 
								[
								"userName" => $txtUserName,
								"password" => $txtPassword,
								]
							]);

	//se comprueba si la consulta devolvio registros
	if ( ! $user ) { //si no consiguio ninguna coincidencia
		
		//se almacena la direccion ip del usuario en la variable
		$ipAddress = !empty( $_SERVER["REMOTE_ADDR"] ) ? $_SERVER["REMOTE_ADDR"] : "";
		
		//se inserta en la tabla ipaddressaccess la direccion ip del usuario que intento hacer login
		$ipAddressAccess = $db->insert("ipaddressaccess", ["ipAddress" => $ipAddress, "#date" => "NOW()"]);

		//se consulta por el nombre de usuario para saber si el usuario esta en la base de datos
		$user = $db->get("vw_login", "*", ["userName" => $txtUserName]);
		
		//se comprueba si la consulta devolvio registros
		if ( $user ) { //si consiguio alguna coincidencia
			
			//se valida si el usuario esta bloqueado
			if ( ( $user["status"] == "Bloqueado" ) || ( $user["status"] == "Locked" ) ) { // 
				
				//se redirecciona al login indicando mensaje de error
				header("location: ../../public/?lang=" . $_GET["lang"] . "&e=2");
			
			} else { //si no consiguio ninguna coincidencia

				if ( $user["failedAttempts"] >= 2 ) { //si el usuario tiene 2 o mas intentos fallidos
					
					//se incrementan los intentos fallidos en +1 y se cambia el estatus a bloqueado
					$users = $db->update("users", ["failedAttempts[+]" => 1, "idStatus" => 2], ["userName" => $txtUserName]);
					//se redirecciona al login indicando mensaje de error
					header("location: ../../public/?lang=" . $_GET["lang"] . "&e=2");

				} else { //si el usuario tiene menos de 2 intentos fallidos

					//se incrementan los intentos fallidos en +1					
					$users = $db->update("users", ["failedAttempts[+]" => 1], ["userName" => $txtUserName]);
					//se redirecciona al login indicando mensaje de error
					header("location: ../../public/?lang=" . $_GET["lang"] . "&e=1");

				}

			}

		}

	} else { //si consiguio alguna coincidencia
		
		if ( ( $user["status"] == "Activo" ) || ( $user["status"] == "Active" ) ) { // Se valida que el usuario este activo

			//se resetea el conteo de intentos fallidos al usuario
			$users = $db->update("users", ["failedAttempts" => 0], ["userName" => $txtUserName]);

			//se procede al login
			//se guardan los datos necesarios del usuario en una session
			session_start();
			$_SESSION["user"]["id"] = $user["id"];
			$_SESSION["user"]["userName"] = $user["userName"];
			$_SESSION["user"]["firstName"] = $user["firstName"];
			$_SESSION["user"]["lastName"] = $user["lastName"];
			$_SESSION["user"]["workEmail"] = $user["workEmail"];
			$_SESSION["user"]["privileges"] = $user["privileges"];
			$_SESSION["user"]["lastChangePassword"] = $user["lastChangePassword"];
			$_SESSION["user"]["daysToChangePassword"] = $user["daysToChangePassword"];
			$_SESSION["user"]["nextChangePassword"] = $user["nextChangePassword"];
			$_SESSION["user"]["idCompany"] = $user["idCompany"];
			$_SESSION["user"]["companyName"] = $user["companyName"];
			$_SESSION["user"]["idCountry"] = $user["idCountry"];
			$_SESSION["user"]["countryName"] = $user["countryName"];
			$_SESSION["user"]["lang"] = $user["countryLang"];

			//se valida si el usuario necesita cambiar su contraseña
			if ( $_SESSION["user"]["nextChangePassword"] <= date("Y-m-d") ) {// si necesita cambiarla

				//se redirecciona al cambio de contraseña
				header("location: ../userProfile/changePassword.php");

			} else { // si no necesita cambiar su contraseña
				
				//se redirecciona al dashboard del sistema
				header("location: ../dashboard.php");

			}

		} else if ( ( $user["status"] == "Bloqueado" ) || ( $user["status"] == "Locked" ) ) { // se valida que el usuario este bloqueado
		
			//se redirecciona al login indicando mensaje de error
			header("location: ../../public/?lang=" . $_GET["lang"] . "&e=2");
		
		} else if ( ( $user["status"] == "Vacaciones" ) || ( $user["status"] == "Vacations" ) ) { // se valida que el usuario este en vacaciones
		
			//se redirecciona al login indicando mensaje de error
			header("location: ../../public/?lang=" . $_GET["lang"] . "&e=3");
		
		} else if ( ( $user["status"] == "Inactivo" ) || ( $user["status"] == "Inactive" ) ) { // se valida que el usuario este inactivo
		
			//se redirecciona al login indicando mensaje de error
			header("location: ../../public/?lang=" . $_GET["lang"] . "&e=4");
		
		}
		
	}

}