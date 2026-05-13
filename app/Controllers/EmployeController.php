<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\DemandeCongeModel;
use App\Models\SoldeCongeModel;
use App\Models\TypeCongeModel;

class EmployeController extends BaseController
{
    protected $demandeModel;
    protected $soldeModel;
    protected $typeModel;
    protected $employeModel;

    public function __construct()
    {
        $this->demandeModel = new DemandeCongeModel();
        $this->soldeModel = new SoldeCongeModel();
        $this->typeModel = new TypeCongeModel();
        $this->employeModel = new EmployeModel();
    }

    public function dashboard()
    {
        $user_id = session()->get('user_id');

        $demandes = $this->demandeModel->getByEmploye($user_id);

        $stats = [
            'en_attente' => count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente')),
            'approuvees' => count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee')),
            'refusees' => count(array_filter($demandes, fn($d) => $d['statut'] === 'refusee')),
            'jours_restants' => 0 // À calculer selon les soldes
        ];

        $year = date('Y');
        $soldes = $this->soldeModel->getByEmployeAndYear($user_id, $year);

        $soldes_formatted = [];
        $jours_restants_total = 0;

        foreach ($soldes as $solde) {
            $jours_restants = $solde['jours_total'] - $solde['jours_pris'];
            $jours_restants_total += $jours_restants;

            $soldes_formatted[] = [
                'type_conge' => $solde['type_conge'],
                'jours_total' => $solde['jours_total'],
                'jours_pris' => $solde['jours_pris'],
                'jours_restants' => $jours_restants
            ];
        }

        $stats['jours_restants'] = $jours_restants_total;

        return view('employe/dashboard', [
            'title' => 'Tableau de bord',
            'breadcrumb_app' => 'Espace employé',
            'demandes' => $demandes,
            'soldes' => $soldes_formatted,
            'stats' => $stats
        ]);
    }

    public function create()
    {
        $year = date('Y');
        $user_id = session()->get('user_id');

        $types_conge = $this->typeModel->findAll();

        $soldes = $this->soldeModel->getByEmployeAndYear($user_id, $year);

        return view('employe/create', [
            'title' => 'Nouvelle demande de congé',
            'breadcrumb_app' => 'Espace employé',
            'types_conge' => $types_conge,
            'soldes' => $soldes
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'type_conge_id' => 'required|integer|greater_than[0]',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $user_id = session()->get('user_id');
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $type_conge_id = $this->request->getPost('type_conge_id');

        // Vérifier le chevauchement
        if ($this->demandeModel->checkOverlap($user_id, $date_debut, $date_fin)) {
            return redirect()->back()
                ->with('error', 'Attention: Une autre demande existe déjà sur ces dates.')
                ->withInput();
        }

        $d1 = new \DateTime($date_debut);
        $d2 = new \DateTime($date_fin);
        $interval = $d1->diff($d2);
        $jours_demandes = $interval->days + 1;

        // Vérifier le solde
        $year = date('Y');
        if (!$this->soldeModel->hasEnoughDays($user_id, $type_conge_id, $jours_demandes, $year)) {
            $solde = $this->soldeModel->getSolde($user_id, $type_conge_id, $year);
            $jours_disponibles = $solde ? ($solde['jours_total'] - $solde['jours_pris']) : 0;
            return redirect()->back()
                ->with('error', "Solde insuffisant. Vous avez $jours_disponibles jour(s) disponible(s).")
                ->withInput();
        }

        // Créer la demande
        $this->demandeModel->insert([
            'employe_id' => $user_id,
            'type_conge_id' => $type_conge_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'motif' => $this->request->getPost('motif') ?? '',
            'statut' => 'en_attente'
        ]);

        return redirect()->to(route_to('employe.dashboard'))
            ->with('message', '✓ Demande soumise avec succès. Elle est en attente de validation par le RH.');
    }

    public function index()
    {
        $user_id = session()->get('user_id');
        $demandes = $this->demandeModel->getByEmploye($user_id);

        return view('employe/index', [
            'title' => 'Mes demandes de congé',
            'breadcrumb_app' => 'Espace employé',
            'demandes' => $demandes
        ]);
    }

    public function profile()
    {
        $user_id = session()->get('user_id');
        $employe = $this->employeModel->getWithDept($user_id);

        return view('employe/profile', [
            'title' => 'Mon profil',
            'breadcrumb_app' => 'Espace employé',
            'employe' => $employe
        ]);
    }

    public function cancel()
    {
        $user_id = session()->get('user_id');
        $id = $this->request->getPost('id');

        $demande = $this->demandeModel->find($id);

        if (!$demande || $demande['employe_id'] != $user_id) {
            return redirect()->back()
                ->with('error', 'Demande non trouvée');
        }

        if ($demande['statut'] !== 'en_attente') {
            return redirect()->back()
                ->with('error', 'Seules les demandes en attente peuvent être annulées');
        }

        $this->demandeModel->update($id, 'statut', 'annulee');

        return redirect()->back()
            ->with('message', 'Demande annulée avec succès');
    }
}
