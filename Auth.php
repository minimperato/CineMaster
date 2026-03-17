<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    /**
     * PAGINA DI BENVENUTO (Landing Page)
     */
    public function index()
    {
        // Se l'utente è già loggato, lo mandiamo direttamente ai film
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('index.php/films'));
        }
        return view('welcome_cinemaster');
    }

    // --- 1. REGISTRAZIONE ---
    public function register()
    {
        helper(['form', 'url']);
        
        if ($this->request->is('post')) {
            $rules = [
                'username' => 'required|is_unique[users.username]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[5]'
            ];

            if (!$this->validate($rules)) {
                return view('templates/header', ['title' => 'Registrazione'])
                     . view('auth/register', ['validation' => $this->validator])
                     . view('templates/footer');
            }

            $model = new UserModel();
            $model->save([
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'     => 'user'
            ]);

            session()->setFlashdata('success_reg', 'Registrazione avvenuta con successo! Ora puoi accedere.');

            return redirect()->to(base_url('index.php/login'));
        }

        return view('templates/header', ['title' => 'Registrazione'])
             . view('auth/register')
             . view('templates/footer');
    }

    // --- 2. LOGIN ---
    public function login()
    {
        helper(['form', 'url']);
        $session = session();

        if ($this->request->is('post')) {
            $model = new UserModel();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $user = $model->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session->remove(['isLoggedIn', 'username', 'email']);
                $session->set([
                    'isLoggedIn' => true,
                    'username'   => $user['username'],
                    'email'      => $user['email']
                ]);
                
                return redirect()->to(base_url('index.php/films')); 
            } else {
                return redirect()->to(base_url('index.php/login'))->with('error', 'Email o Password errati.');
            }
        }

        return view('templates/header', ['title' => 'Login'])
             . view('auth/login')
             . view('templates/footer');
    }

    // --- 3. LOGOUT ---
    public function logout() 
    {
        session()->destroy();
        return redirect()->to(base_url('index.php/login'));
    }

    //---- 4.CONTINUA COME OSPITE---
    public function continuaComeOspite()
{
    $session = session();
    
    // 1. Rimuovi tutti i dati specifici dell'utente (id, username, login status)
    $session->destroy(); 
    
    // 2. Opzionale ma consigliato: se hai salvato i dati in un array 'user', puliscilo
    // $session->remove(['isLoggedIn', 'user_id', 'username']);

    // 3. Reindirizza alla pagina dei film
    // Nota: assicurati che base_url sia configurato correttamente nel file .env o App.php
    return redirect()->to(base_url('films')); 
}
}