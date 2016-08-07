<?php
	require_once "./inc/dbc.inc.php";
	require_once "./inc/Listen.php";

	if(!isset($_GET['id']) || (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])))
	{
		header("Location: /"); // User wird auf die Loginseite geleitet
		die();
	}

	if(isset($_POST['delete']))
	{
		$removeCatQry = $dbc->prepare("DELETE FROM tbl_kategorien WHERE Kategorie_ID = :id");
		$removeCatQry->bindParam(":id", $_GET["id"]);
		$removeCatQry->execute();
	
		//Wenn eine Kategorie gelöscht wird sollen alle URLs aus dieser Kategorie in die Kategorie "Andere" (id: 0) verschoben werden
		$updateAllLinks = $dbc->prepare("UPDATE tbl_urls SET Kategorie_NR = 0 WHERE Kategorie_NR = :id");
		$updateAllLinks->bindParam(":id", $_GET["id"]);
		$updateAllLinks->execute();

		header("Location: admin.php?delete=cat");
		die();
	}
	else if(isset($_POST['edit']))
	{
		$updateCatQry = $dbc->prepare("UPDATE tbl_kategorien SET
											Kategorie_Bezeichnung = :catBez 
										WHERE Kategorie_ID = :id");
		$updateCatQry->bindParam(":catBez", $_POST['Bezeichnung']);
		$updateCatQry->bindParam(":id", $_GET["id"]);
		$updateCatQry->execute();
		
		$alert = "<script>$.notify('Änderungen erfolgreich gespeichert', 'success');</script>";
	}

	$cat = getCatById($dbc, $_GET['id']);
?>
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<title>Bearbeitung</title>
		<link rel="stylesheet" href="stylesheet.css">
		<script src="js/jquery-1.12.3.min.js"></script>
		<script src="js/notify.min.js"></script> <!-- Notifications (zB. beim Einloggen) -->
		<title>Kategorie bearbeiten</title>
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
										<td>Bezeichnung</td>
											<?php 	
												echo "<td><input type='text' name='Bezeichnung' value='".$cat['Kategorie_Bezeichnung']."'></td>";
											?>	
									</tr>
							</table>
							<br><br>
							<input class="btn btn-small" type = "submit" name="delete" value = "L&ouml;schen">&nbsp&nbsp&nbsp
							<input class="btn btn-small" type = "submit" name="edit" value = "Speichern">&nbsp&nbsp&nbsp
						</form>
						<form action="admin.php" class="inlineForm">
							<input type="submit" class="btn btn-small" value="Zur&uuml;ck">
						</form>
					

					</div>
				
			<br>
		</div>	
	</body>