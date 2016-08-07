<?php
	require_once "./inc/dbc.inc.php";
	require_once "./inc/Listen.php";
	
	if(!isset($_GET['id']) || !isset($_SESSION['userId']))
	{
		header("Location: login.php"); // User wird auf die Loginseite geleitet
		die();
	}

	$user = getUserById($dbc, $_GET['id']);

	if(!($user['Benutzer_ID'] == $_SESSION['userId'] || (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])))
	{
		header("Location: /");
		die();
	}

	if(isset($_POST['delete']))
	{
		$removeUserQry = $dbc->prepare("DELETE FROM tbl_benutzer WHERE Benutzer_ID = :id");
		$removeUserQry->bindParam(":id", $_GET["id"]);
		$removeUserQry->execute();

		//Alle zum Nutzer dazugehörigen Links entfernen.
		$removeUserLinksQry = $dbc->prepare("DELETE FROM tbl_urls WHERE Benutzer_NR = :BenNr");
		$removeUserLinksQry->bindParam(":BenNr", $_GET["id"]);
		$removeUserLinksQry->execute();

		header("Location: admin.php?delete=user");
		die();

	}
	else if(isset($_POST['edit']))
	{
		if($_POST['password'] != "")
		{
			$changePasswordQry = $dbc->prepare("UPDATE tbl_benutzer SET Benutzer_Passwort = :password WHERE Benutzer_ID = :id");
			$changePasswordQry->bindValue(":password", md5($_POST['password']));
			$changePasswordQry->bindParam(":id", $_GET["id"]);
			$changePasswordQry->execute();
		}

		$editUserQry = $dbc->prepare("UPDATE tbl_benutzer SET
											Benutzer_email = :email
										WHERE Benutzer_ID = :id");
		$editUserQry->bindParam(":email", $_POST['email']);
		$editUserQry->bindParam(":id", $_GET["id"]);
		$editUserQry->execute();

		$user = getUserById($dbc, $_GET['id']); //überarbeitete Version des Users holen.
		
		$alert = "<script>$.notify('Änderungen erfolgreich gespeichert', 'success');</script>";
	}
?>
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<title>Bearbeitung</title>
		<link rel="stylesheet" href="stylesheet.css">
		<script src="js/jquery-1.12.3.min.js"></script>
		<script src="js/notify.min.js"></script> <!-- Notifications (zB. beim Einloggen) -->
		<title>Nutzer bearbeiten</title>
	</head>
	
	<body>
	<?php if(isset($alert)) { echo $alert; } ?>
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
				<?php
					echo "Hallo, " . $_SESSION['username'] . ".";
				?>
				<a href="login.php?logout">Willst du dich abmelden?</a>
				</div>
				<!---->
					<form method="POST">
						<div id = "inhalt-table">
							<table class="demo">
									<tr class="noborder">
										<td>Name</td>
											<?php 	
													echo "<td>".$user['Benutzer_Name']."</td>";
											?>	
									</tr>
									<tr class="noborder">
										<td>Neues Passwort</td>
										<td>
										<input type="password" name="password" placeholder="Neues Passwort">
										</td>
									</tr>
									<tr class="noborder">
										<td>Email</td>
										<td>
											<input type="text" name="email" value="<?= $user['Benutzer_Email']; ?>">
										</td>
									</tr>
							</table>
							<br><br>
							<?php if($_SESSION['isAdmin']) { echo '<input class="btn btn-small" type = "submit" name="delete" value = "L&ouml;schen">&nbsp&nbsp&nbsp'; } ?>
							<input class="btn btn-small" type = "submit" name="edit" value = "Speichern">&nbsp&nbsp&nbsp
						</form>
						<?php
							if($_SESSION['isAdmin'])
							{
								echo '<form class="inlineForm" action="admin.php">';
							}
							else
							{
								echo '<form class="inlineForm" action="bereich.php">';
							}
						?>
								<input type="submit" class="btn btn-small" value="Zur&uuml;ck">
						</form>


					</div>
				
			<br>
		</div>	
	</body>