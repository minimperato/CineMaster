<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    // Abilita i campi per l'inserimento dati
    protected $allowedFields = ['username', 'email', 'password', 'role'];
}