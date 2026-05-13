<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['nom'];

    protected $validationRules = [
        'nom' => 'required|string|max_length[255]|is_unique[departements.nom]'
    ];
}
