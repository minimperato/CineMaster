<?php

namespace App\Controllers;

use App\Models\FilmModel;

class Films extends BaseController
{
    /**
     * PAGINA CATALOGO
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $model = new FilmModel();
        
        $username = session()->get('username') ?? null;
        $isLoggedIn = session()->get('isLoggedIn') ?? false;

        $filmRandom = $db->table('films')->orderBy('id', 'RANDOM')->limit(1)->get()->getRow();

        $titolo = $this->request->getGet('titolo') ?? '';
        $genere = $this->request->getGet('genere') ?? '';
        $anno   = $this->request->getGet('anno') ?? '';

        $query = $db->query("SELECT * FROM cerca_films(?, ?, ?)", [$genere, $anno, $titolo]);
        $films = $query->getResultArray();

        $userFavorites = [];
        if ($isLoggedIn) {
            $favQuery = $db->table('preferiti')->select('film_id')->where('username', $username)->get()->getResultArray();
            $userFavorites = array_column($favQuery, 'film_id');
        }

        $data = [
            'films'           => $films,
            'userFavorites'   => $userFavorites,
            'username'        => $username,
            'isLoggedIn'      => $isLoggedIn,
            'generi'          => $model->select('genere')->distinct()->findAll(),
            'anni'            => $model->select('anno')->distinct()->orderBy('anno', 'DESC')->findAll(),
            'film_del_giorno' => $filmRandom
        ];

        return view('films/index', $data);
    }

    /**
     * PAGINA DETTAGLIO FILM
     */
    public function view($id)
    {
        $model = new FilmModel();
        $film = $model->find($id);

        if (!$film) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Film con ID $id non trovato");
        }

        $db = \Config\Database::connect();
        $username = session()->get('username') ?? null;
        $isLoggedIn = session()->get('isLoggedIn') ?? false;

        $data = [
            'film'       => $film,
            'username'   => $username,
            'isLoggedIn' => $isLoggedIn,
            'title'      => $film['titolo'],
            'recensioni' => $db->table('recensioni')
                               ->where('film_id', $id)
                               ->orderBy('data_recensione', 'DESC')
                               ->get()
                               ->getResultArray()
        ];

        return view('films/view', $data);
    }

    /**
     * SALVA RECENSIONE E GESTISCI SFIDE
     */
    public function saveReview()
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to(base_url('index.php/login'));
    }
    
    $db = \Config\Database::connect();
    $username = session()->get('username');
    
    $film_id = $this->request->getPost('film_id');
    $voto = $this->request->getPost('voto');
    $commento = $this->request->getPost('commento');
    $rewatch = $this->request->getPost('rewatch') ? 1 : 0;

    // 1. SALVATAGGIO RECENSIONE
    $db->table('recensioni')->insert([
        'username'        => $username,
        'film_id'         => (int)$film_id,
        'voto'            => (int)$voto,
        'commento'        => $commento,
        'rewatch'         => $rewatch, 
        'data_recensione' => date('Y-m-d H:i:s')
    ]);

    // 2. RECUPERO GENERE DEL FILM
    $film = $db->table('films')->where('id', $film_id)->get()->getRowArray();
    $genere_film = trim($film['genere']); 

    // 3. CONTEGGIO RECENSIONI (Query SQL Pura per PostgreSQL)
    // Contiamo quante recensioni ha fatto l'utente per questo genere specifico
    $sql = "SELECT COUNT(*) as totale 
            FROM recensioni 
            JOIN films ON films.id = recensioni.film_id 
            WHERE recensioni.username = ? 
            AND films.genere ILIKE ?";
    $query = $db->query($sql, [$username, $genere_film]);
    $countGenere = $query->getRow()->totale;

    // 4. DETERMINA NOME SFIDA
    $sfidaNome = "";
    $livello = 0;
    $genere_lower = strtolower($genere_film);

    // Usiamo >= così se il sistema si è perso delle sfide passate, le sblocca ora
    if ($countGenere >= 1) {
        $livello = 1;
        if ($genere_lower == 'horror') $sfidaNome = "Brivido Notturno";
        elseif ($genere_lower == 'azione') $sfidaNome = "Eroe Action";
        elseif ($genere_lower == 'fantascienza' || $genere_lower == 'sci-fi') $sfidaNome = "Primo Contatto";
        elseif ($genere_lower == 'thriller') $sfidaNome = "Sotto Tensione";
        elseif ($genere_lower == 'animazione') $sfidaNome = "Sognatore";
        else $sfidaNome = "Novizio " . $genere_film;
    } 
    
    // Se ne ha fatte 3 o più, proviamo a sbloccare anche il livello 2
    if ($countGenere >= 3) {
        if ($genere_lower == 'horror') { $sfidaNome = "Maestro del Terrore"; $livello = 2; }
        elseif ($genere_lower == 'azione') { $sfidaNome = "Duro a Morire"; $livello = 2; }
    }

    // 5. SCRITTURA NEL DBMS
    if ($sfidaNome != "") {
        // Controlliamo se QUESTA sfida specifica esiste già
        $check = $db->table('user_challenges')
                    ->where(['username' => $username, 'sfida_nome' => $sfidaNome])
                    ->get()->getRow();

        if (!$check) {
            $db->table('user_challenges')->insert([
                'username'           => $username,
                'sfida_nome'         => $sfidaNome,
                'livello'            => $livello,
                'completata'         => true,
                'data_completamento' => date('Y-m-d H:i:s')
            ]);
            
            session()->setFlashdata('challenge_unlocked', "Hai sbloccato: <b>$sfidaNome</b>");
        }
    }

    return redirect()->to(base_url('index.php/films/view/'.$film_id))->with('msg', 'Recensione salvata!');
}
    /**
     * AGGIUNGI/RIMUOVI DALLA WATCHLIST
     */
    public function toggleWatchlist($film_id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $username = session()->get('username');
        $builder = $db->table('watchlist');
        $check = $builder->where(['username' => $username, 'film_id' => $film_id])->get()->getRow();
        if ($check) {
            $builder->where(['username' => $username, 'film_id' => $film_id])->delete();
            return redirect()->back()->with('msg', "Rimosso dalla Watchlist");
        } else {
            $builder->insert(['username' => $username, 'film_id' => $film_id]);
            return redirect()->back()->with('msg', "Aggiunto alla Watchlist!");
        }
    }

    /**
     * AGGIUNGI/RIMUOVI DAI PREFERITI
     */
    public function toggleFavorite($film_id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('index.php/login'));
        $db = \Config\Database::connect();
        $username = session()->get('username');
        $builder = $db->table('preferiti');
        $check = $builder->where(['username' => $username, 'film_id' => $film_id])->get()->getRow();
        if ($check) {
            $builder->where(['username' => $username, 'film_id' => $film_id])->delete();
            return redirect()->back()->with('msg', "Rimosso dai preferiti");
        } else {
            $builder->insert(['username' => $username, 'film_id' => $film_id]);
            return redirect()->back()->with('msg', "Aggiunto ai preferiti!");
        }
    }
}