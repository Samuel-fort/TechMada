<?php

namespace App\Controllers;

use App\Models\DemandeCongeModel;
use App\Models\SoldeCongeModel;
use App\Models\EmployeModel;
use App\Models\DepartementModel;

class RhController extends BaseController
{
    protected $demandeModel;
    protected $soldeModel;
    protected $employeModel;
    protected $departementModel;

    public function __construct()
    {
        $this->demandeModel = new DemandeCongeModel();
        $this->soldeModel = new SoldeCongeModel();
        $this->employeModel = new EmployeModel();
        $this->departementModel = new DepartementModel();
    }

    // Dashboard RH
    public function dashboard()
    {
        // Demandes en attente
        $pending = $this->demandeModel->getPending();
        $nb_pending = count($pending);

        // Demandes traitées ce mois
        $history = $this->demandeModel->getHistory(100);
        $this_month = array_filter($history, function($d) {
            return date('m-Y', strtotime($d['created_at'])) === date('m-Y');
        });

        $stats = [
            'pending' => $nb_pending,
            'approved_this_month' => count(array_filter($this_month, fn($d) => $d['statut'] === 'approuvee')),
            'total_employes' => count($this->employeModel->getActive())
        ];

        return view('rh/dashboard', [
            'title' => 'Tableau de bord',
            'breadcrumb_app' => 'Espace RH',
            'stats' => $stats,
            'pending' => $pending
        ]);
    }

    // Lister les demandes à traiter
    public function index()
    {
        $demandes = $this->demandeModel->getPending();
        $departements = $this->departementModel->findAll();

        // Ajouter les jours disponibles pour chaque demande
        $demandes_with_soldes = [];
        foreach ($demandes as $demande) {
            $year = date('Y');
            $solde = $this->soldeModel->getSolde(
                $demande['employe_id'],
                $demande['type_conge_id'],
                $year
            );

            // Calculer le nombre de jours
            $d1 = new \DateTime($demande['date_debut']);
            $d2 = new \DateTime($demande['date_fin']);
            $interval = $d1->diff($d2);
            $jours_demandes = $interval->days + 1;

            $demande['jours_disponibles'] = ($solde['jours_total'] - $solde['jours_pris']) - $jours_demandes;
            $demandes_with_soldes[] = $demande;
        }

        return view('rh/index', [
            'title' => 'Demandes à traiter',
            'breadcrumb_app' => 'Espace RH',
            'demandes' => $demandes_with_soldes,
            'departements' => $departements
        ]);
    }

    // Approuver une demande
    public function approve()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        $id = $this->request->getPost('id');
        $user_id = session()->get('user_id');

        // Récupérer la demande
        $demande = $this->demandeModel->find($id);
        if (!$demande || $demande['statut'] !== 'en_attente') {
            return $this->response->setJSON(['success' => false, 'message' => 'Demande invalide']);
        }

        // Calculer le nombre de jours
        $d1 = new \DateTime($demande['date_debut']);
        $d2 = new \DateTime($demande['date_fin']);
        $interval = $d1->diff($d2);
        $jours_demandes = $interval->days + 1;

        // Déduire du solde
        $year = date('Y');
        $this->soldeModel->deduceDays(
            $demande['employe_id'],
            $demande['type_conge_id'],
            $jours_demandes,
            $year
        );

        // Mettre à jour la demande
        $this->demandeModel->update($id, [
            'statut' => 'approuvee',
            'traite_par' => $user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Demande approuvée avec succès'
        ]);
    }

    // Refuser une demande
    public function refuse()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false]);
        }

        $id = $this->request->getPost('id');
        $commentaire = $this->request->getPost('commentaire_rh') ?? '';
        $user_id = session()->get('user_id');

        // Récupérer la demande
        $demande = $this->demandeModel->find($id);
        if (!$demande || $demande['statut'] !== 'en_attente') {
            return $this->response->setJSON(['success' => false, 'message' => 'Demande invalide']);
        }

        // Mettre à jour la demande
        $this->demandeModel->update($id, [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire,
            'traite_par' => $user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Demande refusée'
        ]);
    }

    // Historique
    public function history()
    {
        $demandes = $this->demandeModel->getHistory();
        $departements = $this->departementModel->findAll();

        return view('rh/history', [
            'title' => 'Historique des demandes',
            'breadcrumb_app' => 'Espace RH',
            'demandes' => $demandes,
            'departements' => $departements
        ]);
    }

    // Gestion des soldes
    public function soldes()
    {
        $year = date('Y');
        $employes = $this->employeModel->getActive();
        $departements = $this->departementModel->findAll();

        // Récupérer les soldes de chaque employé
        $employe_soldes = [];
        foreach ($employes as $emp) {
            $soldes = $this->soldeModel->getByEmployeAndYear($emp['id'], $year);
            $employe_soldes[] = [
                'employe' => $emp,
                'soldes' => $soldes
            ];
        }

        return view('rh/soldes', [
            'title' => 'Gestion des soldes',
            'breadcrumb_app' => 'Espace RH',
            'employe_soldes' => $employe_soldes,
            'year' => $year,
            'departements' => $departements
        ]);
    }
}
