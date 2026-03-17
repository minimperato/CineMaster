<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>AI Match System</title>
    <style>
        body { background: #0a0a0a; color: #00ff41; font-family: 'Courier New', monospace; overflow-x: hidden; }
        .scanner-container { max-width: 800px; margin: 50px auto; text-align: center; }
        
        /* Animazione Scanner */
        .scan-line {
            width: 100%; height: 2px; background: #00ff41;
            position: fixed; top: 0; left: 0;
            box-shadow: 0 0 15px #00ff41;
            animation: scan 3s linear infinite;
            z-index: 100;
        }
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }

        .loading-text { font-size: 1.2rem; margin-bottom: 30px; }
        
        .results { display: none; } /* Nascosti all'inizio */

        .match-card {
            background: rgba(0, 255, 65, 0.05);
            border: 1px solid #00ff41;
            padding: 20px; border-radius: 10px;
            display: flex; gap: 20px; align-items: center;
            margin-bottom: 20px; text-align: left;
            transition: 0.3s;
        }
        .match-card:hover { background: rgba(0, 255, 65, 0.15); box-shadow: 0 0 20px rgba(0, 255, 65, 0.4); }
        .match-card img { width: 100px; border-radius: 5px; border: 1px solid #00ff41; }
        .score { font-size: 2rem; font-weight: bold; color: #fff; }
    </style>
</head>
<body>

<div class="scan-line" id="line"></div>

<div class="scanner-container">
    <div id="loader">
        <h1>AI MATCH SYSTEM v2.0</h1>
        <p class="loading-text" id="status">INIZIALIZZAZIONE SCANSIONE DATABASE...</p>
        <p>[OK] Accesso a vista_match_perfetto...</p>
        <p id="p2" style="display:none;">[OK] Analisi gusti di <?= $username ?>...</p>
        <p id="p3" style="display:none;">[OK] Incrocio dati globali community...</p>
    </div>

    <div class="results" id="results">
        <h1 style="color: #fff;">MATCH TROVATI CON SUCCESSO</h1>
        <?php foreach($suggerimenti as $f): ?>
            <div class="match-card">
                <div class="score"><?= rand(94, 99) ?>%</div>
                <img src="<?= base_url($f['copertina']) ?>">
                <div>
                    <h2 style="margin:0;"><?= esc($f['titolo']) ?></h2>
                    <p style="margin:5px 0;">Genere: <?= $f['genere'] ?> | Media: <?= number_format($match['media_voti'], 1) ?> ★
                    <a href="<?= base_url('index.php/films/view/'.$f['id']) ?>" style="color:#00ff41;">APRI SCHEDA ></a>
                </div>
            </div>
        <?php endforeach; ?>
        <br>
        <a href="<?= base_url('index.php/user/profile') ?>" style="color:#666;">CHIUDI TERMINALE</a>
    </div>
</div>

<script>
    // Script per simulare la scansione "hacker"
    setTimeout(() => { document.getElementById('p2').style.display = 'block'; }, 1000);
    setTimeout(() => { document.getElementById('p3').style.display = 'block'; }, 2000);
    setTimeout(() => { 
        document.getElementById('loader').style.display = 'none';
        document.getElementById('line').style.display = 'none';
        document.getElementById('results').style.display = 'block';
        document.body.style.overflow = 'auto';
    }, 3500);
</script>

</body>
</html>