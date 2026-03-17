<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineMaster - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Bebas+Neue&display=swap" rel="stylesheet">
    
    <style>
        /* Reset totale */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body, html { 
            height: 100%; 
            width: 100%;
            background-color: #000; 
            font-family: 'Segoe UI', sans-serif; 
            overflow: hidden; 
        }

        /* SFONDO CINEMA - Percorso corretto */
        .bg-cinema {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                              url('http://localhost/codeigniter/public/cinema-bg.png');
            height: 100vh;
            width: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* TITOLO GIGANTE EFFETTO LENTE */
        h1 {
            font-family: 'Archivo Black', sans-serif;
            font-size: 7rem; /* Dimensione enorme */
            color: #e50914; /* Rosso Cinema */
            text-transform: uppercase;
            letter-spacing: -2px;
            margin-bottom: -10px; /* Lo avvicina al box */
            text-shadow: 0 0 30px rgba(229, 9, 20, 0.7);
            transform: scale(1.1); /* Effetto ingrandimento */
            z-index: 10;
        }

        /* BOX ARROTONDATO CON GLOW */
        .overlay {
            background: rgba(0, 0, 0, 0.9);
            padding: 40px;
            border-radius: 40px; /* Arrotondato */
            border: 2px solid #e50914;
            text-align: center;
            color: white;
            box-shadow: 0 0 50px rgba(229, 9, 20, 0.4);
            max-width: 420px;
            width: 90%;
            z-index: 5;
        }

        p {
            margin-bottom: 30px;
            color: #ccc;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        /* BOTTONI ARROTONDATI */
        .btn {
            display: block;
            padding: 15px;
            margin: 12px 0;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50px; /* Arrotondati */
            text-transform: uppercase;
            transition: 0.3s ease;
            font-family: sans-serif;
        }

        .btn-red {
            background-color: #e50914;
            color: white;
            border: none;
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.4);
        }

        .guest-link {
            display: inline-block;
            margin-top: 15px;
            color: #777;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .guest-link:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="bg-cinema">
        <h1>CINEMASTER</h1>

        <div class="overlay">
            <p>Entra nel cuore del cinema</p>
            
            <a href="/codeigniter/public/index.php/register" class="btn btn-red">Registrati Ora</a>
            <a href="/codeigniter/public/index.php/login" class="btn btn-outline">Accedi</a>
            
            <div>
                <a href="/codeigniter/public/index.php/films" class="guest-link">Continua come ospite</a>
            </div>
        </div>
    </div>

</body>
</html>