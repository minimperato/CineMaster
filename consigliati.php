<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { background-color: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; }
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        .section-header { margin-bottom: 25px; border-left: 4px solid #e50914; padding-left: 15px; }
        .section-header h2 { margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 20px; }
        .section-header p { color: #888; margin: 5px 0 0; font-size: 14px; }

        /* Stile per i consigli degli amici */
        .friend-advice-scroll { 
            display: flex; 
            gap: 20px; 
            overflow-x: auto; 
            padding-bottom: 20px; 
            margin-bottom: 40px;
            scrollbar-width: thin;
            scrollbar-color: #e50914 #111;
        }
        .advice-card { 
            background: #111; 
            padding: 20px; 
            border-radius: 12px; 
            border: 1px solid #e50914; 
            min-width: 280px; 
            max-width: 280px;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.15);
        }

        /* Grid per i Match della Community */
        .match-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 50px; }
        .match-card { 
            background: #121212; border: 1px solid #222; border-radius: 10px; padding: 15px; 
            display: flex; gap: 20px; align-items: center; transition: 0.3s;
        }
        .match-card:hover { border-color: #e50914; background: #1a1a1a; transform: translateY(-3px); }
        .match-card img { width: 70px; height: 100px; object-fit: cover; border-radius: 4px; }
        .match-card h3 { margin: 0; font-size: 16px; }
        .match-rating { color: #f1c40f; font-weight: bold; font-size: 14px; margin: 5px 0; }

        /* Grid per le Scoperte */
        .grid-films { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 25px; }
        .film-box { text-decoration: none; color: white; transition: 0.3s; }
        .film-box:hover { transform: scale(1.05); }
        .film-box img { width: 100%; border-radius: 6px; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .film-box p { font-size: 13px; font-weight: bold; text-align: center; margin-top: 8px; color: #ccc; }
    </style>
</head>
<body>

<div class="container">
    <h1 style="color: #e50914; font-size: 28px; margin-bottom: 40px;">IL TUO CINEMA PERSONALE</h1>

    <div class="section-header" style="border-left-color: #ffffff;">
        <h2 style="color: #ffffff;"> Suggeriti dai tuoi Amici</h2>
        <p>Film scelti apposta per te dalla tua cerchia.</p>
    </div>

    <div class="friend-advice-scroll">
        <?php if(empty($consigli_amici)): ?>
            <p style="color: #444; font-style: italic;">Nessun consiglio dai tuoi amici per ora. Vai nella community per scambiarvi suggerimenti!</p>
        <?php else: ?>
            <?php foreach($consigli_amici as $ca): ?>
                <div class="advice-card">
                    <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                        <img src="<?= base_url($ca['copertina']) ?>" style="width: 60px; height: 85px; object-fit: cover; border-radius: 4px; border: 1px solid #333;">
                        <div>
                            <span style="font-size: 11px; color: #888; text-transform: uppercase;">Da <b><?= esc($ca['da_username']) ?></b></span><br>
                            <b style="font-size: 15px; display: block; margin-top: 3px;"><?= esc($ca['titolo']) ?></b>
                        </div>
                    </div>
                    <div style="background: #080808; padding: 10px; border-radius: 6px; border-left: 2px solid #e50914;">
                        <p style="font-size: 13px; color: #ccc; font-style: italic; margin: 0; line-height: 1.4;">
                            "<?= esc($ca['messaggio']) ?>"
                        </p>
                    </div>
                    <a href="<?= base_url('index.php/films/view/'.$ca['film_id']) ?>" style="display: block; text-align: center; margin-top: 15px; font-size: 11px; color: #e50914; text-decoration: none; font-weight: bold; text-transform: uppercase;">Guarda Scheda</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <hr style="border: 0; border-top: 1px solid #222; margin-bottom: 50px;">

    <div class="section-header">
        <h2>Match dalla Community</h2>
        <p>I titoli più amati dagli altri utenti che potrebbero piacerti.</p>
    </div>

    <div class="match-grid">
        <?php foreach($community_match as $f): ?>
            <div class="match-card">
                <img src="<?= base_url($f['copertina']) ?>" alt="<?= esc($f['titolo']) ?>">
                <div>
                    <h3><?= esc($f['titolo']) ?></h3>
                    <div class="match-rating">Media Community: <?= $f['media_community'] ?> ★</div>
                    <a href="<?= base_url('index.php/films/view/'.$f['id']) ?>" style="color: #e50914; text-decoration: none; font-size: 11px; font-weight: bold;">DETTAGLI SCHEDA</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="section-header" style="border-left-color: #444;">
        <h2>Esplora Nuovi Orizzonti</h2>
        <p>Altri film scelti casualmente dal catalogo CineMaster.</p>
    </div>

    <div class="grid-films">
        <?php foreach($scoperte as $f): ?>
            <a href="<?= base_url('index.php/films/view/'.$f['id']) ?>" class="film-box">
                <img src="<?= base_url($f['copertina']) ?>" alt="<?= esc($f['titolo']) ?>">
                <p><?= esc($f['titolo']) ?></p>
            </a>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 60px; text-align: center; border-top: 1px solid #222; padding-top: 20px;">
        <a href="<?= base_url('index.php/user/profile') ?>" style="color: #666; text-decoration: none; font-size: 13px;">← Torna al tuo Profilo</a>
    </div>
</div>

</body>
</html>