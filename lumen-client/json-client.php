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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roppu Store – Game Catalog</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #171a21;
            color: #c7d5e0;
        }

        /* SIMPLE TOP BAR */
        .topbar {
            height: 56px;
            background: #171a21;
            border-bottom: 1px solid #2a475e;
            display: flex;
            align-items: center;
            padding: 0 24px;
        }

        .brand {
            font-weight: bold;
            font-size: 18px;
            color: #66c0f4;
            letter-spacing: 1px;
        }

        /* HERO */
        .header {
            padding: 48px 20px;
            text-align: center;
            background: linear-gradient(135deg, #1b2838, #2a475e);
            border-bottom: 1px solid #2a475e;
        }

        .header h1 {
            margin: 0 0 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 44px;
            color: #66c0f4;
            letter-spacing: 2px;
        }

        .header p {
            margin: 0 0 24px;
            font-size: 18px;
            opacity: 0.85;
        }

        /* SEARCH & FILTER */
        .search-filter {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-filter input,
        .search-filter select {
            padding: 10px 14px;
            border-radius: 6px;
            border: none;
            background: #1b2838;
            color: #c7d5e0;
            font-size: 14px;
        }

        /* GRID */
        .container {
            padding: 32px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 26px;
        }

        /* CARD */
        .card {
            background: #1b2838;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 6px 18px rgba(0,0,0,0.35);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.55);
        }

        .cover-wrap {
            position: relative;
            height: 150px;
            overflow: hidden;
        }

        .cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease, filter .35s ease;
        }

        .card:hover .cover {
            transform: scale(1.08);
            filter: brightness(0.85);
        }

        .overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px;
            background: linear-gradient(transparent, rgba(0,0,0,.8));
        }

        .overlay .title {
            font-size: 16px;
            font-weight: bold;
            color: #e6f2ff;
        }

        /* PRICE */
        .price-badge {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: linear-gradient(135deg, #4c6b22, #a4d007);
            color: #e6f2ff;
            font-weight: bold;
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 4px;
        }

        /* INFO */
        .content {
            padding: 14px;
        }

        .category-label {
            display: inline-block;
            background: #2a475e;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
        }

        .role-badge {
            display: inline-block;
            margin-top: 6px;
            background: #3b6e8c;
            padding: 3px 8px;
            font-size: 11px;
            border-radius: 999px;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 16px;
            font-size: 12px;
            color: #8f98a0;
            border-top: 1px solid #2a475e;
        }
    </style>
</head>

<body>

<!-- TOP BAR -->
<div class="topbar">
    <div class="brand">ROPPU STORE</div>
</div>

<!-- HERO -->
<div class="header">
    <h1>Roppu Store</h1>
    <p>Game Catalog Based on Web Service Interoperability</p>

    <div class="search-filter">
        <input type="text" id="searchInput" placeholder="Search games...">
        <select id="categoryFilter">
            <option value="">All Categories</option>
        </select>
    </div>
</div>

<!-- STORE -->
<div class="container" id="gameContainer"></div>

<div class="footer">
    Roppu Store – Interoperability Web Service Project
</div>

<script>
const gamesData = <?php echo json_encode($data['data']); ?>;

const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const gameContainer = document.getElementById('gameContainer');

// Populate categories
[...new Set(gamesData.map(g => g.category.name))].forEach(cat => {
    const opt = document.createElement('option');
    opt.value = cat;
    opt.textContent = cat;
    categoryFilter.appendChild(opt);
});

function renderGames(list) {
    gameContainer.innerHTML = '';
    list.forEach(game => {
        const imageUrl = game.images?.length
            ? `http://localhost:8000/game_images/${game.images[0].image}`
            : '';

        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
            ${imageUrl ? `
            <div class="cover-wrap">
                <img src="${imageUrl}" class="cover">
                <div class="overlay">
                    <div class="title">${game.title}</div>
                </div>
            </div>` : ''}

            <div class="price-badge">Rp ${Number(game.price).toLocaleString('id-ID', { maximumFractionDigits: 0 })}</div>

            <div class="content">
                <div class="category-label">${game.category.name}</div><br>
                <div class="role-badge">Developer: ${game.developer.name}</div>
            </div>
        `;
        gameContainer.appendChild(card);
    });
}

function filterGames() {
    const s = searchInput.value.toLowerCase();
    const c = categoryFilter.value;
    renderGames(gamesData.filter(g =>
        g.title.toLowerCase().includes(s) &&
        (!c || g.category.name === c)
    ));
}

searchInput.addEventListener('input', filterGames);
categoryFilter.addEventListener('change', filterGames);

renderGames(gamesData);
</script>

</body>
</html>
