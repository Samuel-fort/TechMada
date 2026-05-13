<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table = 'types_conge';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['nom', 'jours_max'];

    protected $validationRules = [
        'nom' => 'required|string|max_length[255]',
        'jours_max' => 'required|integer|greater_than[0]'
    ];
}
