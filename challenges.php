<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sfide CineMaster</title>
    <style>
        body { background-color: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; padding-bottom: 50px; }
        .navbar { padding: 20px 5%; background: #111; border-bottom: 1px solid #222; }
        .container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        
        h1 { font-size: 36px; text-transform: uppercase; letter-spacing: 2px; color: #e50914; text-align: center; }
        .subtitle { color: #666; text-align: center; margin-bottom: 50px; }

        /* Stile Titolo Livelli - SEMPLICE SENZA SFUMATURA ROSSA */
        .level-header { 
            padding: 10px 0;
            border-bottom: 1px solid #333; /* Una semplice linea grigia */
            margin: 40px 0 20px;
            font-size: 22px;
            text-transform: uppercase;
            font-weight: bold;
            color: #fff; /* Scritta bianca pulita */
        }

        .challenges-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
            gap: 20px; 
            margin-bottom: 40px;
        }
        
        /* Card Sfida (Esattamente come all'inizio) */
        .challenge-card {
            background: #141414;
            padding: 25px;
            border-radius: 15px;
            border: 2px solid #222;
            transition: 0.4s;
            position: relative;
            text-align: center;
        }

        /* Sfida BLOCCATA */
        .challenge-card.locked {
            filter: grayscale(1);
            opacity: 0.4;
        }

        /* Sfida COMPLETATA */
        .challenge-card.completed {
            filter: grayscale(0);
            opacity: 1;
            border-color: #e50914;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.4);
            background: #1a1a1a;
        }

        .icon { font-size: 45px; margin-bottom: 15px; display: block; transition: 0.3s; }
        .challenge-card.completed .icon { transform: scale(1.1); }
        
        .title { font-weight: bold; font-size: 17px; display: block; margin-bottom: 8px; color: #fff; }
        .desc { font-size: 12px; color: #aaa; margin: 0; }

        .badge-status {
            position: absolute; top: 10px; right: 10px;
            font-size: 9px; font-weight: bold; padding: 3px 8px; border-radius: 10px;
            background: #333; color: #888;
        }
        .completed .badge-status { background: #e50914; color: white; }

    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= base_url('index.php/films') ?>" style="color:#e50914; text-decoration:none; font-weight:bold;">← TORNA AL CINEMA</a>
</nav>

<div class="container">
    <h1>Le tue Sfide</h1>
    <p class="subtitle">Completa un livello per sbloccare le sfide successive!</p>

    <?php if (empty($livelli)): ?>
        <p style="text-align:center; color: #666;">Qualcosa è andato storto nel caricamento dei livelli.</p>
    <?php else: ?>
        
        <?php foreach ($livelli as $nomeLivello => $sfide): ?>
            <div class="level-header"><?= esc($nomeLivello) ?></div>
            
            <div class="challenges-grid">
                <?php foreach($sfide as $s): ?>
                    <div class="challenge-card <?= $s['completed'] ? 'completed' : 'locked' ?>">
                        <div class="badge-status">
                            <?= $s['completed'] ? 'SBLOCCATO' : 'BLOCCATO' ?>
                        </div>
                        <span class="icon"><?= $s['icon'] ?></span>
                        <span class="title"><?= esc($s['title']) ?></span>
                        <p class="desc"><?= esc($s['desc'] ?? 'Recensisci i film per completare questa sfida') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <div style="margin-top: 80px; text-align: center;">
        <a href="<?= base_url('index.php/user/diario') ?>" style="color: #666; text-decoration: none; border: 1px solid #333; padding: 10px 20px; border-radius: 20px;">Vai al diario per vedere i tuoi progressi →</a>
    </div>
</div>

</body>
</html>