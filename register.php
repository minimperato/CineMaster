<div class="login-wrapper">
    <div class="login-box">
        <?php if (isset($validation)): ?>
            <div class="error-banner">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="http://localhost/codeigniter/public/index.php/register" method="post">
            <?= csrf_field() ?>
            
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Scegli un username" value="<?= set_value('username') ?>" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="La tua email" value="<?= set_value('email') ?>" required>
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Crea una password" required>
            </div>
            
            <button type="submit" class="login-button">REGISTRATI</button>
        </form>

        <p class="login-footer">
            Hai già un account? <a href="http://localhost/codeigniter/public/index.php/login">Accedi qui</a>
        </p>
    </div>

    <div class="project-footer">
        Progetto Informatica &copy; 2026
    </div>
</div>

<style>
    /* Nasconde il titolo dell'header */
    h1 { display: none !important; } 

    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: #141414;
        font-family: 'Segoe UI', Tahoma, sans-serif;
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

    .login-box {
        background-color: rgba(0, 0, 0, 0.85);
        padding: 50px;
        border-radius: 4px;
        width: 100%;
        max-width: 400px;
        box-sizing: border-box;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    }

    .input-group { margin-bottom: 20px; }
    .input-group label { display: block; color: #8c8c8c; margin-bottom: 7px; font-size: 14px; }
    
    .input-group input {
        width: 100%;
        padding: 12px;
        background: #333;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        box-sizing: border-box;
    }

    .login-button {
        width: 100%;
        padding: 14px;
        background: #e50914;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 15px;
    }

    .login-button:hover { background: #ff0a16; }

    .login-footer {
        color: #737373;
        text-align: center;
        margin-top: 25px;
        font-size: 14px;
    }

    .login-footer a { color: white; text-decoration: none; font-weight: bold; }

    .project-footer {
        position: absolute;
        bottom: 30px;
        color: #555;
        font-size: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .error-banner {
        background: #e87c03;
        color: white;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 13px;
    }
    
    /* Rende l'elenco degli errori più pulito */
    .error-banner ul { margin: 0; padding-left: 20px; text-align: left; }
</style>