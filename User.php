<?php

namespace App\Controllers;

class User extends BaseController
{
    // --- 1. PROFILO PERSONALE ---
    public function profile()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $username = session()->get('username');

        $seguiti_count = $db->table('seguiti')->where('follower_username', $username)->countAllResults();
        $followers_count = $db->table('seguiti')->where('followed_username', $username)->countAllResults();
        $seguiti_lista = $db->table('seguiti')->where('follower_username', $username)->limit(10)->get()->getResultArray();

        $queryGeneri = $db->table('recensioni')->select('films.genere, COUNT(*) as totale')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->groupBy('films.genere')->orderBy('totale', 'DESC')->get()->getResultArray();
        $recAnni = $db->table('recensioni')->select('films.anno')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->get()->getResultArray();

        $epoche = ['Vintage' => 0, 'Millennium' => 0, 'Moderno' => 0];
        foreach ($recAnni as $r) {
            if ($r['anno'] < 2000) $epoche['Vintage']++;
            elseif ($r['anno'] <= 2015) $epoche['Millennium']++;
            else $epoche['Moderno']++;
        }

        $profilo_critico = "Il tuo diario è ancora vuoto.";
        if (!empty($queryGeneri)) {
            $top_genere = $queryGeneri[0]['genere'];
            arsort($epoche);
            $profilo_critico = "Sei un cultore del genere <b>$top_genere</b>. Epoca d'oro: <b>" . key($epoche) . "</b>.";
        }

        $total_reviews = $db->table('recensioni')->where('username', $username)->countAllResults();

        return view('user/profile', [
            'username'        => $username,
            'seguiti_count'   => $seguiti_count,
            'followers_count' => $followers_count,
            'seguiti_lista'   => $seguiti_lista,
            'total_watchlist' => $db->table('watchlist')->where('username', $username)->countAllResults(),
            'total_reviews'   => $total_reviews,
            'progresso'       => min(($total_reviews / 16) * 100, 100),
            'next_level'      => ($total_reviews < 6) ? 6 : 16,
            'level_name'      => ($total_reviews < 6) ? "Critico Emergente" : "Leggenda del Cinema",
            'profilo_critico' => $profilo_critico,
            'favorites'       => $db->table('preferiti')->select('films.*')->join('films', 'films.id = preferiti.film_id')->where('preferiti.username', $username)->get()->getResultArray(),
            'watchlist_films' => $db->table('watchlist')->select('films.*')->join('films', 'films.id = watchlist.film_id')->where('watchlist.username', $username)->get()->getResultArray(),
            'title'           => 'Il Mio Profilo'
        ]);
        
    }

    // --- 2. DIARIO ---
    public function diario()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $username = session()->get('username');

        $recensioni = $db->table('recensioni')->select('recensioni.*, films.titolo, films.copertina, films.anno, films.genere')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->orderBy('data_recensione', 'DESC')->get()->getResultArray();
        $media_voto = $db->table('recensioni')->where('username', $username)->selectAvg('voto')->get()->getRow()->voto ?? 0;
        $genereQuery = $db->table('recensioni')->select('films.genere')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->groupBy('films.genere')->orderBy('COUNT(*)', 'DESC')->limit(1)->get()->getRow();

        return view('user/diario', [
            'recensioni'  => $recensioni,
            'username'    => $username,
            'total_count' => count($recensioni),
            'media_voto'  => round($media_voto, 1),
            'genere_pref' => $genereQuery ? $genereQuery->genere : "Nessuno",
            'title'       => 'Il Mio Diario'
        ]);
        // Dentro la funzione profile() del Controller User.php

$username = session()->get('username');
$db = \Config\Database::connect();

// Recuperiamo i consigli che IO HO MANDATO agli altri
$consigli_inviati = $db->table('consigli')
                       ->select('consigli.id, consigli.a_username, films.titolo, films.copertina')
                       ->join('films', 'films.id = consigli.film_id')
                       ->where('da_username', $username) // Quelli mandati DA ME
                       ->get()
                       ->getResultArray();

