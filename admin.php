<?php
require_once './inc/dbc.inc.php';
require_once './inc/Listen.php';

if(!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']))
{
	header("Location: /");
	die();
}

if(isset($_POST['Kategorie']))
{
	$addCatQry = $dbc->prepare("INSERT INTO tbl_kategorien(Kategorie_Bezeichnung) VALUES (:katBezeichnung)");
	$addCatQry->bindParam(":katBezeichnung", $_POST['Kategorie']);
	$addCatQry->execute();
}

if(isset($_GET['delete']))
{
	if($_GET['delete'] == "cat")
	{
		$alert = "<script>$.notify('Kategorie wurde gelöscht', 'success');</script>";
	}
	else if($_GET['delete'] == "user")
	{
		$alert = "<script>$.notify('Nutzer wurde gelöscht', 'success');</script>";
	}
}

$allUsers = getAllUsers($dbc);
$allCats = getAllCategories($dbc);
?>
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="stylesheet.css">
		<script src="js/jquery-1.12.3.min.js"></script>
		<script src="js/notify.min.js"></script> <!-- Notifications (zB. beim Einloggen) -->
		<title>Admin&uuml;bersicht</title>
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
					<h1><b>ADMIN PANEL</b></h1>
					<br><br>
				</div>
				<!---->

				<div id="tables">
					<table class="demo" id="table1">
						<thead>
							<tr>
								<th colspan="2">NUTZER</th>

							</tr>
						</thead>
						<tbody>
							<?php
							foreach($allUsers as $user)
							{
								echo "<tr>";
								echo "<td><a href='bereich.php?user=".$user['Benutzer_ID']."'>".htmlspecialchars($user['Benutzer_Name'])."</a></td>";
								echo "<td><a href=\"edit_user.php?id=".$user['Benutzer_ID']."\" class=\"btn btn-small\">EDIT</a></td>";
								echo "</tr>";
							}

							?>
							<!-- <tr>

								<td>Max Mustermann9</td>
								<td><input class="btn btn-small" type = "submit" value = "EDIT"></td>

							</tr> -->
						<tbody>
					</table>



					<table class="demo" id="table2">
						<thead>
							<tr>
								<th colspan="2">KATEGORIEN</th>


							</tr>
						</thead>
						<tbody>
							<?php
							foreach($allCats as $cat)
							{
								echo "<tr>";
								echo "<td>".htmlspecialchars($cat['Kategorie_Bezeichnung'])."</td>";
								echo "<td><a href=\"edit_cat.php?id=".$cat['Kategorie_ID']."\" class=\"btn btn-small\">EDIT</a></td>";
								echo "</tr>";
							}

							?>
							<!-- <tr>
								<td>Test</td>
								<td><input class="btn btn-small" type = "submit" value = "EDIT"></td>

								
							</tr> -->
							
							<tr>
								<form method="POST">
									<td><input type = "text" name="Kategorie" placeholder="Kategorie"></td>
									<td><input class="btn btn-small" name="submit" type = "submit" value = "Hinzuf&uuml;gen"></td>
								</form>
							</tr>	
						<tbody>	
					</table>
				</div>					

				<br><br><br>
				<br><br><br>
				<br><br><br>
			</div>

		</body>
</html>