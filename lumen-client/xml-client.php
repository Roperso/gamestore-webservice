<?php

$apiUrl = "http://localhost:8000/public/games/xml";

$response = file_get_contents($apiUrl);
$xml = simplexml_load_string($response);

echo "<h2>Game List (XML Client)</h2>";

foreach ($xml->game as $game) {
    echo "<hr>";
    echo "<b>Title:</b> {$game->title}<br>";
    echo "<b>Price:</b> {$game->price}<br>";
    echo "<b>Category:</b> {$game->category}<br>";
    echo "<b>Developer:</b> {$game->developer}<br>";
}
