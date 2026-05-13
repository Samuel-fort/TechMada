<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nom', 'email', 'mot_de_passe', 'role', 'departement_id', 'actif'];

    protected $validationRules = [
        'nom' => 'required|string|max_length[255]',
        'email' => 'required|valid_email|is_unique[employes.email]',
        'mot_de_passe' => 'required|min_length[6]',
        'role' => 'in_list[employe,rh,admin]',
        'departement_id' => 'integer'
    ];

    protected $skipValidation = false;

    public function getByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('actif', 1)
                    ->first();
    }

    public function getActive()
    {
        return $this->where('actif', 1)
                    ->orderBy('nom', 'ASC')
                    ->findAll();
    }

    public function getWithDept($id)
    {
        return $this->select('employes.*, departements.nom as departement_nom')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->where('employes.id', $id)
                    ->first();
    }
}
