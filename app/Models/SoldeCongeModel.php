<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeCongeModel extends Model
{
    protected $table = 'soldes_conge';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',
        'jours_total',
        'jours_pris'
    ];

    // Récupérer les soldes d'un employé une année donnée
    public function getByEmployeAndYear($employe_id, $year)
    {
        return $this->select('soldes_conge.*, types_conge.nom as type_conge, types_conge.jours_max')
                    ->join('types_conge', 'types_conge.id = soldes_conge.type_conge_id')
                    ->where('soldes_conge.employe_id', $employe_id)
                    ->where('soldes_conge.annee', $year)
                    ->findAll();
    }

    // Récupérer un solde spécifique
    public function getSolde($employe_id, $type_conge_id, $year)
    {
        return $this->where('employe_id', $employe_id)
                    ->where('type_conge_id', $type_conge_id)
                    ->where('annee', $year)
                    ->first();
    }

    // Vérifier si y'a assez de jours
    public function hasEnoughDays($employe_id, $type_conge_id, $days_needed, $year)
    {
        $solde = $this->getSolde($employe_id, $type_conge_id, $year);
        
        if (!$solde) {
            return false;
        }

        $jours_restants = $solde['jours_total'] - $solde['jours_pris'];
        return $jours_restants >= $days_needed;
    }

    // Déduire les jours après approbation
    public function deduceDays($employe_id, $type_conge_id, $days, $year)
    {
        $solde = $this->getSolde($employe_id, $type_conge_id, $year);
        
        if (!$solde) {
            return false;
        }

        $new_jours_pris = $solde['jours_pris'] + $days;
        
        return $this->update($solde['id'], [
            'jours_pris' => $new_jours_pris
        ]);
    }

    // Restaurer les jours (annulation ou refus)
    public function restoreDays($employe_id, $type_conge_id, $days, $year)
    {
        $solde = $this->getSolde($employe_id, $type_conge_id, $year);
        
        if (!$solde) {
            return false;
        }

        $new_jours_pris = max(0, $solde['jours_pris'] - $days);
        
        return $this->update($solde['id'], [
            'jours_pris' => $new_jours_pris
        ]);
    }

    // Initialiser les soldes pour un employé (début d'année)
    public function initializeSoldes($employe_id, $year)
    {
        $db = \Config\Database::connect();
        
        // Get all types de congé
        $typesResult = $db->query("SELECT id, jours_max FROM types_conge");
        $types = $typesResult->getResultArray();
        
        foreach ($types as $type) {
            // Check if solde already exists
            $exist = $this->getSolde($employe_id, $type['id'], $year);
            
            if (!$exist) {
                $this->insert([
                    'employe_id' => $employe_id,
                    'type_conge_id' => $type['id'],
                    'annee' => $year,
                    'jours_total' => $type['jours_max'],
                    'jours_pris' => 0
                ]);
            }
        }
        
        return true;
    }
}
