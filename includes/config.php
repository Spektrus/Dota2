<?php
	session_start();
	$siteTitle = "Projekt 2";
	$db = new mysqli('secret') or die("Fel vid anslutning"); // Databasanslutning
	$apikey = "secret"; // API-key
	error_reporting(1);
	ini_set('display_errors', 1);
?>