// Aggiungiamo 'consigli_inviati' all'array di ritorno della view
return view('user/profile', [
    // ... tutti gli altri dati che avevi già (username, seguiti, etc.) ...
    'consigli_inviati' => $consigli_inviati, 
    'title'            => 'Il Mio Profilo'
]);
    }

    public function stats()
{
    if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
    
    $db = \Config\Database::connect();
    $username = session()->get('username');

    // 1. Recupero Distribuzione Generi, Conteggi e Medie per i grafici
    $queryGeneri = $db->table('recensioni')
        ->select('films.genere, COUNT(*) as totale, AVG(recensioni.voto) as media')
        ->join('films', 'films.id = recensioni.film_id')
        ->where('recensioni.username', $username)
        ->groupBy('films.genere')
        ->orderBy('totale', 'DESC')
        ->get()->getResultArray();

    // 2. Calcolo Statistiche Generali (Media e Rewatch)
    $resMedia = $db->table('recensioni')->where('username', $username)->selectAvg('voto')->get()->getRow();
    $mediaT = $resMedia ? (float)$resMedia->voto : 0;
    
    $totRecensioni = $db->table('recensioni')->where('username', $username)->countAllResults();
    $countRewatch = $db->table('recensioni')->where(['username' => $username, 'rewatch' => 1])->countAllResults();
    $percentualeRewatch = ($totRecensioni > 0) ? round(($countRewatch / $totRecensioni) * 100) : 0;

    // 3. Calcolo Epoche (Nostalgia)
    $recAnni = $db->table('recensioni')->select('films.anno')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->get()->getResultArray();
    $epoche = ['Vintage' => 0, 'Millennium' => 0, 'Moderno' => 0];
    foreach ($recAnni as $r) {
        $anno = (int)$r['anno'];
        if ($anno < 2000) $epoche['Vintage']++;
        elseif ($anno <= 2015) $epoche['Millennium']++;
        else $epoche['Moderno']++;
    }

    // 4. Miglior Visione (Il voto più alto, tie-break con l'ultimo inserito)
    $topFilm = $db->table('recensioni')
        ->select('films.*, recensioni.voto')
        ->join('films', 'films.id = recensioni.film_id')
        ->where('recensioni.username', $username)
        ->orderBy('recensioni.voto', 'DESC') 
        ->orderBy('recensioni.id', 'DESC')  
        ->limit(1)
        ->get()
        ->getRow();

    // --- GENERATORE DI ANALISI DINAMICA DEL PROFILO ---
    $topGenere = $queryGeneri[0]['genere'] ?? 'vari';
    $descrizione = "";

    // A. Analisi basata sul genere dominante
    if ($topGenere == 'Azione' || $topGenere == 'Avventura') {
        $descrizione = "Il tuo profilo rivela un'anima incline all'<b>adrenalina</b>. Prediligi narrazioni dinamiche dove il ritmo è serrato.";
    } elseif ($topGenere == 'Dramma' || $topGenere == 'Thriller') {
        $descrizione = "Sei uno spettatore attento alle <b>sfumature psicologiche</b>. Cerchi nel cinema una profondità emotiva o una tensione costante.";
    } elseif ($topGenere == 'Commedia') {
        $descrizione = "Affronti il cinema con leggerezza, cercando storie che sappiano intrattenere e regalare un <b>punto di vista brillante</b> sulla realtà.";
    } else {
        $descrizione = "Il tuo gusto è <b>eclettico</b> e spazia tra diverse narrazioni, segno di una grande curiosità cinematografica.";
    }

    // B. Analisi sulla nostalgia
    if ($epoche['Vintage'] > $epoche['Moderno']) {
        $descrizione .= " Hai un legame profondo con i <b>grandi classici</b>: per te il vero cinema ha il sapore del passato.";
    } else {
        $descrizione .= " Sei proiettato verso il <b>cinema contemporaneo</b>, attratto dalle nuove tecniche narrative e visive.";
    }

    // C. Nota sul rigore critico
    if ($mediaT >= 4) {
        $descrizione .= " Le tue recensioni mostrano un cuore da <b>sognatore</b>, capace di farsi rapire dalla magia della settima arte.";
    } elseif ($mediaT < 3) {
        $descrizione .= " Il tuo approccio è quello di un <b>critico rigoroso</b>: non ti lasci incantare facilmente e pretendi l'eccellenza.";
    } else {
        $descrizione .= " Mantieni un <b>equilibrio analitico</b>, sapendo apprezzare i pregi senza ignorare i difetti delle opere.";
    }

    // 5. Preparazione finale dei dati per la View
    $data = [
        'username'           => $username,
        'labels'             => array_column($queryGeneri, 'genere') ?: ['Nessun dato'],
        'counts'             => array_map('intval', array_column($queryGeneri, 'totale')) ?: [0],
        'averages'           => array_map('floatval', array_column($queryGeneri, 'media')) ?: [0],
        'epoca_labels'       => array_keys($epoche),
        'epoca_counts'       => array_values($epoche),
        'mediaTotale'        => round($mediaT, 1),
        'percentualeRewatch' => (int)$percentualeRewatch,
        'tipoCritico'        => ($mediaT > 3.8) ? "Ottimista Sognatore" : "Critico Severo",
        'topFilm'            => $topFilm,
        'profilo_critico'    => $descrizione, // Passiamo la descrizione dinamica
        'title'              => 'Radar del Gusto'
    ];

    return view('user/stats', $data);
}
        //SFIDE//

    public function challenges()
{
    if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));

    $db = \Config\Database::connect();
    $username = session()->get('username');

    // 1. Recuperiamo le sfide sbloccate
    $sfideSalvate = $db->table('user_challenges')
                       ->where('username', $username)
                       ->get()
                       ->getResultArray();
    $nomiSbloccati = array_column($sfideSalvate, 'sfida_nome');

    // 2. Struttura completa (tutti i livelli con 'desc')
    $livelli = [
        'Livello 1: Inizia il viaggio (1 Film)' => [
            ['title' => 'Brivido Notturno', 'icon' => '👻', 'target' => 1, 'genere' => 'Horror', 'desc' => 'Recensisci 1 film Horror'],
            ['title' => 'Eroe Action', 'icon' => '🔥', 'target' => 1, 'genere' => 'Azione', 'desc' => 'Recensisci 1 film Azione'],
            ['title' => 'Primo Contatto', 'icon' => '👽', 'target' => 1, 'genere' => 'Fantascienza', 'desc' => 'Recensisci 1 film Sci-Fi'],
            ['title' => 'Sotto Tensione', 'icon' => '🕵️', 'target' => 1, 'genere' => 'Thriller', 'desc' => 'Recensisci 1 film Thriller'],
        ],
        'Livello 2: Esperto del Genere (3 Film)' => [
            ['title' => 'Maestro del Terrore', 'icon' => '💀', 'target' => 3, 'genere' => 'Horror', 'desc' => 'Recensisci 3 film Horror'],
            ['title' => 'Duro a Morire', 'icon' => '💣', 'target' => 3, 'genere' => 'Azione', 'desc' => 'Recensisci 3 film Azione'],
        ],
        'Livello 3: Leggenda del Cinema (5 Film)' => [
            ['title' => 'Cinefilo Assoluto', 'icon' => '🏆', 'target' => 5, 'genere' => 'Qualsiasi', 'desc' => 'Recensisci 5 film totali'],
        ]
    ];

    // 3. Elaboriamo i dati senza fermarci
    foreach ($livelli as $nomeLivello => &$sfide) {
        foreach ($sfide as &$s) {
            $s['completed'] = in_array($s['title'], $nomiSbloccati);
        }
    }

    return view('user/challenges', [
        'livelli' => $livelli, // Ora passa l'intero array livelli
        'title'   => 'Le Mie Sfide'
    ]);
}
    // --- 5. COMMUNITY ---
    public function community()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $me = session()->get('username');
        $utentiRaw = $db->table('users')->where('username !=', $me)->get()->getResultArray();
        $utenti = [];
        foreach ($utentiRaw as $u) {
            $un = $u['username'];
            $media = $db->table('recensioni')->where('username', $un)->selectAvg('voto')->get()->getRow()->voto ?? 0;
            $gen = $db->table('recensioni')->select('films.genere')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $un)->groupBy('films.genere')->orderBy('COUNT(*)', 'DESC')->limit(1)->get()->getRow();
            $u['descrizione_radar'] = ($media == 0) ? "Nuovo spettatore" : (($media > 3.8 ? "Ottimista Sognatore" : "Critico Severo") . " - " . ($gen ? $gen->genere : "cinema"));
            $u['film_preferiti'] = $db->table('preferiti')->select('films.copertina, films.titolo')->join('films', 'films.id = preferiti.film_id')->where('preferiti.username', $un)->limit(4)->get()->getResultArray();
            $utenti[] = $u;
        }
        return view('user/community', [
            'utenti' => $utenti,
            'miei_seguiti' => array_column($db->table('seguiti')->where('follower_username', $me)->get()->getResultArray(), 'followed_username'),
            'title' => 'Community'
        ]);
    }

    // --- 6. PROFILO PUBBLICO ---
    public function viewProfile($username)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $me = session()->get('username');
        if ($username == $me) return redirect()->to(base_url('index.php/user/profile'));

        $target = $db->table('users')->where('username', $username)->get()->getRowArray();
        if (!$target) return redirect()->to(base_url('index.php/user/community'));

        $media = $db->table('recensioni')->where('username', $username)->selectAvg('voto')->get()->getRow()->voto ?? 0;
        $totR = $db->table('recensioni')->where('username', $username)->countAllResults();
        $gen = $db->table('recensioni')->select('films.genere')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->groupBy('films.genere')->orderBy('COUNT(*)', 'DESC')->limit(1)->get()->getRow();
        
        $tipo = ($media > 3.8) ? "un <b>Ottimista Sognatore</b>" : "un <b>Critico Severo</b>";
        $bio_lunga = "Dall'analisi del Radar, emerge che $username è $tipo. Il suo cuore batte per il genere <b>" . ($gen ? $gen->genere : "Cinema") . "</b>. Ha condiviso <b>$totR</b> recensioni.";

        return view('user/public_profile', [
            'target'            => $target,
            'seguiti_count'     => $db->table('seguiti')->where('follower_username', $username)->countAllResults(),
            'followers_count'   => $db->table('seguiti')->where('followed_username', $username)->countAllResults(),
            'seguiti_lista'     => $db->table('seguiti')->where('follower_username', $username)->limit(6)->get()->getResultArray(),
            'favorites'         => $db->table('preferiti')->select('films.*')->join('films', 'films.id = preferiti.film_id')->where('preferiti.username', $username)->get()->getResultArray(),
            'recensioni'        => $db->table('recensioni')->select('recensioni.*, films.titolo, films.copertina, films.anno')->join('films', 'films.id = recensioni.film_id')->where('recensioni.username', $username)->orderBy('data_recensione', 'DESC')->get()->getResultArray(),
            'descrizione_radar' => $bio_lunga,
            'isFollowing'       => $db->table('seguiti')->where(['follower_username' => $me, 'followed_username' => $username])->countAllResults() > 0,
            'title'             => 'Profilo di ' . $username
        ]);
    }

    // --- 7. CONSIGLIATI (E LOGICA NOTIFICHE) ---
    public function consigliati()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $me = session()->get('username');

        // SEGNA COME LETTI I CONSIGLI RICEVUTI
        $db->table('consigli')->where('a_username', $me)->where('letto', false)->update(['letto' => true]);

        $consigli_amici = $db->table('consigli')->select('consigli.*, films.titolo, films.copertina')->join('films', 'films.id = consigli.film_id')->where('a_username', $me)->orderBy('data_invio', 'DESC')->get()->getResultArray();
        
        $visti = array_column($db->table('recensioni')->select('film_id')->where('username', $me)->get()->getResultArray(), 'film_id');
        $builderA = $db->table('recensioni')->select('films.*, ROUND(AVG(voto), 1) as media_community')->join('films', 'films.id = recensioni.film_id')->groupBy('films.id')->orderBy('media_community', 'DESC');
        if (!empty($visti)) $builderA->whereNotIn('films.id', $visti);
        
        return view('user/consigliati', [
            'consigli_amici' => $consigli_amici,
            'community_match' => $builderA->limit(3)->get()->getResultArray(),
            'scoperte' => $db->table('films')->orderBy('id', 'RANDOM')->limit(8)->get()->getResultArray(),
            'username' => $me,
            'title' => 'Consigliati per Te'
        ]);
    }

    // --- AZIONI ---
    public function toggleFollow($target) {
        $db = \Config\Database::connect(); $me = session()->get('username');
        $b = $db->table('seguiti');
        if ($b->where(['follower_username'=>$me, 'followed_username'=>$target])->get()->getRow()) $b->where(['follower_username'=>$me, 'followed_username'=>$target])->delete();
        else $b->insert(['follower_username'=>$me, 'followed_username'=>$target]);
        return redirect()->back();
    }

    public function toggleWatchlist($film_id)
{
    // Se NON è loggato, invece di dare errore, manda un messaggio
    if (!session()->get('isLoggedIn')) {
        return redirect()->to(base_url('index.php/login'))
                         ->with('error', ' Funzionalità esclusiva! Accedi per aggiungere film alla tua Watchlist.');
    }

    $db = \Config\Database::connect();
    $username = session()->get('username');
    $builder = $db->table('watchlist');

    $check = $builder->where(['username' => $username, 'film_id' => $film_id])->get()->getRow();

    if ($check) {
        $builder->where(['username' => $username, 'film_id' => $film_id])->delete();
        $msg = "Rimosso dalla lista dei desideri ";
    } else {
        $builder->insert(['username' => $username, 'film_id' => $film_id]);
        $msg = "Aggiunto alla tua Watchlist! ";
    }

    return redirect()->back()->with('msg', $msg);
}

