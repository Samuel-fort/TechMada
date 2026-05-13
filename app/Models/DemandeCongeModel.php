<?php

namespace App\Models;

use CodeIgniter\Model;

class DemandeCongeModel extends Model
{
    protected $table = 'demandes_conge';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'motif',
        'statut',
        'commentaire_rh',
        'traite_par',
        'updated_at'
    ];

    protected $validationRules = [
        'employe_id' => 'required|integer',
        'type_conge_id' => 'required|integer',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'statut' => 'in_list[en_attente,approuvee,refusee,annulee]'
    ];

    public function getByEmploye($employe_id)
    {
        return $this->select('demandes_conge.*, types_conge.nom as type_conge')
                    ->join('types_conge', 'types_conge.id = demandes_conge.type_conge_id')
                    ->where('demandes_conge.employe_id', $employe_id)
                    ->orderBy('demandes_conge.created_at', 'DESC')
                    ->findAll();
    }

    public function getPending()
    {
        return $this->select('demandes_conge.*, employes.nom as employe_nom, employes.departement_id,
                            departements.nom as departement_nom, types_conge.nom as type_conge')
                    ->join('employes', 'employes.id = demandes_conge.employe_id')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = demandes_conge.type_conge_id')
                    ->where('demandes_conge.statut', 'en_attente')
                    ->orderBy('demandes_conge.created_at', 'ASC')
                    ->findAll();
    }

    public function checkOverlap($employe_id, $date_debut, $date_fin, $exclude_id = null)
    {
        $query = $this->where('employe_id', $employe_id)
                      ->where('statut !=', 'refusee')
                      ->where('statut !=', 'annulee')
                      ->where('date_debut <=', $date_fin)
                      ->where('date_fin >=', $date_debut);

        if ($exclude_id) {
            $query = $query->where('id !=', $exclude_id);
        }

        return $query->countAllResults() > 0;
    }

    public function getHistory($limit = 50)
    {
        return $this->select('demandes_conge.*, employes.nom as employe_nom, employes.departement_id,
                            departements.nom as departement_nom, types_conge.nom as type_conge')
                    ->join('employes', 'employes.id = demandes_conge.employe_id')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->join('types_conge', 'types_conge.id = demandes_conge.type_conge_id')
                    ->where('demandes_conge.statut !=', 'en_attente')
                    ->orderBy('demandes_conge.updated_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
