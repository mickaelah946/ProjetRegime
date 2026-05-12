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
        // Si deja connecte, rediriger au dashboard
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

        // Verification securisee avec password_verify()
        if ($user && password_verify($password, $user['password_hash'])) {
            // Connexion reussie
            session()->set([
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'nom'        => $user['nom'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/dashboard')->with('success', 'Connexion reussie !');
        } else {
            // Connexion echouee
            return redirect()->back()->with('error', 'Identifiants incorrects !');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Deconnexion reussie !');
    }

    // ============================================
    // REGISTRATION - STEP 1 (Infos personnelles)
    // ============================================
    public function registerStep1()
    {
        // Si deja connecte, rediriger
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
        ];

        $messages = [
            'nom' => [
                'required' => 'Le nom complet est requis',
                'min_length' => 'Le nom doit avoir au moins 3 caractères',
                'max_length' => 'Le nom ne peut pas dépasser 100 caractères',
            ],
            'email' => [
                'required' => 'L\'email est requis',
                'valid_email' => 'Veuillez entrer un email valide',
                'is_unique' => 'Cet email est déjà utilisé',
            ],
            'username' => [
                'required' => 'Le nom d\'utilisateur est requis',
                'min_length' => 'Le nom d\'utilisateur doit avoir au moins 3 caractères',
                'max_length' => 'Le nom d\'utilisateur ne peut pas dépasser 100 caractères',
                'is_unique' => 'Ce nom d\'utilisateur est déjà utilisé',
            ],
            'genre' => [
                'required' => 'Veuillez sélectionner un genre',
                'in_list' => 'Le genre doit être Homme ou Femme',
            ],
            'password' => [
                'required' => 'Le mot de passe est requis',
                'min_length' => 'Le mot de passe doit avoir au moins 6 caractères',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Sauvegarder dans session temporaire
        session()->set([
            'temp_nom'      => $this->request->getPost('nom'),
            'temp_email'    => $this->request->getPost('email'),
            'temp_username' => $this->request->getPost('username'),
            'temp_genre'    => $this->request->getPost('genre'),
            'temp_password' => $this->request->getPost('password'), // ⚠️ DEVELOPMENT: mot de passe en clair
        ]);

        return redirect()->to('/register/step2')->with('success', 'etape 1 completee !');
    }

    // ============================================
    // REGISTRATION - STEP 2 (Infos sante)
    // ============================================
    public function registerStep2()
    {
        // Verifier que step 1 est completee
        if (!session()->get('temp_username')) {
            return redirect()->to('/register/step1')->with('error', 'Veuillez d\'abord completer l\'etape 1');
        }

        return view('auth/register_step2');
    }

    public function saveStep2()
    {
        // Verifier que step 1 est completee
        if (!session()->get('temp_username')) {
            return redirect()->to('/register/step1')->with('error', 'Session expiree, recommencez');
        }

        $rules = [
            'taille' => 'required|numeric|greater_than[1.0]|less_than[3.0]',
            'poids'  => 'required|numeric|greater_than[20]|less_than[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Recuperer les donnees de session
        $userData = [
            'nom'           => session()->get('temp_nom'),
            'email'         => session()->get('temp_email'),
            'username'      => session()->get('temp_username'),
            'password_hash' => password_hash(session()->get('temp_password'), PASSWORD_BCRYPT),
            'genre'         => session()->get('temp_genre'),
            'taille'        => $this->request->getPost('taille'),
            'poids'         => $this->request->getPost('poids'),
            'role'          => 'user',
        ];

        // Creer l'utilisateur
        if ($this->userModel->insert($userData)) {
            // Recuperer les donnees du nouvel utilisateur
            $newUser = $this->userModel->where('username', $userData['username'])->first();

            // Connexion automatique
            session()->set([
                'user_id'    => $newUser['id'],
                'username'   => $newUser['username'],
                'email'      => $newUser['email'],
                'nom'        => $newUser['nom'],
                'role'       => $newUser['role'],
                'isLoggedIn' => true,
            ]);

            // Effacer la session temporaire
            session()->remove(['temp_nom', 'temp_email', 'temp_username', 'temp_genre', 'temp_password']);

            return redirect()->to('/dashboard')->with('success', 'Bienvenue ! Votre compte a été créé avec succès.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'inscription');
        }
    }
}