public function toggleFavorite($film_id)
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to(base_url('index.php/login'))
                         ->with('error', ' Vuoi salvare i tuoi preferiti? Effettua il login!');
    }

    $db = \Config\Database::connect();
    $username = session()->get('username');
    $builder = $db->table('preferiti');

    $check = $builder->where(['username' => $username, 'film_id' => $film_id])->get()->getRow();

    if ($check) {
        $builder->where(['username' => $username, 'film_id' => $film_id])->delete();
        $msg = "Rimosso dai preferiti ";
    } else {
        $builder->insert(['username' => $username, 'film_id' => $film_id]);
        $msg = "Aggiunto ai tuoi preferiti! ";
    }

    return redirect()->back()->with('msg', $msg);
}

    public function consigliaFilm($target_username)
{
    if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
    $db = \Config\Database::connect();
    
    $film_id   = $this->request->getPost('film_id');
    $messaggio = $this->request->getPost('messaggio');

    // --- INIZIO TRANSAZIONE (Pag. 79 PDF) ---
    $db->transStart(); 

    $db->table('consigli')->insert([
        'da_username' => session()->get('username'),
        'a_username'  => $target_username,
        'film_id'     => $film_id,
        'messaggio'   => $messaggio,
        'data_invio'  => date('Y-m-d H:i:s'),
        'letto'       => false
    ]);

    // Se volessi aggiungere un'altra operazione (es: log attività), andrebbe qui
    // $db->table('logs')->insert([...]);

    $db->transComplete(); 
    // --- FINE TRANSAZIONE ---

    if ($db->transStatus() === FALSE) {
        // Se qualcosa è andato storto, CodeIgniter fa il ROLLBACK automatico
        return redirect()->back()->with('msg', "Errore critico durante l'invio.");
    } else {
        // Se tutto è ok, fa il COMMIT
        return redirect()->back()->with('msg', "Consiglio inviato con successo! ");
    }
}

    public function eliminaConsiglio($id) {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $db->table('consigli')->where(['id' => $id, 'a_username' => session()->get('username')])->delete();
        return redirect()->back()->with('msg', "Consiglio rimosso! ");
    }
    public function updateEmail()
{
    if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
    
    $db = \Config\Database::connect();
    $me = session()->get('username');
    $nuova_email = trim($this->request->getPost('email'));
    
    // Recuperiamo l'email attuale dalla sessione (o dal DB) per il confronto
    $email_attuale = session()->get('email');

    // 1. NUOVO CONTROLLO: Se l'email inserita è IDENTICA a quella che ha già
    if ($nuova_email === $email_attuale) {
        return redirect()->back()->with('msg', "Inserisci un'email diversa da quella attuale!");
    }

    // 2. Controllo validità formato
    if (!filter_var($nuova_email, FILTER_VALIDATE_EMAIL)) {
        return redirect()->back()->with('msg', "Errore: formato email non valido!");
    }

    // 3. CONTROLLO UNICITÀ: Se l'email appartiene a un ALTRO utente
    $esisteGia = $db->table('users')
                    ->where('email', $nuova_email)
                    ->where('username !=', $me)
                    ->get()
                    ->getRow();

    if ($esisteGia) {
        return redirect()->back()->with('msg', "Errore: questa email è già associata a un altro account!");
    }

    // 4. Eseguiamo l'aggiornamento
    $db->table('users')
       ->where('username', $me)
       ->update(['email' => $nuova_email]);

    // 5. Aggiorniamo la sessione per cambiare l'email "sopra" nell'header
    session()->set('email', $nuova_email);

    return redirect()->back()->with('msg', "Email aggiornata con successo!");
}
public function eliminaProfilo()
{
    if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));

    $db = \Config\Database::connect();
    $me = session()->get('username');

    // --- INIZIO TRANSAZIONE ---
    $db->transStart();

    // 
    $db->table('recensioni')->where('username', $me)->delete();
    $db->table('preferiti')->where('username', $me)->delete();
    $db->table('consigli')->where('da_username', $me)->orWhere('a_username', $me)->delete();
    $db->table('seguiti')->where('follower_username', $me)->orWhere('followed_username', $me)->delete();
    $db->table('watchlist')->where('username', $me)->delete();
    $db->table('user_challenges')->where('username', $me)->delete();

    // Infine, eliminiamo l'utente
    $db->table('users')->where('username', $me)->delete();

    $db->transComplete();
    // --- FINE TRANSAZIONE ---

    if ($db->transStatus() === FALSE) {
        return redirect()->back()->with('msg', "Errore durante l'eliminazione del profilo.");
    }

    // Puliamo la sessione e torniamo alla login
    session()->destroy();
    return redirect()->to(base_url('index.php/login'))->with('msg', "Profilo eliminato correttamente. Ci mancherai! ");
}
   public function deleteReview($id = null)
{
    if (!$id) return redirect()->back();

    $db = \Config\Database::connect();
    $me = session()->get('username');

    // 1. Recuperiamo il genere PRIMA di cancellare
    $recensione = $db->table('recensioni')
                     ->select('films.genere')
                     ->join('films', 'films.id = recensioni.film_id')
                     ->where('recensioni.id', (int)$id)
                     ->where('recensioni.username', $me)
                     ->get()
                     ->getRowArray();

    if ($recensione) {
        $genere = trim($recensione['genere']);

        // 2. Cancelliamo la recensione
        $db->table('recensioni')->where('id', (int)$id)->delete();

        // 3. Contiamo quante ne restano
        $countAttuale = $db->table('recensioni')
                           ->join('films', 'films.id = recensioni.film_id')
                           ->where('recensioni.username', $me)
                           ->where('films.genere', $genere)
                           ->countAllResults();

        // 4. CANCELLAZIONE SICURA DAL DBMS
        // Definiamo i nomi ESATTI come sono nel DB
        $sfideDelGenere = [];
        $g = strtolower($genere);

        if ($g == 'horror')      $sfideDelGenere = ['Brivido Notturno', 'Maestro del Terrore'];
        elseif ($g == 'azione')  $sfideDelGenere = ['Eroe Action', 'Duro a Morire'];
        elseif ($g == 'fantascienza' || $g == 'sci-fi') $sfideDelGenere = ['Primo Contatto'];
        elseif ($g == 'thriller') $sfideDelGenere = ['Sotto Tensione'];

        // Se l'utente ha meno di 3 recensioni, togliamo la sfida di livello 2 (se esiste nella lista)
        if ($countAttuale < 3 && isset($sfideDelGenere[1])) {
            $db->table('user_challenges')
               ->where('username', $me)
               ->where('sfida_nome', $sfideDelGenere[1])
               ->delete();
        }

        // Se l'utente ha 0 recensioni, togliamo anche la sfida di livello 1
        if ($countAttuale < 1 && isset($sfideDelGenere[0])) {
            $db->table('user_challenges')
               ->where('username', $me)
               ->where('sfida_nome', $sfideDelGenere[0])
               ->delete();
        }

        session()->setFlashdata('successo_eliminazione', 'Recensione rimossa. Il tuo progresso sfide è stato aggiornato.');
        return redirect()->back();
    }

    return redirect()->back()->with('errore', 'Recensione non trovata.');
}

}