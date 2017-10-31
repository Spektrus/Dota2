<?php

class Match {

	public $db;
	public $apikey;

	public function __construct() {
		$this->db = new mysqli('beckius.me', 'mabe1317', 'cDBtWSX9sCsjBmNc', 'projekt'); // Databas
		$this->apikey = "EC70849F4F195F151784D729025C9960";

	}

	public function matchToDB($matchData, $playerData) { // Lägger in match info i DB

		$query1 = "INSERT INTO player_matches (match_id, account_id, player_slot, hero_id, item_0, item_1, item_2, item_3, item_4, item_5, kills, deaths, assists, leaver_status, gold, last_hits, denies, 
	    	gold_per_min, xp_per_min, gold_spent, hero_damage, tower_damage, hero_healing, level) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$query2 = "INSERT INTO matches (match_id, match_seq_num, radiant_win, start_time, duration, tower_status_radiant, tower_status_dire, barracks_status_radiant, barracks_status_dire, cluster, first_blood_time, lobby_type, human_players, leagueid, positive_votes, negative_votes, game_mode, engine) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";		

		/* Statements */	
	  	$stmt1 = $this->db->prepare($query1);
	  	$stmt2 = $this->db->prepare($query2);

	  	/* Loopar som lägger in alla värden från array */
	  	foreach ($matchData as $key => $value) {
			$stmt2->bind_param(
				'iiiiiiiiiiiiiiiiii',
				$value["match_id"],
				$value["match_seq_num"],
				$value["radiant_win"],
				$value["start_time"],
				$value["duration"],
				$value["tower_status_radiant"],
				$value["tower_status_dire"],
				$value["barracks_status_radiant"],
				$value["barracks_status_dire"],
				$value["cluster"],
				$value["first_blood_time"],
				$value["lobby_type"],
				$value["human_players"],
				$value["leagueid"],
				$value["positive_votes"],
				$value["negative_votes"],
				$value["game_mode"],
				$value["engine"]
				);

			$stmt2->execute();
		}

		$stmt2->close();

	  	foreach ($playerData as $key => $value) {
			$stmt1->bind_param(
				'iiiiiiiiiiiiiiiiiiiiiiii',
				$_POST["id"],
				$value["account_id"],
				$value["player_slot"],
				$value["hero_id"],
				$value["item_0"],
				$value["item_1"],
				$value["item_2"],
				$value["item_3"],
				$value["item_4"],
				$value["item_5"],
				$value["kills"],
				$value["deaths"],
				$value["assists"],
				$value["leaver_status"],
				$value["gold"],
				$value["last_hits"],
				$value["denies"],
				$value["gold_per_min"],
				$value["xp_per_min"],
				$value["gold_spent"],
				$value["hero_damage"],
				$value["tower_damage"],
				$value["hero_healing"],
				$value["level"]
				);

			$stmt1->execute();
		}

