<?php

function getAllLinksByUser($userId, $dbc)
{
	$selectLinks = $dbc->prepare("SELECT * FROM tbl_urls, tbl_kategorien WHERE Kategorie_ID = Kategorie_NR AND Benutzer_NR = :userId");
	
	$selectLinks->bindParam(":userId", $userId);
	$selectLinks->execute();

	
	return $selectLinks->fetchAll();
}

function getLinkById($dbc, $urlId)
{
	$selectLinkQry = $dbc->prepare("SELECT * FROM tbl_urls WHERE URL_ID = :id");
	$selectLinkQry->bindParam(":id", $urlId);
	$selectLinkQry->execute();
	
	return $selectLinkQry->fetch();
}

function getLinksByParam($dbc, $userId = -1, $catNr = 0, $descSearchString = "") //userId 0 ist Gast, -1 ist nicht angegeben
{
	$sqlString = "SELECT * FROM tbl_urls, tbl_kategorien, tbl_benutzer WHERE Kategorie_ID = Kategorie_NR AND (Benutzer_NR = Benutzer_ID)";
	if($userId != -1)
	{
		$sqlString .= " AND Benutzer_NR = :userId";
	}
	if($catNr != 0)
	{
		$sqlString .= " AND Kategorie_NR = :catNr";
	}
	if($descSearchString != "")
	{
		$sqlString .= " AND URL_Beschreibung LIKE :searchStr";
	}
	
	$selectLinksQry = $dbc->prepare($sqlString);
	
	if($userId != -1) //Nachteil von PDO -> leider so nicht ohne doppelte if-Abfragen machbar.
	{
		$selectLinksQry->bindParam(":userId", $userId);
	}
	if($catNr != 0)
	{
		$selectLinksQry->bindParam(":catNr", $catNr);
	}
	if($descSearchString != "")
	{
		$descSearchString = "%".$descSearchString."%"; //Anfügen der Platzhalter für den LIKE-Operator in MySQL.
		$selectLinksQry->bindParam(":searchStr", $descSearchString);
	}
	
	$selectLinksQry->execute();
	return $selectLinksQry->fetchAll();
}

function getAllCategories($dbc)
{
	$selectCatsQry = $dbc->query("SELECT * FROM tbl_kategorien");
	return $selectCatsQry->fetchAll();
}

function getCatById($dbc, $id)
{
	$selectCatQry = $dbc->prepare("SELECT * FROM tbl_kategorien WHERE Kategorie_ID = :catId");
	$selectCatQry->bindParam(":catId", $id);
	$selectCatQry->execute();
	return $selectCatQry->fetch();
}

function getAllUsers($dbc)
{
	$selectUsersQry = $dbc->query("SELECT * FROM tbl_benutzer");
	return $selectUsersQry->fetchAll();
}

function getUserById($dbc, $id)
{
	$selectUserQry = $dbc->prepare("SELECT * FROM tbl_benutzer WHERE Benutzer_ID = :id");
	$selectUserQry->bindParam(":id", $id);
	$selectUserQry->execute();
	return $selectUserQry->fetch();
}

?>