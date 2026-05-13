<?php

if (! function_exists('auth')) {
    /**
     * Get the currently authenticated user
     *
     * @return object|null
     */
    function auth()
    {
        return new class {
            /**
             * Get the currently authenticated user data
             *
             * @return array|null
             */
            public function user()
            {
                if (session('isLoggedIn')) {
                    return [
                        'id' => session('user_id'),
                        'nom' => session('user_nom'),
                        'role' => session('user_role'),
                        'departement_id' => session('user_departement_id'),
                        'departement_nom' => session('user_departement_nom'),
                    ];
                }
                return null;
            }

            /**
             * Check if user is logged in
             *
             * @return bool
             */
            public function isLoggedIn()
            {
                return session('isLoggedIn') === true;
            }

            /**
             * Check if user has a specific role
             *
             * @param string $role
             * @return bool
             */
            public function hasRole($role)
            {
                return session('user_role') === $role;
            }

            /**
             * Check if user is admin
             *
             * @return bool
             */
            public function isAdmin()
            {
                return session('user_role') === 'admin';
            }

            /**
             * Check if user is RH
             *
             * @return bool
             */
            public function isRh()
            {
                return session('user_role') === 'rh';
            }
        };
    }
}

if (! function_exists('getInitials')) {
    /**
     * Get initials from a full name
     *
     * @param string $name
     * @return string
     */
    function getInitials($name)
    {
        $words = explode(' ', trim($name));
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) $initials .= strtoupper($word[0]);
        }
        return substr($initials, 0, 2) ?: 'UN';
    }
}
