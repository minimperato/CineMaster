<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineMaster - Home</title>
    <style>
        body { background-color: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; overflow-x: hidden; }
        
        /* SIDEBAR */
        .sidebar { height: 100%; width: 0; position: fixed; z-index: 1000; top: 0; left: 0; background-color: #111; overflow-x: hidden; transition: 0.5s; padding-top: 60px; box-shadow: 5px 0 15px rgba(0,0,0,0.5); }
        .sidebar .menu-label { padding: 10px 32px; font-size: 11px; color: #555; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; }
        .sidebar a { padding: 12px 32px; text-decoration: none; font-size: 14px; color: #bbb; display: block; transition: 0.3s; text-transform: uppercase; }
        .sidebar a:hover { color: #fff; background-color: #e50914; padding-left: 40px; }
        .sidebar .closebtn { position: absolute; top: 10px; right: 25px; font-size: 36px; cursor: pointer; }

        .badge-notifica {
            background: #e50914; color: white; border-radius: 50%; padding: 2px 7px;
            font-size: 10px; margin-left: 8px; font-weight: bold;
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(229, 9, 20, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(229, 9, 20, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(229, 9, 20, 0); }
        }

        /* NAVBAR */
        .navbar { position: absolute; width: 100%; z-index: 100; padding: 20px 5%; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, transparent 100%); box-sizing: border-box; }
        .nav-left { display: flex; align-items: center; gap: 20px; }
        .open-btn { font-size: 30px; cursor: pointer; color: white; }
        .brand { color: #e50914; font-size: 30px; font-weight: bold; text-decoration: none; letter-spacing: 2px; }

        /* HERO SECTION (ORIGINALE) */
        .hero { height: 75vh; position: relative; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 0 5%; overflow: hidden; }
        .hero::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('<?= base_url("uploads/film_cover/sfondo_hero.jpg") ?>'); background-size: cover; background-position: center; filter: blur(5px) brightness(0.6); z-index: -1; }
        .hero::after { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to top, rgba(8,8,8,1) 0%, rgba(8,8,8,0.1) 100%); z-index: -1; }
        .hero-content h1 { font-size: 75px; margin: 0; font-weight: 900; text-transform: uppercase; line-height: 0.9; }
        .hero-content p { font-size: 15px; color: #bbb; margin: 10px 0 25px; text-transform: uppercase; letter-spacing: 2px; }

        /* BOX CONSIGLIO DEL GIORNO (NUOVO STILE) */
        .daily-suggestion { margin: -50px 5% 40px; position: relative; z-index: 10; background: #111; border-radius: 12px; border: 1px solid #222; overflow: hidden; display: flex; align-items: center; box-shadow: 0 15px 40px rgba(0,0,0,0.6); }
        .suggestion-img { width: 180px; height: 250px; object-fit: cover; }
        .suggestion-body { padding: 30px; flex: 1; }
        .suggestion-body h2 { margin: 0; font-size: 28px; text-transform: uppercase; color: #fff; }
        .suggestion-body p { color: #888; margin: 10px 0 20px; font-size: 14px; }

        /* FILTRI */
        .filter-section { padding: 40px 5%; background: #080808; text-align: center; border-bottom: 1px solid #1a1a1a; }
        .filter-form { display: flex; gap: 15px; align-items: flex-end; justify-content: center; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; text-align: left; }
        .filter-group label { font-size: 10px; color: #555; text-transform: uppercase; font-weight: bold; }
        .filter-group input, .filter-group select { background: #121212; color: white; border: 1px solid #333; padding: 12px; border-radius: 4px; min-width: 160px; }
        .btn-red { background: #e50914; color: white; border: none; padding: 12px 30px; font-weight: bold; cursor: pointer; border-radius: 4px; text-transform: uppercase; transition: 0.3s; text-decoration: none; display: inline-block; }

        /* CATALOGO */
        .catalog { padding: 40px 5% 60px; }
        .film-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }
        .film-card { background: #141414; border-radius: 4px; overflow: hidden; transition: 0.4s; border: 1px solid #222; text-decoration: none; color: white; display: block; }
        .film-card:hover { transform: translateY(-10px); border-color: #e50914; box-shadow: 0 10px 20px rgba(0,0,0,0.5); }
        .film-card img { width: 100%; height: 320px; object-fit: cover; }
        .film-info { padding: 15px; }
    </style>
</head>
<body>

<?php  
    $db = \Config\Database::connect();
    $me = session()->get('username');
    
    // Query pulita per contare solo i messaggi effettivamente non letti
    $notifiche = $db->table('consigli')
                    ->where('a_username', (string)$me)
                    ->where('letto', false) 
                    ->countAllResults();
?>

<div id="mySidebar" class="sidebar">
    <span class="closebtn" onclick="closeNav()">&times;</span>
    <div class="menu-label">Il Tuo Profilo</div>
    <a href="<?= base_url('index.php/user/profile') ?>">Profilo Personale</a>
    <a href="<?= base_url('index.php/user/diario') ?>">Diario Recensioni</a>
    <div class="menu-label" style="margin-top:20px;">Cinema Pro</div>
    <a href="<?= base_url('index.php/user/challenges') ?>">Sfide del Mese</a>
    <a href="<?= base_url('index.php/user/stats') ?>">Radar del Gusto</a>
    <a href="<?= base_url('index.php/user/consigliati') ?>">Consigliati <?php if($notifiche > 0): ?><span class="badge-notifica"><?= $notifiche ?></span><?php endif; ?></a>
    <a href="<?= base_url('index.php/user/community') ?>">Scopri la Community</a>
    <div class="menu-label" style="margin-top:20px;">Sistema</div>
    <a href="<?= base_url('index.php/logout') ?>" style="color: #e50914;">Logout</a>
</div>

<nav class="navbar">
    <div class="nav-left">
        <span class="open-btn" onclick="openNav()">&#9776;</span>
        <a href="<?= base_url('index.php/films') ?>" class="brand">CINEMASTER</a>
    </div>
    <div style="font-size: 12px; color: #666; text-transform: uppercase;">Bentornata, <?= esc($username) ?></div>
</nav>

<div class="hero">
    <div class="hero-content">
        <h1>SCOPRI IL CINEMA</h1>
        <p>Dove le storie prendono vita</p>
        <a href="#catalogo" class="btn-red">Sfoglia Catalogo</a>
    </div>
</div>

<?php if(isset($film_del_giorno)): ?>
<div class="container">
    <div class="daily-suggestion">
        <img src="<?= base_url($film_del_giorno->copertina) ?>" class="suggestion-img">
        <div class="suggestion-body">
            <span style="color: #e50914; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">Consiglio del Giorno </span>
            <h2><?= esc($film_del_giorno->titolo) ?></h2>
            <p>Oggi ti suggeriamo questo titolo del genere <b><?= $film_del_giorno->genere ?></b>. Non perdertelo!</p>
            <a href="<?= base_url('index.php/films/view/'.$film_del_giorno->id) ?>" class="btn-red" style="padding: 10px 20px; font-size: 13px;">▶ Guarda Scheda</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="filter-section" id="catalogo">
    <form action="<?= base_url('index.php/films') ?>" method="get" class="filter-form">
        <div class="filter-group">
            <label>Ricerca</label>
            <input type="text" name="titolo" placeholder="Titolo..." value="<?= esc($_GET['titolo'] ?? '') ?>">
        </div>
        <div class="filter-group">
            <label>Genere</label>
            <select name="genere">
                <option value="">Tutti i generi</option>
                <?php foreach($generi as $g): ?>
                    <option value="<?= esc($g['genere']) ?>" <?= (isset($_GET['genere']) && $_GET['genere'] == $g['genere']) ? 'selected' : '' ?>><?= esc($g['genere']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Anno</label>
            <select name="anno">
                <option value="">Tutti gli anni</option>
                <?php foreach($anni as $a): ?>
                    <option value="<?= esc($a['anno']) ?>" <?= (isset($_GET['anno']) && $_GET['anno'] == $a['anno']) ? 'selected' : '' ?>><?= esc($a['anno']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-red">Filtra</button>
    </form>
</div>

<div class="catalog">
    <div class="film-grid">
        <?php foreach ($films as $film): ?>
            <a href="<?= base_url('index.php/films/view/'.$film['id']) ?>" class="film-card">
                <img src="<?= base_url($film['copertina']) ?>" alt="<?= esc($film['titolo']) ?>">
                <div class="film-info">
                    <div style="font-weight: bold; text-transform: uppercase; font-size: 14px;"><?= esc($film['titolo']) ?></div>
                    <div style="font-size: 11px; color: #666; margin-top: 5px;"><?= esc($film['genere']) ?> | <?= esc($film['anno']) ?></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function openNav() { document.getElementById("mySidebar").style.width = "280px"; }
    function closeNav() { document.getElementById("mySidebar").style.width = "0"; }
</script>

</body>
</html>