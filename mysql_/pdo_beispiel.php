<?php
//Testszenario: Es werden Username und Passwort per POST an eine Login-Form übergeben, um den Login zu überprüfen.


//Beispiel mysql_
$username = mysql_real_escape_string($_POST['username']); //Nutzername gegen Zeichen schützen, die die Query unsicher machen können.
$passwort = mysql_real_escape_string($_POST['passwort']); //gleiches wie beim Nutzernamen.
$sql = "SELECT COUNT(*) AS login_successful FROM tbl_benutzer WHERE Benutzer_Name = '$username' AND Benutzer_Passwort = '$passwort'";
/*
Wären die Daten vom Nutzer nicht escaped, wäre folgende Modifikation der Abfrage möglich:
$_POST['passwort'] wird vom Nutzer als: ' or 1=1-- 
Dadurch sieht die Endültige Abfrage wie folgt aus:
SELECT COUNT(*) AS login_successful FROM tbl_benutzer WHERE Benutzer_Name = 'admin' AND Benutzer_Passwort = '' or 1=1--'

Da -- in SQL einen Kommentar darstellt, wird das letzte Anführungszeichen ignoriert. Das Kriterium zum Einloggen lautet nun: 
Benutzer_Name = 'admin' 
Und
Benutzer_Passwort = '' Oder 1=1

Da 1 immer gleich 1 ist, wird immer ein Datensatz zurück gegeben und der Zugang zu dem Konto wird gewährt. 
*/
$loginCheckQry = $mysql_query($sql);
$loginCheck = mysql_fetch_assoc($loginCheckQry);
if($loginCheck['login_successful'] == 1)
{
	//login erfolgreich
}
else
{
	//login fehlgeschlagen
}

//Beispiel PDO
$loginCheckQry = $dbc->prepare("SELECT COUNT(*) AS login_successful FROM tbl_benutzer WHERE Benutzer_Name = :username AND Benutzer_Passwort = :passwort"); //Die Abfrage wird "vorbereitet", 
																			//die Parameter werden durch Platzhalter ersetzt, welche mit einem führenden Doppelpunkt gekennzeichnet werden
$loginCheckQry->bindParam(":username", $_POST['username']); //Der Wer aus $_POST['username'] wird escaped und in die Query eingesetzt
$loginCheckQry->bindParam(":passwort", $_POST['passwort']);
$loginCheckQry->execute(); //die Query wird ausgeführt.
$loginCheck = $loginCheckQry->fetchColumn(); //fetchColumn wählt den Wert des 1. Attributes aus. Das reicht für unseren Zweck. Wenn man 1 oder mehrere Datensätze benötigt, gibt es fetch() und fetchAll()
if($loginCheck == 1)
{
	//login erfolgreich
}
else
{
	//login fehlgeschlagen
}

?>