<?php
	require_once "./inc/dbc.inc.php";
	require_once "./inc/Listen.php";
	
	if(!isset($_SESSION['username']))
	{
		header("Location: /login.php"); // User wird auf Hauptseite geleitet
	}

	if($_SESSION['isAdmin'] && isset($_GET['user']))
	{
		$userId = $_GET['user']; //der Admin wird übers Admin-Panel auf diese Seite geleitet, wenn er die URLs eines anderen Nutzers einsehen will
	}
	else
	{
		$userId = $_SESSION['userId']; //...jedoch können normale Nutzer das nicht.
	}
	
	if(isset($_GET['delete']) && $_GET['delete'] == "url")
	{
		$alert = "<script>$.notify('Link wurde gelöscht', 'success');</script>";
	}
			//(Bedingung ? true : false)
	$catId = (isset($_POST['catId']) ? $_POST['catId'] : 0);
	$searchString = (isset($_POST['Beschreibung']) ? $_POST['Beschreibung'] : "");

?>

<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="stylesheet.css">
		<title>Bereich</title>
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
					echo "Willkommen, " . $_SESSION['username'] . ".";
				?>
				<a href="edit_user.php?id=<?= $_SESSION['userId']; ?>">Email oder Passwort &auml;ndern</a> | 
				<a href="login.php?logout">Abmelden</a>
				</div>
				<!---->
				<div id = "inhalt-table">
				<form method="POST">
					Kategorie:
					<select name="catId">
						<option value="0">Alle</option>
						<?php
							$allCats = getAllCategories($dbc);
							foreach($allCats as $cat)
							{
								if($cat['Kategorie_ID'] == $catId)
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
					&nbsp;
					Beschreibung: <input type="text" name="Beschreibung" placeholder="Beschreibung" value="<?= $searchString; ?>">
					<input type="submit" value="Suchen" name="Suchen" class="btn btn-small">
				</form>
					<table class="demo">
						<thead>
							<tr>
								<th class="noborder">Link</th>
								<th class="noborder">Original URL</th>
								<th class="noborder">Kategorie</th>
								<th class="noborder">Beschreibung</th>
								<th class="noborder">Klicks</th>
								<th class="noborder-small">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach(getLinksByParam($dbc, $userId, $catId, $searchString) as $Link)
								{
									echo "<tr>";
									echo "<td><a href='/?c=". $Link['URL_short'] ."'>" . $Link['URL_short'] . "</a></td>";
									echo "<td><div class='urlDisplay' style=''><a href='". $Link['URL_original'] ."'>" . $Link['URL_original'] . "</div></td>";
									echo "<td>" . $Link['Kategorie_Bezeichnung'] . "</td>";
									echo "<td>" . nl2br(htmlspecialchars($Link['URL_Beschreibung'])) . "</td>"; //nl2br wandelt Zeilenumbrüche in <br>'s um (new line to br)
									echo "<td>" .$Link["URL_Klicks"] . "</td>";
									echo '<td class="noborder"><a href="edit.php?id='.$Link['URL_ID'].'" class="btn btn-small">EDIT</a></td>';
									echo "</tr>";
								}
							?>
						<tbody>
					</table>
					<br><br>
					<br><br>
				</div>
			<br>
		</div>	
	</body>
</html>
