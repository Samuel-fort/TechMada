<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Vérifier le rôle s'il est spécifié
        if (!empty($arguments)) {
            $user_role = session()->get('user_role');
            
            // Si le premier argument est un rôle
            if (in_array($user_role, $arguments)) {
                return; // Accès autorisé
            }
            
            // Sinon, redirection
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
