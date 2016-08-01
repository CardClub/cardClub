<?php
if ( !isset( $_GET["lang"] ) ) {// si no existe la variable GET lang
	if ( !isset( $_COOKIE["CardClub_lang"] ) ) {// si no existe la cookie CardClub_lang
		header("location: ?lang=es"); //se redirecciona y se indica como lenguaje por defecto "es"
		$lang = "es"; // se almacena el lenguaje "es" en la variable lang
	} else { // si existe la cookie CardClub_lang
		if ( $_COOKIE["CardClub_lang"] != "" ) { // si la cookie CardClub_lang no esta vacia
			header("location: ?lang=" . $_COOKIE["CardClub_lang"]); //se redirecciona y se indica como lenguaje por defecto el escogido por el usario
			$lang = $_COOKIE["CardClub_lang"];// se almacena el lenguaje escogido por el usuario en la variable lang
		}		
	}
} else { // si existe la variable GET lang
	$lang = $_GET["lang"]; // se almacena el lenguaje recibido por GET en la variable lang
}
if ( $lang == "es" ) {
	$langDefault = "es_VE";
} else if ( $lang == "en" ) {
	$langDefault = "en_EN";
}

require '../lang/' . $langDefault . '/login.php'; // se incluye el archivo del lenguaje escogido por el usuario o por defecto
setcookie("CardClub_lang", $lang, time()+(60*60*24*365)); //se almacena en la cookie el ultimo lenguaje escogido por el usuario

$msjError = isset($_GET["e"])?$_GET["e"]:Null; //se verifica si existe un mensaje de error recibido por GET
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$l["tittle"]?> :: CardClub</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../dist/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="../dist/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/AdminLTE.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="../plugins/iCheck/square/blue.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<img src="../dist/img/logo.png" />
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
		<div class="login-languageChoice">
			<a href="?lang=es"><img src="../dist/img/es.png" title="Español" width="50" id="choiceEs"></a>
			<a href="?lang=en"><img src="../dist/img/en.jpg" title="English" width="50" id="choiceEn"></a>
		</div>
			<form action="../private/lib/login.php?lang=<?=$lang;?>" method="post">
				<div class="form-group has-feedback">
					<input type="text" name="txtUserName" id="txtUser" class="form-control" placeholder="<?=$l["ph_txtUserName"]?>">
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" name="txtPassword" id="txtPassword" placeholder="<?=$l["ph_txtPassword"]?>">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<p class="text-red"><?=isset($msjError)?$l["error" . $msjError]:"";?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="checkbox icheck">
							<label>
								<input name="chk_rememberMe" <?=( $_COOKIE["CardClub_lang"] != "" )?"checked":"";?> id="chk_rememberMe" value="1" type="checkbox"> <?=$l["rememberMe"]?>
							</label>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat"><?=$l["SignIn"]?></button>
					</div>
					<!-- /.col -->
				</div>
			</form>

			<a href="#"><?=$l["forgotPassword"]?></a><br>

		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 2.2.3 -->
	<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="../plugins/iCheck/icheck.min.js"></script>
	<script>
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
	  			increaseArea: '20%' // optional
			});
		});
	</script>
</body>
</html>
