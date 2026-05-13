<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(route_to('auth.login'));
        }

        $role = session()->get('user_role');

        if ($role === 'admin') {
            return redirect()->to(route_to('admin.dashboard'));
        } elseif ($role === 'rh') {
            return redirect()->to(route_to('rh.dashboard'));
        } else {
            return redirect()->to(route_to('employe.dashboard'));
        }
    }
}
