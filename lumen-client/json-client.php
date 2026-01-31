<?php

$apiUrl = "http://localhost:8000/public/games";

$response = @file_get_contents($apiUrl);

if ($response === false) {
    die("Gagal mengambil data dari API");
}

$data = json_decode($response, true);

if (!isset($data['data'])) {
    die("Format data tidak sesuai");
}

echo "<h2>Game List (JSON Client)</h2>";

foreach ($data['data'] as $game) {
    echo "<hr>";
    echo "<b>Title:</b> {$game['title']}<br>";
    echo "<b>Price:</b> {$game['price']}<br>";
    echo "<b>Category:</b> {$game['category']['name']}<br>";
    echo "<b>Developer:</b> {$game['developer']['name']}<br>";
}
