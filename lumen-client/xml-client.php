<?php
$apiUrl = "http://localhost:8000/public/games/xml";
$response = @file_get_contents($apiUrl);

if ($response === false) {
    die("Gagal mengambil data dari API XML");
}

$xml = simplexml_load_string($response);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game Store – XML Client</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #171a21;
            color: #c7d5e0;
        }
        .header {
            background: #171a21;
            padding: 20px;
            border-bottom: 1px solid #2a475e;
        }
        .header h1 {
            margin: 0;
            color: #66c0f4;
        }
        .container {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: #1b2838;
            border-radius: 6px;
            padding: 15px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #66c0f4;
        }
        .price {
            margin-top: 10px;
            font-size: 16px;
            color: #a4d007;
        }
        .meta {
            font-size: 14px;
            margin-top: 5px;
            color: #8f98a0;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #8f98a0;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Game Store</h1>
    <p>XML Client – Steam-like Theme</p>
</div>

<div class="container">
    <?php foreach ($xml->game as $game): ?>
        <div class="card">
            <div class="title"><?= htmlspecialchars($game->title) ?></div>
            <div class="meta">Category: <?= htmlspecialchars($game->category) ?></div>
            <div class="meta">Developer: <?= htmlspecialchars($game->developer) ?></div>
            <div class="price">Rp <?= number_format((int)$game->price, 0, ',', '.') ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div class="footer">
    Lumen Client – Interoperability Web Service
</div>

</body>
</html>
