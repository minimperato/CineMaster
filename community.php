<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>CineMaster - Community</title>
    <style>
        body { background-color: #080808; color: white; font-family: 'Helvetica Neue', Arial, sans-serif; margin: 0; }
        .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        
        h1 { font-size: 2.5rem; font-weight: 900; margin-bottom: 30px; border-left: 5px solid #e50914; padding-left: 15px; }

        /* Barra di Ricerca */
        .search-container { margin-bottom: 40px; }
        #searchInput { 
            background: #111; color: white; border: 1px solid #333; padding: 15px; 
            width: 100%; border-radius: 8px; font-size: 16px; box-sizing: border-box;
            outline: none; transition: 0.3s;
        }
        #searchInput:focus { border-color: #e50914; box-shadow: 0 0 10px rgba(229, 9, 20, 0.2); }

        /* Grid Utenti */
        .community-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        
        .user-card { 
            background: #141414; border-radius: 12px; padding: 25px; 
            border: 1px solid #222; transition: transform 0.3s, border-color 0.3s;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .user-card:hover { transform: translateY(-5px); border-color: #444; }

        .user-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .avatar { 
            width: 60px; height: 60px; background: linear-gradient(45deg, #e50914, #b20710); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            font-size: 24px; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.5); flex-shrink: 0;
        }

        .username-link { color: white; text-decoration: none; font-size: 20px; font-weight: bold; }
        .username-link:hover { color: #e50914; }

        .radar-badge { 
            font-size: 11px; color: #e50914; font-weight: bold; text-transform: uppercase; 
            letter-spacing: 1px; background: rgba(229, 9, 20, 0.1); padding: 4px 8px; border-radius: 4px;
        }

        .fav-row { display: flex; gap: 8px; margin-top: 15px; background: #0c0c0c; padding: 10px; border-radius: 8px; }
        .fav-mini-poster { width: 50px; height: 75px; object-fit: cover; border-radius: 4px; border: 1px solid #222; }

        .btn-follow { background: #e50914; color: white; border: none; padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; margin-top: 15px; }
        .btn-unfollow { background: #333; color: #ccc; border: none; padding: 10px; border-radius: 5px; font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; margin-top: 15px; }
        
        /* Footer ritorno */
        .btn-back-catalog {
            display: inline-block; color: #a3a3a3; text-decoration: none; font-weight: bold;
            font-size: 14px; padding: 12px 25px; border: 1px solid #444; border-radius: 4px;
            transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-back-catalog:hover { background-color: white; color: black; border-color: white; transform: scale(1.05); }
    </style>
</head>
<body>

<div class="container">
    <h1>Community</h1>

    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchUser()" placeholder="Cerca un cinefilo per nome...">
    </div>

    <div class="community-grid">
        <?php foreach($utenti as $u): ?>
            <div class="user-card">
                <div>
                    <div class="user-info">
                        <div class="avatar"><?= strtoupper(substr($u['username'], 0, 1)) ?></div>
                        <div>
                            <a href="<?= base_url('index.php/user/viewProfile/'.$u['username']) ?>" class="username-link">
                                <?= esc($u['username']) ?>
                            </a><br>
                            <span class="radar-badge"><?= esc($u['descrizione_radar']) ?></span>
                        </div>
                    </div>
                    
                    <p style="color: #999; font-size: 13px; line-height: 1.4; height: 40px; overflow: hidden;">
                        "Appassionato di cinema che ama condividere le proprie emozioni sul grande schermo..."
                    </p>

                    <div class="fav-row">
                        <?php if(!empty($u['film_preferiti'])): ?>
                            <?php foreach($u['film_preferiti'] as $f): ?>
                                <img src="<?= base_url($f['copertina']) ?>" class="fav-mini-poster" title="<?= esc($f['titolo']) ?>">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span style="color: #444; font-size: 12px;">Nessun film preferito</span>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?= base_url('index.php/user/toggleFollow/'.$u['username']) ?>" 
                   class="<?= in_array($u['username'], $miei_seguiti) ? 'btn-unfollow' : 'btn-follow' ?>">
                    <?= in_array($u['username'], $miei_seguiti) ? 'Smetti di seguire' : 'Segui' ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 60px; text-align: center; border-top: 1px solid #222; padding-top: 40px;">
        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Hai finito di esplorare la community?</p>
        <a href="<?= base_url('index.php/films') ?>" class="btn-back-catalog">
            ← TORNA AL CATALOGO FILM
        </a>
    </div>
</div>

<script>
function searchUser() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let cards = document.getElementsByClassName('user-card');
    
    for (let i = 0; i < cards.length; i++) {
        let username = cards[i].querySelector('.username-link').innerText.toLowerCase();
        cards[i].style.display = username.includes(input) ? "flex" : "none";
    }
}
</script>

</body>
</html>