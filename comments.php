<?php
include "includes/config.php";
include "classes/Match.class.php";
$match = new Match();

if (!empty($_POST["id"])) {

    $id = $_POST["id"];
}

if (!empty($_POST["name"]) && !empty($_POST["comment"])) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $match->addComment($id, $name, $comment);
    $match->listComments($id);
}

?>