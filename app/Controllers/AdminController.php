<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\DepartementModel;
use App\Models\TypeCongeModel;
use App\Models\SoldeCongeModel;
use App\Models\DemandeCongeModel;

class AdminController extends BaseController
{
    protected $employeModel;
    protected $departementModel;
    protected $typeModel;
    protected $soldeModel;
    protected $demandeModel;

    public function __construct()
    {
        $this->employeModel = new EmployeModel();
        $this->departementModel = new DepartementModel();
        $this->typeModel = new TypeCongeModel();
        $this->soldeModel = new SoldeCongeModel();
        $this->demandeModel = new DemandeCongeModel();
    }

    public function dashboard()
    {
        $year = date('Y');
        $employes = $this->employeModel->getActive();
        $pending = $this->demandeModel->getPending();

        $stats = [
            'employes' => count($employes),
            'pending' => count($pending),
            'total_approved' => count(array_filter(
                $this->demandeModel->getHistory(1000),
                fn($d) => $d['statut'] === 'approuvee' && date('m-Y', strtotime($d['created_at'])) === date('m-Y')
            ))
        ];

        return view('admin/dashboard', [
            'title' => 'Vue d\'ensemble',
            'breadcrumb_app' => 'Administration',
            'stats' => $stats,
            'employes' => $employes
        ]);
    }

    public function employes()
    {
        $employes = $this->employeModel->findAll();
        $departements = $this->departementModel->findAll();

        $year = date('Y');
        foreach ($employes as &$emp) {
            $soldes = $this->soldeModel->getByEmployeAndYear($emp['id'], $year);
            $emp['soldes'] = $soldes;
        }

        return view('admin/employes', [
            'title' => 'Gestion des employés',
            'breadcrumb_app' => 'Administration',
            'employes' => $employes,
            'departements' => $departements
        ]);
    }

    public function storeEmploye()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nom' => 'required|string|max_length[255]',
            'email' => 'required|valid_email|is_unique[employes.email]',
            'mot_de_passe' => 'required|min_length[6]',
            'role' => 'required|in_list[employe,rh,admin]',
            'departement_id' => 'integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $hashed_password = password_hash($this->request->getPost('mot_de_passe'), PASSWORD_DEFAULT);

        $this->employeModel->insert([
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'mot_de_passe' => $hashed_password,
            'role' => $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id') ?: null,
            'actif' => 1
        ]);

        $new_id = $this->employeModel->getInsertID();
        $this->soldeModel->initializeSoldes($new_id, date('Y'));

        return redirect()->to(route_to('admin.employes'))
            ->with('message', 'Employé créé avec succès');
    }

    public function editEmploye($id)
    {
        $employe = $this->employeModel->find($id);
        if (!$employe) {
            return redirect()->to(route_to('admin.employes'))->with('error', 'Employé non trouvé');
        }

        $departements = $this->departementModel->findAll();

        return view('admin/edit_employe', [
            'title' => 'Éditer l\'employé',
            'breadcrumb_app' => 'Administration',
            'employe' => $employe,
            'departements' => $departements
        ]);
    }

    public function updateEmploye($id)
    {
        $employe = $this->employeModel->find($id);
        if (!$employe) {
            return redirect()->to(route_to('admin.employes'))->with('error', 'Employé non trouvé');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nom' => 'required|string|max_length[255]',
            'role' => 'required|in_list[employe,rh,admin]',
            'departement_id' => 'integer',
            'actif' => 'integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $this->employeModel->update($id, [
            'nom' => $this->request->getPost('nom'),
            'role' => $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id') ?: null,
            'actif' => $this->request->getPost('actif') ?? 1
        ]);

        return redirect()->to(route_to('admin.employes'))
            ->with('message', 'Employé mis à jour');
    }

    public function disableEmploye($id)
    {
        $this->employeModel->update($id, ['actif' => 0]);
        return redirect()->to(route_to('admin.employes'))
            ->with('message', 'Employé désactivé');
    }

    public function departements()
    {
        $departements = $this->departementModel->findAll();

        return view('admin/departements', [
            'title' => 'Gestion des départements',
            'breadcrumb_app' => 'Administration',
            'departements' => $departements
        ]);
    }

    public function storeDepartement()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nom' => 'required|string|max_length[255]|is_unique[departements.nom]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $this->departementModel->insert([
            'nom' => $this->request->getPost('nom')
        ]);

        return redirect()->back()->with('message', 'Département créé');
    }

    public function typesCong()
    {
        $types = $this->typeModel->findAll();

        return view('admin/types_conge', [
            'title' => 'Gestion des types de congé',
            'breadcrumb_app' => 'Administration',
            'types' => $types
        ]);
    }

    public function storeTypeConge()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nom' => 'required|string|max_length[255]',
            'jours_max' => 'required|integer|greater_than[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $this->typeModel->insert([
            'nom' => $this->request->getPost('nom'),
            'jours_max' => $this->request->getPost('jours_max')
        ]);

        return redirect()->back()->with('message', 'Type de congé créé');
    }

    public function soldes()
    {
        $year = date('Y');
        $employes = $this->employeModel->getActive();

        return view('admin/soldes', [
            'title' => 'Gestion des soldes annuels',
            'breadcrumb_app' => 'Administration',
            'employes' => $employes,
            'year' => $year
        ]);
    }
}
