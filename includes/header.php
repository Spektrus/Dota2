<!DOCTYPE html>
<html>
    <head>
        <title><?= $siteTitle ." - ". $pageTitle; // Sätter titeln efter variabler definerade i egna filer ?></title>
        <meta charset="utf-8" />
        <meta name="description" content="Web 2.0 - Projekt">
        <meta name="author" content="Mathias Beckius">
        <link href="css/style.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    	<!--Wrapper-->
    	<div id="wrapper" class="container">
    		<!--Header-->
		    	<header>
		    		<nav class="navbar navbar-default navbar-fixed-top">
                        <div class="container">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="#">Projekt</a>
                            </div>
                            <div class="collapse navbar-collapse">
                                <ul class="nav navbar-nav">
                                    <li><a href="index.php">Add Match</a></li>
                                    <li><a href="matches.php">Matches</a></li>
                                </ul>  
                            </div>
                        </div>    
			    	</nav>
		    	</header>