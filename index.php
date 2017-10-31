<?php
    
    $pageTitle = "Request Match";
    include "includes/config.php"; 
    include "includes/header.php";   
?>

			<!--Content mellan header och footer-->
	        <div id="content" class="container">
	        	
	        	<form class="form-inline" action="index.php" method="post">
	        		<label>Match ID: </label>
	        		<input class="form-control" id="input" type="text" name="matchid">
	        		<input class="btn btn-default" type="button" onclick="getMatchInfo()" name="addMatch" value="Send request">
	        	</form><br>

	        	<p>Sidan är till för att kunna hämta hem statistik från en match man spelat i spelet Dota 2. För fungerande ID:n, använt något härifrån: <a href="http://www.dotabuff.com/matches">http://www.dotabuff.com/matches</a>.</p>
	        	<p>För att se lagrade matcher, klicka dig vidare till "Matches" i menyn.</p>

	        	<div id="match">
	        	</div>
	        </div>       

<?php
	include "includes/footer.php";
?>