<?php

function login($username, $passwort, $dbc)
{
	$getUserIdQry = $dbc->prepare("SELECT Benutzer_ID FROM tbl_benutzer WHERE Benutzer_Name = :name AND Benutzer_Passwort = :passwort");
	$getUserIdQry->bindParam(":name", $username);
	$getUserIdQry->bindValue(":passwort", md5($passwort));
	$getUserIdQry->execute();
	$userId = $getUserIdQry->fetchColumn();
	
	if(!empty($userId)) //1 = true = Nutzer mit diesen Daten gefunden
	{
		$_SESSION['username'] = $username;
		$_SESSION['userId'] = $userId;
		$_SESSION['isAdmin']  = isAdmin($_POST['username'], $dbc);
		return true;
	}
	else //2 = false = kein Nutzer mit diesen Daten gefunden
	{
		return false;
	}
}

function isAdmin($username, $dbc)
{
	$isAdminQry = $dbc->prepare("SELECT Benutzer_Admin FROM tbl_benutzer WHERE Benutzer_Name = :name");
	$isAdminQry->bindParam(":name", $username);
	$isAdminQry->execute();
	
	return $isAdminQry->fetchColumn();
}

?>