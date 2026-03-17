<?php
namespace App\Models;
use CodeIgniter\Model;

class FilmModel extends Model
{
    protected $table = 'films';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'body'];
    public function getFilms()
    {
        return $this->findAll();
    }
}