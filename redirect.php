<?php
//Diese Datei dient zur Weiterlung zur gewünschten Ziel-URL

require_once './inc/dbc.inc.php'; //Datenbankverbindung einfügen
require_once './inc/Kurz-URL-Funktionen.php'; //Funktionen zum Kurz-URL erstellen einbinden

if(isset($_GET['c']))
{
	$longUrl = getLongUrl($_GET['c'], $dbc);
	if(!empty($longUrl))
	{
		$addClickQry = $dbc->prepare("UPDATE tbl_urls SET URL_Klicks = URL_Klicks + 1 WHERE URL_short = :short");
		$addClickQry->bindParam(":short", $_GET['c']);
		$addClickQry->execute();
		header("Location: " . $longUrl);
	}
	else
	{
		header("Location: /");
	}
}
else
{
	header("Location: /");
}



?>