<?php
require_once './inc/dbc.inc.php';

if(isset($_POST['login']))
{
	require_once './inc/login-Funktion.php';
	if(login($_POST['username'], $_POST['password'], $dbc)) //Funktion login aus login-Funktion.php
	{
		header("Location: /?login=success");
	}
	else
	{
		$error = "<script>$.notify('Ungültiger Username oder ungültiges Passwort!', 'error');</script>";
	}
}
else if(isset($_GET['logout']))
{
	session_destroy();
}

if(isset($_POST['register']))
{
	require_once './inc/register-Funktion.php';
	require_once './inc/login-Funktion.php';
	if($_POST['password'] == $_POST['password2'])
	{
		register($_POST['username'], $_POST['email'], $_POST['password'], $dbc);
		if(login($_POST['username'], $_POST['password'], $dbc))
		{
			header("Location: /?login=success");
		}
		else
		{
			$error = "<script>$.notify('Ungültiger Username oder ungültiges Passwort!', 'error');</script>";
		}
	}
	else
	{
		$error = "<script>$.notify('Passwörter stimmen nicht überein', 'error');</script>";
	}
}

if(isset($_SESSION['username'])) //Überprüfung ob Nutzer eingeloggt ist.
{
	header("Location: /?login=success"); //Weiterleitung auf die Hauptseite
}
?>

<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="stylesheet.css">
		<script src="js/jquery-1.12.3.min.js"></script>
		<script src="js/notify.min.js"></script> <!-- Notifications (zB. beim Einloggen) -->
		<title>Login</title>
	</head>

		<body>
		<?php
		if(isset($error))
		{
			echo $error;
		}
		?>
			<header>
				<div id = "nav-bar-links">
					<div>
						<a href = "index.php"> <b> START &nbsp </b> </a>
						<a href = "bereich.php"> <b> MEIN BEREICH &nbsp</b> </a>
						<?php
						if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
						{
							echo '<a href = "admin.php"> <b> ADMIN &nbsp</b> </a>';
							echo '<a href = "admin_suche.php"> <b> LINKVERZEICHNIS &nbsp</b> </a>';
						}
						?>
					</div>
					
					<div style = "left: 80%;">
					<?php
					if(isset($_SESSION['username']))
					{
						echo '<a href="bereich.php"><b>Hallo, ' . $_SESSION['username'] . '</b></a>';
					}
					else
					{
						echo '<a href = "login.php"><b>ANMELDEN</b></a>';
					}
					?>
					</div>
				</div>
				
			</header>

			<div id = "inhalt">
				
				<br>
				<br>
				<div id = "ueberschrift">
					<h1><b>URL Kurz-Dienst</b></h1><br><br>
				</div>
					
					<div id="Login-form">
						
						<form method="POST">
					
							<div id="Anmelden">
								<h2><b>Anmelden</b></h2>
								
								<input type="text" name="username" placeholder="Benutzername" /><br><br>
								<input type="password" name="password" placeholder="Passwort" /><br><br>
								
								<input type="submit" id="loginBtn" name="login" class="btn" value="Anmelden" />
							</div>
							
						</form>
						
						<form method="POST">
							
							<div id="Registrieren">
								<h2><b>Registrieren</b></h2>
								
								<input type="text" name="username" placeholder="Benutzername" /><br><br>
								<input type="text" name="email" placeholder="E-Mail" /><br><br>
								<input type="password" name="password" placeholder="Passwort" /><br><br>
								<input type="password" name="password2" placeholder="Passwort erneut" /><br><br>
								
								<input type="submit" name="register" id="registerBtn" class="btn" value="Anmelden" />	
							</div>
							
						</form>
						
						
							
							<br><br><br><br>
							<br><br><br><br>
							<br><br><br><br>
							<br><br><br><br>
							<br><br><br><br>
							<br><br><br><br>
							<br><br><br><br>
								
					</div>
						
				
				<br>
			</div>
			
		</body>
</html>
