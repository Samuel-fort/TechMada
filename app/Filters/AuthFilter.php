<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!empty($arguments)) {
            $user_role = session()->get('user_role');
            
            if (in_array($user_role, $arguments)) {
                return; // Accès autorisé
            }
            
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
