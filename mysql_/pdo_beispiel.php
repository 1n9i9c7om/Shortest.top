<?php
//Testszenario: Es werden Username und Passwort per POST an eine Login-Form �bergeben, um den Login zu �berpr�fen.


//Beispiel mysql_
$username = mysql_real_escape_string($_POST['username']); //Nutzername gegen Zeichen sch�tzen, die die Query unsicher machen k�nnen.
$passwort = mysql_real_escape_string($_POST['passwort']); //gleiches wie beim Nutzernamen.
$sql = "SELECT COUNT(*) AS login_successful FROM tbl_benutzer WHERE Benutzer_Name = '$username' AND Benutzer_Passwort = '$passwort'";
/*
W�ren die Daten vom Nutzer nicht escaped, w�re folgende Modifikation der Abfrage m�glich:
$_POST['passwort'] wird vom Nutzer als: ' or 1=1-- 
Dadurch sieht die End�ltige Abfrage wie folgt aus:
SELECT COUNT(*) AS login_successful FROM tbl_benutzer WHERE Benutzer_Name = 'admin' AND Benutzer_Passwort = '' or 1=1--'

Da -- in SQL einen Kommentar darstellt, wird das letzte Anf�hrungszeichen ignoriert. Das Kriterium zum Einloggen lautet nun: 
Benutzer_Name = 'admin' 
Und
Benutzer_Passwort = '' Oder 1=1

Da 1 immer gleich 1 ist, wird immer ein Datensatz zur�ck gegeben und der Zugang zu dem Konto wird gew�hrt. 
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
																			//die Parameter werden durch Platzhalter ersetzt, welche mit einem f�hrenden Doppelpunkt gekennzeichnet werden
$loginCheckQry->bindParam(":username", $_POST['username']); //Der Wer aus $_POST['username'] wird escaped und in die Query eingesetzt
$loginCheckQry->bindParam(":passwort", $_POST['passwort']);
$loginCheckQry->execute(); //die Query wird ausgef�hrt.
$loginCheck = $loginCheckQry->fetchColumn(); //fetchColumn w�hlt den Wert des 1. Attributes aus. Das reicht f�r unseren Zweck. Wenn man 1 oder mehrere Datens�tze ben�tigt, gibt es fetch() und fetchAll()
if($loginCheck == 1)
{
	//login erfolgreich
}
else
{
	//login fehlgeschlagen
}

?>