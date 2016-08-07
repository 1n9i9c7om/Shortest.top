-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Mai 2016 um 11:15
-- Server-Version: 5.5.47-0+deb8u1
-- PHP-Version: 5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `shorturl`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_benutzer`
--

CREATE TABLE IF NOT EXISTS `tbl_benutzer` (
  `Benutzer_ID` int(11) NOT NULL,
  `Benutzer_Name` varchar(16) NOT NULL,
  `Benutzer_Passwort` varchar(32) NOT NULL,
  `Benutzer_Email` varchar(60) NOT NULL,
  `Benutzer_Datum` int(11) NOT NULL,
  `Benutzer_Admin` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tbl_benutzer`
--

INSERT INTO `tbl_benutzer` (`Benutzer_ID`, `Benutzer_Name`, `Benutzer_Passwort`, `Benutzer_Email`, `Benutzer_Datum`, `Benutzer_Admin`) VALUES
(0, 'Gast', '0', '0', 0, 0),
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin2@shortest.top', 1463861342, 1),
(2, 'Test1', '4061863caf7f28c0b0346719e764d561', 'test1@shortest.top', 1463861494, 0),
(3, 'Test2', '4edefd1254ebf8bdb04bf7c208a1f347', 'test2.2@shortest.top', 1463861519, 0),
(4, 'Test3', '3aaa4ff6fa71d98282e0b2e0c49d4066', 'test3@shortest.top', 1463861540, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_kategorien`
--

CREATE TABLE IF NOT EXISTS `tbl_kategorien` (
  `Kategorie_ID` int(11) NOT NULL,
  `Kategorie_Bezeichnung` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tbl_kategorien`
--

INSERT INTO `tbl_kategorien` (`Kategorie_ID`, `Kategorie_Bezeichnung`) VALUES
(1, 'Andere'),
(2, 'Google-Suchabfragen'),
(3, 'Stundenplan');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_urls`
--

CREATE TABLE IF NOT EXISTS `tbl_urls` (
  `URL_ID` int(11) NOT NULL,
  `Benutzer_NR` int(11) NOT NULL,
  `Kategorie_NR` int(11) NOT NULL,
  `URL_short` varchar(60) NOT NULL,
  `URL_original` varchar(2000) NOT NULL,
  `URL_Beschreibung` varchar(300) NOT NULL,
  `URL_Datum` int(11) NOT NULL,
  `URL_Klicks` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tbl_urls`
--

INSERT INTO `tbl_urls` (`URL_ID`, `Benutzer_NR`, `Kategorie_NR`, `URL_short`, `URL_original`, `URL_Beschreibung`, `URL_Datum`, `URL_Klicks`) VALUES
(108, 1, 2, 'r0LBqP', 'https://www.google.de/search?q=Berufskolleg+Bocholt+West&oq=Berufskolleg+Bocholt+West&aqs=chrome..69i57j0l4j69i60.335j0j7', 'Eine Google-Suche nach dem BKBW.', 1463862427, 7),
(109, 1, 3, 'AcceptableDifficultPaint', 'http://www.bkbocholt-west.de/medien/Stundenplan/Klassenplan/index_ITAM.htm', 'ITA-M Stundenplan', 1463862466, 5),
(110, 2, 3, 'LoneImmediateConflict', 'http://www.bkbocholt-west.de/medien/Stundenplan/Klassenplan/index_GTAM.htm', 'GTA-M Stundenplan', 1463862511, 8),
(112, 1, 1, 'NVe7GA', 'https://de.wikipedia.org/wiki/Komplexe_Zahl', 'Wikipedia-Eintrag zu Komplexen Zahlen', 1463862586, 0),
(113, 3, 1, 'CuteFuzzyHair', 'http://blogs.sandiegozoo.org/wp-content/uploads/2011/02/T10_0628_015.jpg', 'Ein Bild zu einem Koala. :)', 1463862621, 6),
(114, 0, 1, 'sf7Iz6', 'http://php.net/', 'Die Seite von PHP.', 1463862804, 13),
(115, 2, 2, 'BerHMB', 'https://www.google.de/webhp?hl=de#hl=de&q=bit.ly', 'Google-Suche nach bit.ly. ', 1463862853, 4);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tbl_benutzer`
--
ALTER TABLE `tbl_benutzer`
  ADD PRIMARY KEY (`Benutzer_ID`);

--
-- Indizes für die Tabelle `tbl_kategorien`
--
ALTER TABLE `tbl_kategorien`
  ADD PRIMARY KEY (`Kategorie_ID`);

--
-- Indizes für die Tabelle `tbl_urls`
--
ALTER TABLE `tbl_urls`
  ADD PRIMARY KEY (`URL_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tbl_benutzer`
--
ALTER TABLE `tbl_benutzer`
  MODIFY `Benutzer_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `tbl_kategorien`
--
ALTER TABLE `tbl_kategorien`
  MODIFY `Kategorie_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `tbl_urls`
--
ALTER TABLE `tbl_urls`
  MODIFY `URL_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=117;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
