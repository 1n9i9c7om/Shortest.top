<?php
/* Diese Datei wird über AJAX aufgerufen und gibt NUR den verkürzten Link zurück. */

require_once 'inc/dbc.inc.php'; //Datenbankverbindung einfügen
require_once 'inc/Kurz-URL-Funktionen.php'; //Funktionen zum Kurz-URL erstellen einbinden

if(isset($_POST['destUrl']) && isset($_POST['description']) && isset($_POST['catId']) && isset($_POST['shortType']))
{
	$destinationURL = $_POST['destUrl'];
	$description = $_POST['description'];
	$categoryId = $_POST['catId'];
	$shortType = $_POST['shortType'];

	echo generateShortUrl($destinationURL, $description, $categoryId, $dbc, $shortType);
}
else
{
	echo "Error";
}

?>