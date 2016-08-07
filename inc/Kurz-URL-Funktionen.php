<?php
//Kurz-URL generieren // Kurz-URL-Weiterleitung
require_once './inc/dbc.inc.php';
function generateRandomWord()
{
	$adjectives = array
		('Active', 'Aggressive', 'Amazing', 'Attached', 'Appropriate', 'Acceptable', 'Afraid', 'Adorable', 'Awful', 
		'Bad', 'Brave', 'Blank', 'Busy', 'Bold', 'Big',
		'Cheap', 'Complex', 'Critical', 'Complicated', 'Careful', 'Cool', 'Crispy', 'Cute',
		'Damaged', 'Difficult', 'Defenseless', 'Devoted', 'Different', 'Delicious',
		'Fabolous', 'Fast', 'Filthy', 'Fortunate', 'Fake', 'Favorite', 'Free', 'Fuzzy', 'Funny', 'Faded',
		'Gross', 'Golden', 'Good', 'Generous',
		'Honest', 'Harsh', 'High',
		'Icy', 'Illegal', 'Ill', 'Immediate', 'Impossible', 'Infamous', 
		'Kindly', 
		'Lame', 'Lazy', 'Low', 'Long', 'Lone');
	$nouns = array
		('Apple', 'Arab', 
		'Bunny', 'Baby', 'Back', 'Bacterium', 'Ball', 'Bank',
		'Conflict', 'Coincidence', 'Cupcake', 'Cake', 
		'Dragon', 'Danger', 'Data', 'Deal', 'Death',
		'Hair', 'Hammmer', 'Hand', 'Harbour', 'Hardware',
		'Kitty', 'Kingdom', 'Knife', 'Kitchen', 'Knowledge',
		'Ninja', 'Name', 'Nature', 'Neighbor', 
		'Pancake', 'Potato', 'Page','Paint','Pants','Parliament',
		'Toilets', 'Telephone', 'Tank', 'Tape', 'Tea');

	$str = "";
	shuffle($adjectives); //Die Anordnung der Werte im Array wird durcheinander gewürfelt
	shuffle($nouns);
	$str .= $adjectives[0] . $adjectives[1] . $nouns[0];
	
	return $str;
}

function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)]; //ein Zufälliges Zeichen aus $characters nehmen und an $randomString anfügen
    }
    return $randomString;
}

function generateShortUrl($destination, $description, $category, $dbc, $shortType = 1) //shortType 1 = random String, 2 = random Word.
{
	//Überprüfung ob die übergebene URL wirklich eine URL ist.
	if (filter_var($destination, FILTER_VALIDATE_URL) == false) {
		return "Invalid URL";
	}
	
	//Falls keine userId in der Session gesetzt ist, wird die userId 0 für Gast genommen
	if(!isset($_SESSION['userId']))
	{
		$userId = 0;
	}
	else
	{
		$userId = $_SESSION['userId'];
	}
	
	//Überprüfung, ob diese URL vom Nutzer bereits eingefügt wurde und falls ja, und Kurz-Code abfragen.
	$checkLinkAdded = $dbc->prepare("SELECT URL_short FROM tbl_urls WHERE URL_original = :destination AND Benutzer_NR = :userId");
	$checkLinkAdded->bindParam(":destination", $destination);
	$checkLinkAdded->bindParam(":userId", $userId);
	$checkLinkAdded->execute();
	
	$shortCode = $checkLinkAdded->fetchColumn();
	if(!empty($shortCode))
	{
		return $shortCode;
	}
	
	if($shortType == 1)
	{
		$shortCode = generateRandomString();
	}
	else
	{
		$shortCode = generateRandomWord();
	}
	
	$addUrlQry = $dbc->prepare("INSERT INTO tbl_urls(Benutzer_NR, Kategorie_NR, URL_short, URL_original, URL_Beschreibung, URL_Datum) VALUES (:Benutzer_NR, :Kategorie_NR, :URL_short, :URL_original, :URL_Beschreibung, :URL_Datum)");
	$addUrlQry->bindParam(":Benutzer_NR", $userId);
	$addUrlQry->bindParam(":Kategorie_NR", $category);
	$addUrlQry->bindParam(":URL_short", $shortCode);
	$addUrlQry->bindParam(":URL_original", $destination);
	$addUrlQry->bindParam(":URL_Beschreibung", $description);
	$addUrlQry->bindValue(":URL_Datum", time());
	
	if($addUrlQry->execute())
	{
		return $shortCode;
	}
	else
	{
		return false;
	}
}

function getLongUrl($shortCode, $dbc)
{
	$getDestinationQry = $dbc->prepare("SELECT URL_original FROM tbl_urls WHERE URL_short = :shortCode");
	$getDestinationQry->bindParam(":shortCode", $shortCode);
	$getDestinationQry->execute();
	$destination = $getDestinationQry->fetchColumn();
	if($destination == "")
		return "http://127.0.0.1"; //falls die Kurz-URL nicht gefunden wurde, leite auf die Hauptseite weiter.
	
	return $destination;
}
?>