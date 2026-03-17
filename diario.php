<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>CineMaster - Diario di <?= esc($username) ?></title>
    <style>
        body { background-color: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; }
        .navbar { padding: 15px 5%; background: #111; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #222; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        
        /* DASHBOARD STATISTICHE */
        .stats-dashboard { 
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; 
            margin-bottom: 40px; background: #141414; padding: 25px; border-radius: 12px; border: 1px solid #333;
        }
        .stat-card { text-align: center; border-right: 1px solid #222; }
        .stat-card:last-child { border-right: none; }
        .stat-card small { color: #666; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .stat-card div { font-size: 22px; font-weight: bold; color: #e50914; margin-top: 5px; }

        /* LIVELLO BADGE */
        .rank-badge {
            background: linear-gradient(45deg, #e50914, #800);
            padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold;
        }

        /* REWATCH BADGE */
        .rewatch-icon { color: #2ecc71; font-size: 12px; margin-left: 10px; border: 1px solid #2ecc71; padding: 2px 6px; border-radius: 4px; font-weight: bold; }

        /* LISTA RECENSIONI */
        .review-entry { 
            display: flex; gap: 25px; background: #111; padding: 20px; border-radius: 8px; 
            margin-bottom: 20px; border-left: 4px solid #333; transition: 0.3s;
        }
        /* Se il film è da rivedere, il bordo diventa verde */
        .review-entry.is-rewatch { border-left-color: #2ecc71; }
        
        .review-entry:hover { border-left-color: #e50914; background: #161616; }
        .poster { width: 100px; height: 150px; object-fit: cover; border-radius: 4px; }
        .stars { color: #e50914; margin-bottom: 10px; }
        .comment { font-style: italic; color: #bbb; margin: 10px 0; font-size: 15px; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="<?= base_url('index.php/films') ?>" style="color:#e50914; text-decoration:none; font-weight:bold; font-size:20px;">CINEMASTER</a>
    <span class="rank-badge">
        <?php 
            if($total_count <= 5) echo "🎬 SPETTATORE";
            elseif($total_count <= 15) echo "✍️ CRITICO EMERGENTE";
            else echo "🏆 LEGGENDA DEL CINEMA";
        ?>
    </span>
</nav>

<div class="container">
    <div class="stats-dashboard">
        <div class="stat-card">
            <small>Media Voto</small>
            <div>★ <?= $media_voto ?></div>
        </div>
        <div class="stat-card">
            <small>Film Recensiti</small>
            <div><?= $total_count ?></div>
        </div>
        <div class="stat-card">
            <small>Genere Preferito</small>
            <div style="color: #fff; font-size: 18px;"><?= strtoupper($genere_pref) ?></div>
        </div>
        <div class="stat-card">
            <small>Stato Diario</small>
            <div style="color: #2ecc71; font-size: 18px;">ATTIVO</div>
        </div>
    </div>

    <h1>Cronologia Visioni</h1>

    <?php foreach($recensioni as $r): ?>
        <div class="review-entry <?= (isset($r['rewatch']) && $r['rewatch'] == 1) ? 'is-rewatch' : '' ?>">
            <img src="<?= base_url($r['copertina']) ?>" class="poster">
            <div style="flex:1;">
                <div style="display:flex; align-items:center;">
                    <h3 style="margin:0;"><?= esc($r['titolo']) ?></h3>
                    
                    <?php if(isset($r['rewatch']) && $r['rewatch'] == 1): ?>
                        <span class="rewatch-icon">↻ RIVEDREI</span>
                    <?php endif; ?>

                </div>
                <div class="stars"><?= str_repeat('★', $r['voto']) ?><?= str_repeat('☆', 5-$r['voto']) ?></div>
                <div class="comment">"<?= esc($r['commento']) ?>"</div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <small style="color:#555;"><?= date('d M Y', strtotime($r['data_recensione'])) ?></small>
                    <a href="<?= base_url('index.php/user/deleteReview/'.$r['id']) ?>" 
                       onclick="return confirm('Vuoi davvero eliminare questa pagina del diario?')" 
                       style="color:#666; font-size:10px; text-decoration:none;">[ ELIMINA ]</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>