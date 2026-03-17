<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Radar del Gusto - CineMaster</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --netflix-red: #e50914;
            --dark-bg: #080808;
            --card-bg: #121212;
            --text-gray: #a3a3a3;
        }

        body { 
            background-color: var(--dark-bg); 
            color: white; 
            font-family: 'Helvetica Neue', sans-serif; 
            margin: 0; 
            padding-bottom: 50px;
        }

        .container { max-width: 1100px; margin: 40px auto; padding: 0 25px; }

        h1 { 
            font-size: 3rem; 
            font-weight: 900; 
            text-transform: uppercase; 
            margin-bottom: 5px; 
            background: linear-gradient(to right, #fff, #e50914);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle { color: var(--text-gray); margin-bottom: 40px; letter-spacing: 1px; }

        .analysis-card { 
            background: linear-gradient(145deg, #1a1a1a, #0a0a0a);
            padding: 40px; 
            border-radius: 20px; 
            border: 1px solid #222;
            margin-bottom: 30px; 
            position: relative;
        }
        .analysis-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: var(--netflix-red); box-shadow: 0 0 15px var(--netflix-red);
            border-radius: 20px 0 0 20px;
        }
        .analysis-text { font-size: 20px; line-height: 1.8; font-weight: 300; }
        .analysis-text b { color: #fff; font-weight: 700; border-bottom: 2px solid var(--netflix-red); }

        /* NUOVA GRID PER INSIGHT RAPIDI */
        .insight-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            margin-bottom: 30px; 
        }

        .grid { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 30px; }
        .card { background: var(--card-bg); padding: 25px; border-radius: 15px; border: 1px solid #1f1f1f; display: flex; flex-direction: column; justify-content: center; }
        .card-full { grid-column: span 2; margin-top: 10px; }
        
        .card h3 { 
            font-size: 12px; color: var(--text-gray); text-transform: uppercase; 
            letter-spacing: 1px; margin-bottom: 15px; border-left: 3px solid var(--netflix-red); padding-left: 10px;
        }

        .big-stat { font-size: 32px; font-weight: bold; margin: 5px 0; color: #fff; }
        .stat-desc { font-size: 13px; color: var(--text-gray); margin: 0; }

        .top-film-card { display: flex; flex-direction: row !important; align-items: center; gap: 15px; }
        .top-film-img { width: 60px; height: 90px; object-fit: cover; border-radius: 5px; border: 1px solid #333; }

        .btn-back { display: inline-block; margin-top: 50px; color: var(--text-gray); text-decoration: none; font-size: 14px; transition: 0.3s; }
        .btn-back:hover { color: var(--netflix-red); transform: translateX(-5px); }
    </style>
</head>
<body>

<div class="container">
    <h1>Radar del Gusto</h1>
    <p class="subtitle">L'identità cinematografica di <b><?= esc($username) ?></b></p>

    <div class="analysis-card">
        <p class="analysis-text">
            <?= $profilo_critico ?>
        </p>
    </div>

    <div class="insight-grid">
        <div class="card">
            <h3>Termometro Critica</h3>
            <p class="big-stat"><?= $mediaTotale ?>/5</p>
            <p class="stat-desc">Sei un <b><?= $tipoCritico ?></b></p>
        </div>

        <div class="card">
            <h3>Indice Nostalgia</h3>
            <p class="big-stat"><?= $percentualeRewatch ?>%</p>
            <p class="stat-desc">dei film li rivedresti volentieri</p>
        </div>

        <div class="card top-film-card">
            <?php if($topFilm): ?>
                <img src="<?= base_url($topFilm->copertina) ?>" class="top-film-img">
                <div>
                    <h3>Miglior Visione</h3>
                    <p style="margin: 0; font-weight: bold; font-size: 15px;"><?= esc($topFilm->titolo) ?></p>
                    <p style="margin: 0; color: gold;">★ <?= $topFilm->voto ?>/5</p>
                </div>
            <?php else: ?>
                <p class="stat-desc">Nessun film recensito</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Apprezzamento per Genere</h3>
            <canvas id="ratingChart"></canvas>
        </div>

        <div class="card">
            <h3>Focus Generi</h3>
            <canvas id="genreChart"></canvas>
        </div>

        <div class="card card-full">
            <h3>Evoluzione Temporale delle Visioni</h3>
            <canvas id="eraChart" height="100"></canvas>
        </div>
    </div>

    <a href="<?= base_url('index.php/user/profile') ?>" class="btn-back">← BACK TO PROFILE</a>
</div>

<script>
    const syncPalette = ['#e50914', '#2ecc71', '#3498db', '#f1c40f', '#9b59b6', '#e67e22', '#1abc9c'];
    Chart.defaults.color = '#888';
    Chart.defaults.font.family = "'Helvetica Neue', sans-serif";

    // 1. DOUGHNUT CHART
    new Chart(document.getElementById('genreChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($counts) ?>,
                backgroundColor: syncPalette,
                hoverOffset: 20,
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20, color: '#ccc' } } } }
    });

    // 2. BAR CHART
    new Chart(document.getElementById('ratingChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($averages) ?>,
                backgroundColor: syncPalette,
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, max: 5, grid: { color: '#1a1a1a' } },
                x: { grid: { display: false }, ticks: { color: '#ccc' } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // 3. LINE CHART
    const ctxEra = document.getElementById('eraChart').getContext('2d');
    const eraGradient = ctxEra.createLinearGradient(0, 0, 800, 0);
    eraGradient.addColorStop(0, syncPalette[0]);
    eraGradient.addColorStop(0.5, syncPalette[2]);
    eraGradient.addColorStop(1, syncPalette[1]);

    new Chart(ctxEra, {
        type: 'line',
        data: {
            labels: <?= json_encode($epoca_labels) ?>,
            datasets: [{
                data: <?= json_encode($epoca_counts) ?>,
                borderColor: eraGradient,
                backgroundColor: 'rgba(255, 255, 255, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 8,
                pointBackgroundColor: syncPalette,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            scales: {
                y: { grid: { color: '#1a1a1a' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: '#ccc' } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>

</body>
</html>