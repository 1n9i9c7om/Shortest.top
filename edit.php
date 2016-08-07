<?php
	require_once "./inc/dbc.inc.php";
	require_once "./inc/Listen.php";
	
	if(!isset($_SESSION['username']))
	{
		header("Location: /login.php"); // User wird auf den Login geleitet
	}
		
		
	if(!isset($_GET['id'])) //falls keine ID übergeben wurde wird einfach auf Mein Bereich geleitet
	{
		header("Location: bereich.php");
		die();
	}
	
	$Link = getLinkById($dbc, $_GET['id']); //getLinkById führt eine Abfrage aus, welche den Datensatz aus tbl_urls als Array zurückgibt.
	
	if(!(($Link["Benutzer_NR"] == $_SESSION["userId"]) || $_SESSION["isAdmin"]))
	{
		header("Location: bereich.php");
		die();
	}
	
	if(isset($_POST['delete']))
	{
		$removeLinkQry = $dbc->prepare("DELETE FROM tbl_urls WHERE URL_ID = :id");
		$removeLinkQry->bindParam(":id", $_GET["id"]);
		$removeLinkQry->execute();
		if(isset($_GET['from']) && $_GET['from'] == "admin") //falls man vom Linkverzeichnis kommt, wird man auch wieder darauf geleitet.
		{
			header("Location: admin_suche.php?delete=url");
		}
		else
		{
			header("Location: bereich.php?delete=url");
		}
		
		die();

	}
	else if(isset($_POST['edit']))
	{
		$updateLinkQry = $dbc->prepare("UPDATE tbl_urls SET
											Kategorie_NR = :catNr, 
											URL_Beschreibung = :description
										WHERE URL_ID = :id");
		$updateLinkQry->bindParam(":catNr", $_POST['catSelect']);
		$updateLinkQry->bindParam(":description", $_POST['beschreibung']);
		$updateLinkQry->bindParam(":id", $_GET["id"]);
		$updateLinkQry->execute();

		$Link = getLinkById($dbc, $_GET['id']); //überarbeitete Version des Links holen.
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
		<title>URL bearbeiten</title>
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
										<td>Link</td>
											<?php 	
													echo "<td><a href='/?c=". $Link['URL_short'] ."'>" . $Link['URL_short'] . "</a></td>";
											?>	
									</tr>
									<tr class="noborder">
										<td>Original URL</td>
											<?php
												echo "<td><a href='". $Link['URL_original'] ."'>" . $Link['URL_original'] . "</td>";
											?>
									</tr>
									<tr class="noborder">
										<td>Kategorie</td>
										<td>
											<select name="catSelect" size="0" id="catSelect"> 
												<?php
													$allCats = getAllCategories($dbc);
														foreach($allCats as $cat)
														{
															if($Link['Kategorie_NR'] == $cat['Kategorie_ID'])
															{
																echo '<option value="'.$cat['Kategorie_ID'].'" selected>'.$cat['Kategorie_Bezeichnung'].'</option>';
															}
															else
															{
																echo '<option value="'.$cat['Kategorie_ID'].'">'.$cat['Kategorie_Bezeichnung'].'</option>';
															}
															
														}
												?>
											</select>
									</tr>
									<tr class="noborder">
										<td>Beschreibung</td>
										<td>
											<textarea name="beschreibung"><?= $Link['URL_Beschreibung']; ?></textarea>
										</td>
									</tr>
							</table>
							<br><br>
							<input class="btn btn-small" type = "submit" name="delete" value = "L&ouml;schen">&nbsp&nbsp&nbsp
							<input class="btn btn-small" type = "submit" name="edit" value = "Speichern">&nbsp&nbsp&nbsp
						</form>
						<?php
							if(isset($_GET['from']) && $_GET['from'] == "admin")
							{
								echo '<form class="inlineForm" action="admin_suche.php">';
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