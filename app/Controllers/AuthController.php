<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    protected $employeModel;

    public function __construct()
    {
        $this->employeModel = new EmployeModel();
    }

    public function login()
    {
        return view('auth/login', [
            'title' => 'Connexion'
        ]);
    }

    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $employe = $this->employeModel->getByEmail($email);

        if ($employe && password_verify($password, $employe['mot_de_passe'])) {
            $employe_complet = $this->employeModel->getWithDept($employe['id']);
            session()->set([
                'isLoggedIn' => true,
                'user_id' => $employe['id'],
                'user_nom' => $employe['nom'],
                'user_role' => $employe['role'],
                'user_departement_id' => $employe_complet['departement_id'] ?? null,
                'user_departement_nom' => $employe_complet['departement_nom'] ?? null
            ]);

            return redirect()->to(route_to('dashboard'))->with('message', 'Bienvenue ' . $employe['nom'] . '!');
        }

        return redirect()->back()
            ->with('error', 'Email ou mot de passe incorrect')
            ->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(route_to('auth.login'))->with('message', 'Vous avez été déconnecté');
    }

    public function register()
    {
        return view('auth/register', [
            'title' => 'Inscription'
        ]);
    }

    public function storeUser()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nom' => 'required|string|max_length[255]',
            'email' => 'required|valid_email|is_unique[employes.email]',
            'password' => 'required|min_length[6]|max_length[255]',
            'password_confirm' => 'required|matches[password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        $hashed_password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $this->employeModel->insert([
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'mot_de_passe' => $hashed_password,
            'role' => 'employe',
            'departement_id' => null,
            'actif' => 1
        ]);

        return redirect()->to(route_to('auth.login'))
            ->with('message', 'Inscription réussie! Connectez-vous maintenant.');
    }
}
