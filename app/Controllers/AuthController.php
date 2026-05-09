<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ============================================
    // LOGIN
    // ============================================
    public function login()
    {
        // Si déjà connecté, rediriger au dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Connexion réussie
            session()->set([
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/dashboard')->with('success', 'Connexion réussie !');
        } else {
            // Connexion échouée
            return redirect()->back()->with('error', 'Identifiants incorrects !');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Déconnexion réussie !');
    }

    // ============================================
    // REGISTRATION - STEP 1 (Infos personnelles)
    // ============================================
    public function registerStep1()
    {
        // Si déjà connecté, rediriger
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register_step1');
    }

    public function saveStep1()
    {
        $rules = [
            'nom'      => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'genre'    => 'required|in_list[M,F]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Sauvegarder dans session temporaire
        session()->set([
            'temp_nom'      => $this->request->getPost('nom'),
            'temp_email'    => $this->request->getPost('email'),
            'temp_username' => $this->request->getPost('username'),
            'temp_genre'    => $this->request->getPost('genre'),
            'temp_password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/register/step2')->with('success', 'Étape 1 complétée !');
    }

    // ============================================
    // REGISTRATION - STEP 2 (Infos santé)
    // ============================================
    public function registerStep2()
    {
        // Vérifier que step 1 est complétée
        if (!session()->get('temp_username')) {
            return redirect()->to('/register/step1')->with('error', 'Veuillez d\'abord compléter l\'étape 1');
        }

        return view('auth/register_step2');
    }

    public function saveStep2()
    {
        // Vérifier que step 1 est complétée
        if (!session()->get('temp_username')) {
            return redirect()->to('/register/step1')->with('error', 'Session expirée, recommencez');
        }

        $rules = [
            'taille' => 'required|numeric|greater_than[1.0]|less_than[3.0]',
            'poids'  => 'required|numeric|greater_than[20]|less_than[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Récupérer les données de session
        $userData = [
            'nom'           => session()->get('temp_nom'),
            'email'         => session()->get('temp_email'),
            'username'      => session()->get('temp_username'),
            'password_hash' => session()->get('temp_password'),
            'genre'         => session()->get('temp_genre'),
            'taille'        => $this->request->getPost('taille'),
            'poids'         => $this->request->getPost('poids'),
            'role'          => 'user',
        ];

        // Créer l'utilisateur
        if ($this->userModel->insert($userData)) {
            // Effacer la session temporaire
            session()->remove(['temp_nom', 'temp_email', 'temp_username', 'temp_genre', 'temp_password']);

            return redirect()->to('/login')->with('success', 'Inscription réussie ! Veuillez vous connecter.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'inscription');
        }
    }
}
