<?php

function checkUsernameExists($username, $dbc)
{
	$usernameCountQry = $dbc->prepare("SELECT COUNT(*) FROM tbl_benutzer WHERE Benutzer_Name = :name");
	$usernameCountQry->bindParam(":name", $username);
	$usernameCountQry->execute();
	$usernameCount = $usernameCountQry->fetchColumn();
	
	return $usernameCount; //0 = false = Username exisitiert noch nicht, 1 = true = Username existiert
}

//return: true/false ob Registration erfolgreich
function register($username, $email, $password, $dbc)
{
	if(checkUsernameExists($username, $dbc))
	{
		//username ist schon vergeben
		return false;
	}
	
	$addUserQry = $dbc->prepare("INSERT INTO tbl_benutzer(Benutzer_Name, Benutzer_Passwort, Benutzer_Email, Benutzer_Datum) VALUES (:name, :passwort, :email, :timestamp)");
	$addUserQry->bindParam(":name", $username);
	$addUserQry->bindValue(":passwort", md5($password));
	$addUserQry->bindParam(":email", $email);
	$addUserQry->bindValue(":timestamp", time());
	if($addUserQry->execute())
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>