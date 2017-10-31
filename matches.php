<?php
    
    $pageTitle = "Matches";
    include "includes/config.php"; 
    include "includes/header.php";
    include "classes/Match.class.php";
    $match = new Match();
?>
			<!--Content mellan header och footer-->
	        <div id="content" class="container">
	        	<div id="matches">
	        	<?php     
	        		$match->listMatches();
	        	?>
	        	</div>
	        </div>       

<?php
	include "includes/footer.php";
	$db->close(); 
?>