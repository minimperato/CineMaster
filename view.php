<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?= esc($film['titolo']) ?> - CineMaster</title>
    <style>
        body { background-color: #080808; color: #fff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; }
        
        /* NOTIFICA POPUP SFIDA */
        .challenge-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e50914;
            color: white;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 9999;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s 4s forwards;
            border: 2px solid #fff;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; visibility: hidden; } }

        /* HERO SECTION */
        .film-backdrop {
            position: relative;
            height: 70vh;
            width: 100%;
            background: linear-gradient(to bottom, rgba(8,8,8,0) 0%, rgba(8,8,8,0.6) 50%, #080808 100%), 
                        url('<?= base_url($film['copertina']) ?>');
            background-size: cover;
            background-position: center 20%;
            display: flex;
            align-items: flex-end;
            padding: 0 5% 60px;
            box-sizing: border-box;
        }

        .hero-text h1 { font-size: 70px; margin: 0; text-transform: uppercase; letter-spacing: -2px; line-height: 1; }
        .hero-meta { margin-bottom: 15px; font-size: 18px; color: #ccc; display: flex; gap: 20px; align-items: center; }
        .rating-badge { background: #e50914; color: #fff; padding: 2px 10px; border-radius: 3px; font-weight: bold; font-size: 14px; }

        /* BOTTONI */
        .actions-bar { display: flex; gap: 15px; margin-top: 30px; }
        .btn-netflix {
            background: #fff; color: #000; padding: 12px 25px; border-radius: 4px; 
            font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 10px;
            transition: 0.2s; border: none; cursor: pointer;
        }
        .btn-netflix:hover { background: rgba(255,255,255,0.75); transform: scale(1.05); }
        .btn-secondary { background: rgba(109, 109, 110, 0.7); color: #fff; backdrop-filter: blur(5px); }

        /* LAYOUT */
        .main-content { padding: 40px 5%; display: grid; grid-template-columns: 2fr 1fr; gap: 60px; }
        .section-label { 
            text-transform: uppercase; color: #666; letter-spacing: 2px; font-size: 13px; 
            font-weight: bold; margin-bottom: 20px; display: block; border-bottom: 1px solid #222; padding-bottom: 10px;
        }

        /* DIARIO & REWATCH */
        .diary-section { background: #111; border: 1px solid #222; padding: 25px; border-radius: 8px; margin-bottom: 40px; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; margin: 15px 0; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 30px; color: #333; cursor: pointer; }
        .star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label { color: #e50914; }
        textarea { width: 100%; background: #000; border: 1px solid #333; color: #fff; padding: 15px; border-radius: 5px; box-sizing: border-box; }

        /* COMMUNITY REVIEWS */
        .review-card { 
            background: #161616; padding: 20px; border-radius: 8px; margin-bottom: 15px; 
            border-left: 4px solid #333; transition: 0.3s;
        }
        .review-card:hover { border-left-color: #e50914; background: #1a1a1a; }
        .review-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .review-user { color: #e50914; font-weight: bold; text-transform: uppercase; font-size: 13px; }
        .review-stars { color: #e50914; letter-spacing: 2px; }

        .info-card { background: #111; padding: 25px; border-radius: 8px; border-top: 3px solid #e50914; }
    </style>
</head>
<body>
<?php if(session()->getFlashdata('successo_eliminazione')): ?>
    <div style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border: 1px solid #2ecc71; animation: fadeOut 0.5s 3s forwards;">
        <?= session()->getFlashdata('successo_eliminazione') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('errore')): ?>
    <div style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border: 1px solid #c0392b;">
        <?= session()->getFlashdata('errore') ?>
    </div>
<?php endif; ?>
<?php if(session()->getFlashdata('challenge_unlocked')): ?>
    <div class="challenge-popup">
        <span style="font-size: 30px;">🏆</span>
        <div>
            <p style="margin: 0; font-weight: bold; font-size: 16px;">SFIDA COMPLETATA!</p>
            <p style="margin: 0; font-size: 14px; opacity: 0.9;"><?= session()->getFlashdata('challenge_unlocked') ?></p>
        </div>
    </div>
<?php endif; ?>

<div class="film-backdrop">
    <div class="hero-text">
        <div class="hero-meta">
            <span class="rating-badge">TOP 10</span>
            <span><?= esc($film['anno']) ?></span>
            <span><?= esc($film['durata'] ?? '2h 15m') ?></span>
            <span style="border: 1px solid #555; padding: 0 5px; font-size: 12px;">HD</span>
        </div>
        <h1><?= esc($film['titolo']) ?></h1>
        
        <div class="actions-bar">
            <a href="<?= base_url('index.php/user/toggleWatchlist/' . $film['id']) ?>" class="btn-netflix">
                + La mia lista
            </a>
            <a href="<?= base_url('index.php/user/toggleFavorite/' . $film['id']) ?>" class="btn-netflix btn-secondary">
                ❤ Preferito
            </a>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="left-col">
        <?php if(session()->getFlashdata('msg')): ?>
            <div style="background: #2ecc71; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <span class="section-label">Sinossi</span>
        <p style="font-size: 18px; line-height: 1.6; color: #ccc; margin-bottom: 40px;">
            <?= esc($film['descrizione']) ?>
        </p>

        <span class="section-label">Il Mio Diario</span>
        <div class="diary-section">
            <form action="<?= base_url('index.php/films/saveReview') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                
                <div class="star-rating">
                    <?php for($i=5; $i>=1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="voto" value="<?= $i ?>" required>
                        <label for="star<?= $i ?>">★</label>
                    <?php endfor; ?>
                </div>

                <textarea name="commento" rows="3" placeholder="Cosa ne pensi di questa pellicola?" required></textarea>

                <label style="display: flex; align-items: center; gap: 10px; margin: 15px 0; cursor: pointer; color: #888; font-size: 14px;">
                    <input type="checkbox" name="rewatch" value="1" style="accent-color: #e50914;">
                    Lo riguarderesti volentieri? ↻
                </label>

                <button type="submit" class="btn-netflix" style="width: 100%; justify-content: center;">SALVA NEL DIARIO</button>
            </form>
        </div>

        <span class="section-label">Recensioni della Community</span>
        <div class="community-reviews">
            <?php if(empty($recensioni)): ?>
                <p style="color: #444; font-style: italic;">Nessuno ha ancora recensito questo film. Sii il primo!</p>
            <?php else: ?>
                <?php foreach($recensioni as $r): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="review-user"><?= esc($r['username']) ?></span>
                            <span class="review-stars">
                                <?= str_repeat('★', $r['voto']) ?><?= str_repeat('☆', 5 - $r['voto']) ?>
                            </span>
                        </div>
                        <p style="color: #bbb; margin: 0; font-style: italic;">"<?= esc($r['commento']) ?>"</p>
                        <div style="margin-top: 10px; font-size: 10px; color: #444; text-transform: uppercase;">
                            <?= date('d M Y', strtotime($r['data_recensione'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

   <div class="right-col">
        <div class="info-card">
            <div style="margin-bottom: 20px;">
                <label style="color: #555; font-size: 11px; text-transform: uppercase; display: block;">Genere</label>
                <span style="font-size: 16px; color: #e50914; font-weight: bold;"><?= esc($film['genere']) ?></span>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="color: #555; font-size: 11px; text-transform: uppercase; display: block;">Cast Protagonisti</label>
                <span style="font-size: 15px; line-height: 1.4; display: block;">
                    <?= !empty($film['cast_attori']) ? esc($film['cast_attori']) : 'Cast in fase di aggiornamento' ?>
                </span>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="color: #555; font-size: 11px; text-transform: uppercase; display: block;">Regia</label>
                <span style="font-size: 15px;"><?= esc($film['regista'] ?? 'Regista non specificato') ?></span>
            </div>

            <hr style="border: 0; border-top: 1px solid #222; margin: 20px 0;">
            
            <a href="<?= base_url('index.php/films') ?>" style="color: #e50914; text-decoration: none; font-size: 13px; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                <span>←</span> TORNA AL CATALOGO
            </a>
        </div>
    </div>

</body>
</html>