	    $stmt1->close();
	}

	function displayMatchData($id) { // Skriver ut match data

		$this->id = $id;
		$query = "SELECT * FROM matches WHERE match_id =" . $id . "";
		?> 
		<!--Tabs-->
		<ul class="nav nav-tabs">
		    <li class="active"><a data-toggle="tab" href="#game">Game</a></li>
		    <li><a data-toggle="tab" href="#comments">Comments</a></li>
	  	</ul>
	  	<br>
	  	<div class="tab-content">
		    <div id="game" class="tab-pane fade in active">
		    <?php
		    if ($this->db->query($query)->fetch_object()->radiant_win = 1) { // Kollar vinnare
            echo "<h3>Radiant Victory</h3>";
	        }
	        else {
	            echo "<h3>Dire Victory</h3>";
	        }

	        $time = $this->db->query($query)->fetch_object()->duration; // Matchlängd
	        $timestamp = $this->db->query($query)->fetch_object()->start_time; // Datum/tid när matchen avslutades
	        echo "<h4>Duration: " . gmdate("H:i:s", $time) . "</h4>";
	        echo "<h4>Date: " . gmdate("F jS Y - H:i:s", $timestamp) . "</h4>";
			$this->displayTeam("Radiant");
	        $this->playerStats(0, 5); // Lag 1
	        $this->displayTeam("Dire"); 
	        $this->playerStats(5, 9); // Lag 2
	        ?>
	        </div>
	  	<?php
	  	echo "<div id='comments' class='tab-pane fade'>";
        $this->listComments($id);
        echo "</div></div>";
	}

	function curl_load($url){ // curl

		$ch = curl_init();
	    curl_setopt($ch=curl_init(), CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $response = curl_exec($ch);
	    curl_close($ch);
	    return $response;
	}

	function playerStats($a, $b) { // Skriver ut spelare

		$query = "SELECT * FROM player_matches WHERE match_id =" . $this->id . " LIMIT ". $a . ", " . $b . ""; // Hämtar spelare mellan $a och $b
		$result = $this->db->query($query) or die("Fel vid SQL-fråga");

		while ($this->row = $result->fetch_array()) {

			$heroquery = $this->db->query("SELECT * FROM heroes WHERE ID =" . $this->row[3] .""); // Hämtar namn på hero med tillhörande id
            $heroname = $heroquery->fetch_object()->name;       		                          // Och konverterar till rätt format för filnamn  
            $hero = str_replace("npc_dota_hero_", "", $heroname);

            $getItem = function($position) { // Hämtar items och konverterar till rätt format för filnamn

				$itemquery = $this->db->query("SELECT * FROM items WHERE ID = " . $this->row[$position] . "");
				$itemname = $itemquery->fetch_object()->name;
				$item = str_replace("item_", "", $itemname);

				$html = "<img height='33' width='44' src='http://cdn.dota2.com/apps/dota2/images/items/";
				
				if ($item ==  "empty") {
					return "";
				}
				else {
					$end = "_lg.png";
					$html = $html . $item . $end . "' alt=" . $item . ">";
					return $html;
				}
		    };

			$id = $this->row[1];

		    if ($id == "4294967295") {
		    	$id = "Anonymous";
		    } 
		    else {
		    	/* $id = $this->getSteamName($id); */ // Skriver ut namn, men gör ökar load time med några sekunder
		    	$id = $id;
		    }
		

            $gold = $this->row[14]+$this->row[19];
            echo "<tr>";
            echo "<td>" . $id . "</td>"; // Player-ID
            echo "<td><img src='http://cdn.dota2.com/apps/dota2/images/heroes/". $hero . "_sb.png' alt=". $hero . "></td>"; // Hero
            echo "<td>" . $this->row[23] . "</td>"; // Level
            echo "<td>" . $this->row[10] . "</td>"; // Kills
            echo "<td>" . $this->row[11] . "</td>"; // Deaths
            echo "<td>" . $this->row[12] . "</td>"; // Assists
            echo "<td>" . $gold . "</td>"; // Gold
            echo "<td>" . $this->row[15] . "</td>"; // Last Hits
            echo "<td>" . $this->row[16] . "</td>"; // Denies
            echo "<td>" . $this->row[17] . "</td>"; // GPM
            echo "<td>" . $this->row[18] . "</td>"; // XPM
            echo "<td>" . $this->row[20] . "</td>"; // Hero Damage
            echo "<td>" . $this->row[21] . "</td>"; // Tower Damage
            echo "<td>" . $this->row[22] . "</td>"; // Hero Healing
            echo "<td>" . $getItem("item_0") . $getItem("item_1") . $getItem("item_2") . $getItem("item_3") . $getItem("item_4") . $getItem("item_5") . "</td>"; // Items
        }

        echo "</tbody>";
        echo "</table>";

	}

	function displayTeam($team) {  // Skriver ut lag

		if ($team == "Radiant") {
			$team2 = "<br><h4 style='color:green'>Radiant</h4>";
		}

		else {
			$team2 = "<br><h4 style='color:red'>Dire</h4>";
		}

        	echo $team2;
            echo "<table id=" . $team . "-" . $this->id . " class='table-striped table table-condensed'>"; // Table headers
            echo "<thead>";
            echo "<tr>";
            echo "<th>Player</th>";
            echo "<th>Hero</th>";
            echo "<th>Level</th>";
            echo "<th>K</th>";
            echo "<th>D</th>";
            echo "<th>A</th>";
            echo "<th>Gold</th>";
            echo "<th>LH</th>";
            echo "<th>DN</th>";
            echo "<th>GPM</th>";
            echo "<th>XPM</th>";
            echo "<th>HD</th>";
            echo "<th>TD</th>";
            echo "<th>HH</th>";
            echo "<th>Items</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
	}

	function listMatches() {  // Skriver ut match-ids lagrade i databasen
		$query = "SELECT * FROM matches ORDER BY match_id DESC";
		$result = $this->db->query($query);


		if (isset($_REQUEST["showMatch"])) { // Skriver ut table med data från databasen
			$id = $_REQUEST["showMatch"];
			$this->displayMatchData($id);
		}

		echo "<h3>Matches in database</h3>"; // Table headers
		echo "<p>Click to display stats</p>";
		echo "<table id='matchList' class='table-striped table table-condensed'>";
		echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Winner</th>";
        echo "<th>Duration</th>";
        echo "<th>Radiant</th>";
        echo "<th>Dire</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $getHero = function($id) { // Hämtar namn på hero med tillhörande id och skickar tillbaka bild
        	$heroquery = $this->db->query("SELECT * FROM heroes WHERE ID =" . $id ."");
	        $heroname = $heroquery->fetch_object()->name;
	        $hero = str_replace("npc_dota_hero_", "", $heroname);
	        return "<img src='http://cdn.dota2.com/apps/dota2/images/heroes/". $hero . "_sb.png' alt=". $hero . ">";
        };

		while($this->row = $result->fetch_array()) { // Skriver ut alla matcher lagrade i databasen
			echo "<tr><td><a href='matches.php?showMatch=" . $this->row[0] . "'>" . $this->row[0] . "</a></td>";
			if ($this->row[2] == 0) { 
				echo "<td style='color:red'>Dire</td>";
			}
			else {
				echo "<td style='color:green'>Radiant</td>";
			}
			echo "<td>" . gmdate("H:i:s", $this->row[4]) . "</td>";

			$id = $this->row[0];
			$query2 = "SELECT hero_id FROM player_matches WHERE match_id=$id LIMIT 0, 5";  // Heroes från lag 1
			$result2 = $this->db->query($query2);
			echo "<td>";
			while ($row2 = $result2->fetch_array()) {
				echo $getHero($row2[0]);
			}
			echo "</td><td>";
			$query3 = "SELECT hero_id FROM player_matches WHERE match_id=$id LIMIT 5, 9"; // Heroes från lag 2
			$result3 = $this->db->query($query3);
			while ($row3 = $result3->fetch_array()) {
				echo $getHero($row3[0]);
			}
			echo "</td></tr>";
		}
		echo "</tbody></table>";

	}

	function makeComment($id) { // Skapar html för att kommentera
		?> 
		<form id="makeComment" method="post">
			<label for="name">Name</label>
			<input class="form-control" id="name" name="name" type="text" maxlength="30" style="width:25%"><br>
			<label for="comment">Comment</label>
			<textarea class="form-control" id="comment" name="comment" rows="5" maxlength="250" style="width:50%"></textarea><br>
			<input class="btn btn-default" type="button" onclick="addComment(<?=$id?>)" name="addMatch" value="Post comment">
		</form><br>	
		<?php
	}

	function listComments($id) { // Skriver ut kommenterar från tillhörande match id
		$query = "SELECT * FROM comments WHERE match_id=$id ORDER BY id DESC";
		$result = $this->db->query($query);
		$this->makeComment($id);
		while ($row = $result->fetch_array()) {
			echo "<div class='panel panel-default comment'>";
			echo "<div class='panel-body' id='comment-" . $row["id"] . "'>";
			echo "<div class='commentInfo'>" . "<p class='date'>" . $row["date"] . "</p><h4 class='name'>" . htmlspecialchars($row["name"]) . "</h4></div>";
			echo "<div class='commentText'><p>" . htmlspecialchars($row["comment"]) . "</p></div>";
			echo "</div></div>";
		}
	}

	function addComment($id, $name, $comment) { // Lägger till kommentarar i databas

		$query = "INSERT INTO comments (match_id, name, comment) VALUES (?, ?, ?)";
		$stmt = $this->db->prepare($query);
		$stmt->bind_param('iss', $id, $name, $comment);
		$stmt->execute();
		$stmt->close();
	}

	function getSteamName($id) { // Konverterar id, och hämtar spelarens profilnamn - ANVÄNDS EJ 	

		$steamid = "765" . ($id + 61197960265728); 
        $json_string2 = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $this->apikey . "&steamids=" . $steamid . "");
        $steam_data = json_decode($json_string2, true);
        $steamname = $steam_data['response']['players'][0]['personaname'];

    	if  (isset($steamname)) { // Kollar om det existerar, gör det inte så har spelaren en privat profil
            return $steamname;
        }
        else { 
            return "Anonymous";
        }


	}
}

?>