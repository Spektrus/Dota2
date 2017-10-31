<?php
include "includes/config.php";
include "classes/Match.class.php";
$match = new Match();

if (!empty($_POST["id"])) {

    $id = $_POST["id"];

    $result = $db->query("SELECT * FROM matches WHERE match_id=$id");
    if ($result->num_rows>0) {
        $match->displayMatchData($id);
    }
    else {
        $data = $match->curl_load($_POST["url"]);
        $matchData = json_decode($data, true);
        if (isset($matchData)) {
            $playerData = $matchData["result"]["players"];
            $match->matchToDB($matchData, $playerData);
            $match->displayMatchData($id);
        }
        else {
            echo "<br><p>ID doesn't exist.</p>";
        }    
    }
    
}

?>