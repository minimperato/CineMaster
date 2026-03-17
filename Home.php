<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // Mostra la pagina home.php
        return view('home');
    }
}