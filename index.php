<?php
require_once './inc/dbc.inc.php';
if(isset($_GET['c']))
{
	require_once 'redirect.php';
}

require_once './inc/Listen.php';
$allCats = getAllCategories($dbc);
?>
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="stylesheet.css">
		<script src="js/jquery-1.12.3.min.js"></script>
		<script src="js/notify.min.js"></script> <!-- Notifications (zB. beim Einloggen) -->
		<title>Startseite</title>
	</head>

		<body>
		<?php
		if(isset($_GET["login"]))
		{
			if($_GET["login"] == "success")
			{
				echo "<script>$.notify('Erfolgreich eingeloggt!', 'success');</script>";
			}
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
					<h1><b>URL Kurz-Dienst</b></h1>
					<br><br> 
					
					<div>
						<form id="URL-form" method="POST">
							<input type="text" name="URL-input" id="URL-input" placeholder="http://" />
							<input type="submit" id="submitUrlBtn" name="URL-submit" value="" />
							
							<div id="response"></div>
					
							<br>
							
							<div id = "URL-art">
								<input type ="radio" id="randomChars" name="shortType" value="1" checked /> <label for="randomChars">Zeichenfolge</label>
								<input type ="radio" id="randomWords" name="shortType" value="2" /> <label for="randomWords">Wortfolge</label>
							</div>
							
							<textarea name = "Beschreibung" id="txtDescription" placeholder = "Beschreibung (Optional)" cols= "35" rows = "5"></textarea>
							
							<select name="catSelect" size="0" id="catSelect"> 
								<?php
								foreach($allCats as $cat)
								{
									echo '<option value="'.$cat['Kategorie_ID'].'">'.$cat['Kategorie_Bezeichnung'].'</option>';
								}
								?>
							</select>
							
							<br><br>
							<br><br>
							<br><br>
							<br><br>
							
							
							
							
						</form>
					</div>
				</div>
				<br>
			</div>
			
		</body>
		
		<script>
		$("#URL-form").submit(function(e) {
			//alert("test");
			e.preventDefault();
			$.post("getUrl.php", 
			{
				destUrl: $("#URL-input").val(),
				description: $("#txtDescription").val(),
				catId: $("#catSelect").val(),
				shortType: $('input[name=shortType]:checked', '#URL-form').val()
			})
			.success(function(data) 
			{
				console.log(data);
				if(data == "Invalid URL")
				{
					$("#response").html('');
					$("#URL-input").notify("URL ungültig", { position:"top" });
				}
				else
				{
					$("#response").html('Kurze URL: <a href="/?c=' + data + '">' + data + '</a>');
					$("#URL-input").notify('URL gekürzt', { position:"top", className:"success" });
					
				}
			})
		});
		
		</script>
</html>
