<div class="login-wrapper">
    <div class="login-box">
        
        <?php if (session()->getFlashdata('success_reg')): ?>
            <div class="success-banner">
                <?= session()->getFlashdata('success_reg') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="error-banner">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('index.php/login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" class="login-button">ACCEDI</button>
        </form>

        <p class="login-footer">
            Nuovo utente? <a href="<?= base_url('index.php/register') ?>">Registrati</a>
        </p>
    </div>

    <div class="project-footer">
        Progetto Sistemi Informativi &copy; 2026
    </div>
</div>

<style>
    /* Nasconde forzatamente il titolo che arriva dall'header */
    h1 { display: none !important; } 

    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: #141414; /* Sfondo scuro stile Netflix */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: white;
        overflow: hidden;
    }

    .login-wrapper {
        height: 100vh;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    /* Box Centrale */
    .login-box {
        background-color: rgba(0, 0, 0, 0.85);
        padding: 60px;
        border-radius: 8px;
        width: 100%;
        max-width: 450px;
        box-sizing: border-box;
        box-shadow: 0 15px 35px rgba(0,0,0,0.7);
    }

    /* Gruppi di Input */
    .input-group { margin-bottom: 25px; }
    .input-group label { display: block; color: #8c8c8c; margin-bottom: 10px; font-size: 14px; }
    
    .input-group input {
        width: 100%;
        padding: 14px;
        background: #333;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        box-sizing: border-box;
        transition: background 0.3s;
    }
    
    .input-group input:focus {
        background: #444;
        outline: none;
    }

    /* Bottone Rosso */
    .login-button {
        width: 100%;
        padding: 16px;
        background: #e50914;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
        transition: background 0.2s;
    }

    .login-button:hover { background: #ff0a16; }

    /* Footer del Box */
    .login-footer {
        color: #737373;
        text-align: center;
        margin-top: 30px;
        font-size: 15px;
    }

    .login-footer a { color: white; text-decoration: none; font-weight: bold; }
    .login-footer a:hover { text-decoration: underline; }

    /* Footer Progetto */
    .project-footer {
        position: absolute;
        bottom: 30px;
        color: #444;
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* Banners Notifiche */
    .error-banner {
        background: #e87c03; /* Arancione Netflix per errori */
        color: white;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 25px;
        text-align: center;
        font-size: 14px;
    }

    .success-banner {
        background: #2ecc71; /* Verde per successo */
        color: white;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 25px;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>