<?php
	session_start();
	$siteTitle = "Projekt 2";
	$db = new mysqli('beckius.me', 'mabe1317', 'cDBtWSX9sCsjBmNc', 'projekt') or die("Fel vid anslutning"); // Databasanslutning
	$apikey = "EC70849F4F195F151784D729025C9960"; // API-key
	error_reporting(1);
	ini_set('display_errors', 1);
?>