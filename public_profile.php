<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { background: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        /* Header Profilo */
        .profile-card { background: #111; border-radius: 15px; padding: 30px; border: 1px solid #222; margin-bottom: 30px; }
        .user-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        
        /* Avatar perfettamente tondo */
        .avatar-big { 
            width: 85px; height: 85px; background: #e50914; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 36px; font-weight: bold; flex-shrink: 0;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.4); border: 2px solid #fff;
        }

        /* Testo biografia radar */
        .radar-tag { color: #ccc; font-weight: normal; font-size: 15px; margin-top: 12px; line-height: 1.6; max-width: 650px; }
        .radar-tag b { color: #fff; border-bottom: 1px solid #e50914; }

        /* Social Stats & Stack Cerchietti */
        .social-row { display: flex; gap: 35px; margin: 25px 0 10px 0; border-top: 1px solid #222; padding-top: 20px; align-items: center; }
        .social-stat { text-align: left; }
        .social-stat b { display: block; font-size: 20px; color: #fff; }
        .social-stat span { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 1px; }

        .following-stack { display: flex; padding-left: 10px; }
        .mini-avatar {
            width: 38px; height: 38px; border-radius: 50%; background: #222;
            border: 2px solid #111; display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: bold; color: #fff; margin-left: -12px;
            transition: 0.3s; text-decoration: none;
        }
        .mini-avatar:hover { transform: translateY(-5px); border-color: #e50914; z-index: 5; }

        /* Box Consiglia Film */
        .advice-box { background: #1a1a1a; padding: 20px; border-radius: 12px; margin-top: 25px; border: 1px solid #333; }
        .advice-box h4 { margin-top: 0; font-size: 12px; color: #e50914; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
        .advice-box select, .advice-box textarea { 
            background: #000; color: #fff; border: 1px solid #444; padding: 12px; 
            width: 100%; border-radius: 6px; box-sizing: border-box; font-family: inherit; margin-bottom: 10px;
        }
        .btn-send-advice { 
            background: #e50914; color: white; border: none; padding: 12px; border-radius: 6px; 
            width: 100%; font-weight: bold; cursor: pointer; transition: 0.3s; text-transform: uppercase;
        }
        .btn-send-advice:hover { background: #ff0f1a; transform: translateY(-2px); }

        /* Liste Film e Diario */
        .fav-section { margin-bottom: 40px; }
        .fav-grid { display: flex; gap: 15px; overflow-x: auto; padding-bottom: 15px; }
        .fav-item { flex: 0 0 130px; text-align: center; }
        .fav-item img { width: 100%; height: 185px; object-fit: cover; border-radius: 8px; border: 2px solid #333; transition: 0.3s; }
        .fav-item img:hover { border-color: #e50914; transform: scale(1.02); }
        .fav-item p { font-size: 11px; margin-top: 8px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .btn-follow { background: #e50914; color: white; border: none; padding: 10px 25px; border-radius: 4px; font-weight: bold; text-decoration: none; cursor: pointer; }
        .btn-unfollow { background: transparent; color: #888; border: 1px solid #444; padding: 10px 25px; border-radius: 4px; font-weight: bold; text-decoration: none; }
        
        .alert-msg { background: #e50914; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">

    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert-msg"><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>

    <div class="profile-card">
        <div class="user-header">
            <div style="display: flex; align-items: flex-start; gap: 25px;">
                <div class="avatar-big"><?= strtoupper(substr($target['username'], 0, 1)) ?></div>
                <div>
                    <h1 style="margin:0; font-size: 34px; letter-spacing: -1px;"><?= esc($target['username']) ?></h1>
                    <div class="radar-tag">
                        <?= $descrizione_radar ?>
                    </div>
                </div>
            </div>
            
            <a href="<?= base_url('index.php/user/toggleFollow/'.$target['username']) ?>" 
               class="<?= $isFollowing ? 'btn-unfollow' : 'btn-follow' ?>">
                <?= $isFollowing ? 'Smetti di seguire' : 'Segui' ?>
            </a>
        </div>

        <div class="social-row">
            <div class="social-stat">
                <b><?= $seguiti_count ?></b>
                <span>Seguiti</span>
            </div>
            <div class="social-stat">
                <b><?= $followers_count ?></b>
                <span>Follower</span>
            </div>
            
            <?php if(!empty($seguiti_lista)): ?>
                <div style="margin-left: auto; text-align: right;">
                    <span style="font-size: 10px; color: #444; text-transform: uppercase; display: block; margin-bottom: 8px;">Nella cerchia di <?= esc($target['username']) ?></span>
                    <div class="following-stack" style="justify-content: flex-end;">
                        <?php foreach($seguiti_lista as $s): ?>
                            <a href="<?= base_url('index.php/user/viewProfile/'.$s['followed_username']) ?>" 
                               class="mini-avatar" title="<?= esc($s['followed_username']) ?>">
                                <?= strtoupper(substr($s['followed_username'], 0, 1)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="advice-box">
            <h4> Consiglia un film a <?= esc($target['username']) ?></h4>
            <form action="<?= base_url('index.php/user/consigliaFilm/'.$target['username']) ?>" method="post">
                <?= csrf_field() ?> <select name="film_id" required>
                    <option value="">Scegli un film dal catalogo...</option>
                    <?php 
                        $db = \Config\Database::connect();
                        $tutti_film = $db->table('films')->orderBy('titolo', 'ASC')->get()->getResultArray();
                        foreach($tutti_film as $tf): 
                    ?>
                        <option value="<?= $tf['id'] ?>"><?= esc($tf['titolo']) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="messaggio" placeholder="Scrivi un motivo per cui dovrebbe guardarlo..." style="resize: none;"></textarea>
                <button type="submit" class="btn-send-advice">Invia Suggerimento </button>
            </form>
        </div>
    </div>

    <div class="fav-section">
        <h3 style="border-left: 4px solid #e50914; padding-left: 12px; margin-bottom: 20px; font-size: 18px; text-transform: uppercase; letter-spacing: 1px;">Film Preferiti </h3>
        <?php if(empty($favorites)): ?>
            <p style="color: #444; font-style: italic;">L'utente non ha ancora aggiunto film ai preferiti.</p>
        <?php else: ?>
            <div class="fav-grid">
                <?php foreach($favorites as $f): ?>
                    <div class="fav-item">
                        <img src="<?= base_url($f['copertina']) ?>" alt="<?= esc($f['titolo']) ?>">
                        <p><?= esc($f['titolo']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <h3 style="border-left: 4px solid #444; padding-left: 12px; margin-bottom: 20px; color: #888; font-size: 18px; text-transform: uppercase; letter-spacing: 1px;">Ultime Visioni </h3>
    <?php if(empty($recensioni)): ?>
        <p style="color: #444; font-style: italic;">Nessuna recensione ancora presente nel diario.</p>
    <?php else: ?>
        <?php foreach($recensioni as $r): ?>
            <div style="background: #111; padding: 20px; border-radius: 12px; margin-bottom: 15px; display: flex; gap: 20px; border: 1px solid #222;">
                <img src="<?= base_url($r['copertina']) ?>" style="width: 55px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #333;">
                <div>
                    <h4 style="margin: 0; font-size: 18px; color: #fff;"><?= esc($r['titolo']) ?> <span style="color: #444; font-weight: normal;">(<?= $r['anno'] ?>)</span></h4>
                    <div style="color: #f1c40f; margin: 8px 0; font-size: 14px;">
                        <?= str_repeat('★', $r['voto']) ?><?= str_repeat('☆', 5 - $r['voto']) ?>
                    </div>
                    <p style="margin: 0; font-size: 14px; color: #aaa; line-height: 1.5; font-style: italic;">"<?= esc($r['commento']) ?>"</p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div style="margin-top: 60px; text-align: center; border-top: 1px solid #222; padding-top: 30px;">
        <a href="<?= base_url('index.php/user/community') ?>" style="color: #555; text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">← Torna alla Community</a>
    </div>
</div>

</body>
</html>