<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Profilo Cinema - <?= esc($username) ?></title>
    <style>
        body { background-color: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; }
        .navbar { padding: 20px 5%; display: flex; align-items: center; background: #111; border-bottom: 1px solid #222; }
        .brand { color: #e50914; font-size: 25px; font-weight: bold; text-decoration: none; letter-spacing: 2px; }
        
        .container { max-width: 900px; margin: 50px auto; padding: 0 20px; }
        
        .profile-header { text-align: center; margin-bottom: 30px; }
        .avatar { 
            width: 120px; height: 120px; background: #e50914; border-radius: 50%; 
            margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; 
            font-size: 50px; font-weight: bold; text-transform: uppercase;
            box-shadow: 0 0 25px rgba(229, 9, 20, 0.4); border: 4px solid #fff;
            flex-shrink: 0;
        }

        .social-container { margin-bottom: 40px; text-align: center; }
        .social-row { display: flex; justify-content: center; gap: 40px; margin-bottom: 15px; }
        .social-stat { text-align: center; }
        .social-stat b { display: block; font-size: 22px; color: #fff; }
        .social-stat span { font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 1px; }

        .following-stack { display: flex; justify-content: center; padding-left: 12px; margin-top: 10px; }
        .mini-avatar {
            width: 42px; height: 42px; border-radius: 50%; background: #222;
            border: 3px solid #080808; display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: bold; color: #fff; margin-left: -12px;
            transition: 0.3s; text-decoration: none; position: relative;
        }
        .mini-avatar:hover { transform: translateY(-5px); z-index: 10; border-color: #e50914; }
        .more-circle { background: #111; color: #444; font-size: 10px; }

        .analysis-box {
            background: #111; padding: 25px; border-radius: 12px; border-left: 4px solid #e50914;
            margin: 30px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.5); text-align: left;
        }
        .analysis-box h3 { color: #e50914; text-transform: uppercase; font-size: 13px; margin-top: 0; letter-spacing: 2px; }
        .analysis-text { color: #ccc; line-height: 1.6; font-size: 16px; margin: 0; }

        .stats-container { display: flex; justify-content: center; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .stat-ticket {
            background: #e50914; color: white; padding: 15px 25px; border-radius: 8px;
            text-align: center; box-shadow: 5px 5px 0px #800; transform: rotate(-1deg); min-width: 100px;
        }
        .stat-ticket.alt { background: #fff; color: #000; box-shadow: 5px 5px 0px #ccc; transform: rotate(1deg); }
        .stat-ticket label { display: block; font-size: 9px; text-transform: uppercase; opacity: 0.8; }
        .stat-ticket span { display: block; font-size: 24px; font-weight: bold; }

        .progress-card { background: #141414; padding: 20px; border-radius: 10px; margin-bottom: 40px; border: 1px solid #222; }
        .progress-bg { background: #333; height: 8px; border-radius: 4px; width: 100%; overflow: hidden; margin: 12px 0; }
        .progress-bar { background: linear-gradient(90deg, #e50914, #ff4d4d); height: 100%; transition: width 1s; }

        .card { background: #141414; padding: 25px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #222; }
        .card h3 { margin-top: 0; color: #fff; text-transform: uppercase; font-size: 16px; border-bottom: 1px solid #222; padding-bottom: 10px; }
        .fav-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px; }
        .fav-item img { width: 100%; border-radius: 4px; border: 2px solid #333; height: 190px; object-fit: cover; transition: 0.3s; }
        .fav-item img:hover { border-color: #e50914; transform: scale(1.05); }

        .btn-toggle { background: transparent; color: #666; border: 1px solid #333; padding: 8px 15px; cursor: pointer; border-radius: 4px; font-size: 12px; }
        .btn-red { background: #e50914; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .alert-success { background: #2ecc71; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
        .btn-back { color: #444; text-decoration: none; font-size: 13px; display: block; text-align: center; margin-top: 40px; text-transform: uppercase; }
        input { background:#000; color:#fff; border:1px solid #333; padding:12px; width:100%; border-radius:4px; box-sizing: border-box; margin-top: 5px; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= base_url('index.php/films') ?>" class="brand">CINEMASTER</a>
</nav>

<div class="container">

    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert-success"><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>

    <div class="profile-header">
        <div class="avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
        <h1 style="margin: 0; font-size: 38px;"><?= esc($username) ?></h1>
        <p style="color: #e50914; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; font-size: 11px; margin-top: 5px;">Cinephile Elite</p>
    </div>

    <div class="social-container">
        <div class="social-row">
            <div class="social-stat">
                <b><?= $seguiti_count ?></b>
                <span>Seguiti</span>
            </div>
            <div class="social-stat">
                <b><?= $followers_count ?></b>
                <span>Follower</span>
            </div>
        </div>

        <?php if(!empty($seguiti_lista)): ?>
            <div class="following-stack">
                <?php 
                $count = 0;
                foreach($seguiti_lista as $s): 
                    if($count < 6): ?>
                        <a href="<?= base_url('index.php/user/viewProfile/'.$s['followed_username']) ?>" 
                           class="mini-avatar" title="<?= esc($s['followed_username']) ?>">
                            <?= strtoupper(substr($s['followed_username'], 0, 1)) ?>
                        </a>
                    <?php endif; $count++;
                endforeach; ?>

                <?php if($seguiti_count > 6): ?>
                    <div class="mini-avatar more-circle">+<?= $seguiti_count - 6 ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="analysis-box">
        <h3> Analisi Critica</h3>
        <p class="analysis-text"><?= $profilo_critico ?></p>
    </div>

    <div class="stats-container">
        <div class="stat-ticket">
            <label>Watchlist</label>
            <span><?= $total_watchlist ?></span> 
        </div>
        <div class="stat-ticket alt">
            <label>Recensioni</label>
            <span><?= $total_reviews ?></span>
        </div>
        <div class="stat-ticket">
            <label>Livello</label>
            <span style="font-size: 14px; margin-top: 8px;"><?= strtoupper($level_name) ?></span>
        </div>
    </div>

    <div class="progress-card">
        <div style="display: flex; justify-content: space-between; font-size: 10px; color: #666; text-transform: uppercase;">
            <span>Grado Carriera</span>
            <span style="color: #e50914; font-weight: bold;"><?= round($progresso) ?>%</span>
        </div>
        <div class="progress-bg">
            <div class="progress-bar" style="width: <?= $progresso ?>%;"></div>
        </div>
    </div>

    <div class="card">
        <h3>Impostazioni Account</h3>
        <p style="font-size: 14px; color: #888;">Email attuale: <b><?= esc(session()->get('email') ?? 'Non impostata') ?></b></p>
        
        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn-toggle" onclick="toggleEdit()">Modifica Email</button>
            <button onclick="confermaEliminazione()" class="btn-toggle" style="color: #e50914; border-color: rgba(229,9,20,0.3);">Elimina Profilo</button>
        </div>
        
        <div id="editForm" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid #222;">
            <form action="<?= base_url('index.php/user/updateEmail') ?>" method="post">
                <?= csrf_field() ?> 
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 12px; color: #666;">NUOVA EMAIL:</label><br>
                    <input type="email" name="email" required placeholder="Inserisci la nuova email...">
                </div>
                <button type="submit" class="btn-red">SALVA MODIFICHE</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h3>La mia Lista </h3>
        <div class="fav-grid">
            <?php if(empty($watchlist_films)): ?>
                <p style="color: #333; font-size: 13px;">Nessun film salvato.</p>
            <?php else: ?>
                <?php foreach($watchlist_films as $film): ?>
                    <div class="fav-item">
                        <a href="<?= base_url('index.php/films/view/'.$film['id']) ?>">
                            <img src="<?= base_url($film['copertina']) ?>" title="<?= esc($film['titolo']) ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h3>Collezione Preferiti </h3>
        <div class="fav-grid">
            <?php if(empty($favorites)): ?>
                <p style="color: #333; font-size: 13px;">Ancora nessun preferito.</p>
            <?php else: ?>
                <?php foreach($favorites as $fav): ?>
                    <div class="fav-item">
                        <a href="<?= base_url('index.php/films/view/'.$fav['id']) ?>">
                            <img src="<?= base_url($fav['copertina']) ?>" title="<?= esc($fav['titolo']) ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <a href="<?= base_url('index.php/films') ?>" class="btn-back">← Torna al Cinema</a>
</div>

<script>
    function toggleEdit() {
        var f = document.getElementById("editForm");
        f.style.display = (f.style.display === "none") ? "block" : "none";
    }

    function confermaEliminazione() {
        if (confirm("Sei sicura? Perderai tutte le tue recensioni e i tuoi consigli. L'azione è irreversibile.")) {
            window.location.href = "<?= base_url('index.php/user/eliminaProfilo') ?>";
        }
    }
</script>

</body>
</html>