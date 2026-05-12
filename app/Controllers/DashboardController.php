<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ObjectifModel;
use App\Models\CodePortefeuilleModel;
use Config\Database;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $objectifModel;
    protected $codeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->objectifModel = new ObjectifModel();
        $this->codeModel = new CodePortefeuilleModel();
    }

    // ============================================
    // DASHBOARD PRINCIPAL
    // ============================================
    public function index()
    {
        // Verifier authentification
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        // Rediriger les admins vers le dashboard admin
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Utilisateur non trouve');
        }

        // Recuperer les objectifs de l'utilisateur
        $userObjectifs = $this->objectifModel->getUserObjectifs($userId);

        // Calculer l'IMC (poids / (taille * taille))
        $imc = $user['poids'] / ($user['taille'] * $user['taille']);

        // Determiner la categorie IMC
        $imcCategorie = $this->getIMCCategorie($imc);

        $data = [
            'user' => $user,
            'imc' => round($imc, 2),
            'imcCategorie' => $imcCategorie,
            'userObjectifs' => $userObjectifs,
        ];

        return view('dashboard/index', $data);
    }

    // ============================================
    // SELECTIONER LES 3 OBJECTIFS
    // ============================================
    public function selectObjectifs()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $userId = session()->get('user_id');
        $objectifs = $this->objectifModel->findAll();
        $userObjectifs = $this->objectifModel->getUserObjectifs($userId);

        $data = [
            'objectifs' => $objectifs,
            'userObjectifs' => $userObjectifs,
        ];

        return view('dashboard/select_objectifs', $data);
    }

    public function saveObjectifs()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $userId = session()->get('user_id');
        $selectedObjectifs = $this->request->getPost('objectifs');

        // Validation: doit choisir au moins 1 objectif
        if (!$selectedObjectifs || count($selectedObjectifs) < 1) {
            return redirect()->back()->with('error', 'Vous devez choisir au moins 1 objectif');
        }

        // Effacer les anciens objectifs
        $this->objectifModel->deleteUserObjectifs($userId);

        // Inserer les nouveaux
        foreach ($selectedObjectifs as $objectifId) {
            $this->objectifModel->addUserObjectif($userId, (int) $objectifId);
        }

        return redirect()->to('/regime/browse')->with('success', 'Objectifs mis a jour ! Decouvrez nos regimes recommandes');
    }

    // ============================================
    // VALIDER UN CODE PORTEFEUILLE
    // ============================================
    public function validateCode()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $code = trim($this->request->getPost('code'));
        $userId = session()->get('user_id');

        if (!$code) {
            return redirect()->back()->with('error', 'Veuillez entrer un code');
        }

        // Verifier le code
        $codeData = $this->codeModel->where('code', $code)->first();

        if (!$codeData) {
            return redirect()->back()->with('error', 'Code invalide');
        }

        if ($codeData['valide'] && $codeData['utilisateur_id'] === null) {
            // Code valide et non utilise
            $montant = (float)$codeData['montant'];
            $user = $this->userModel->find($userId);
            $newBalance = $user['solde_portefeuille'] + $montant;

            // Ajouter au solde
            $this->userModel->update($userId, [
                'solde_portefeuille' => $newBalance
            ]);

            // Marquer le code comme utilise
            $this->codeModel->update($codeData['id'], [
                'utilisateur_id' => $userId,
                'date_utilisation' => date('Y-m-d H:i:s'),
                'valide' => false,
            ]);

            return redirect()->back()->with('success', "+{$montant}€ ajoute au portefeuille !");
        } elseif ($codeData['utilisateur_id'] === $userId) {
            return redirect()->back()->with('error', 'Ce code a deja ete utilise par vous');
        } else {
            return redirect()->back()->with('error', 'Ce code a deja ete utilise');
        }
    }

    // ============================================
    // HELPER: Determiner categorie IMC
    // ============================================
    private function getIMCCategorie($imc)
    {
        if ($imc < 18.5) {
            return ['nom' => 'Maigre', 'couleur' => 'info', 'emoji' => '🔵'];
        } elseif ($imc < 25) {
            return ['nom' => 'Normal', 'couleur' => 'success', 'emoji' => '🟢'];
        } elseif ($imc < 30) {
            return ['nom' => 'Surpoids', 'couleur' => 'warning', 'emoji' => '🟠'];
        } else {
            return ['nom' => 'Obese', 'couleur' => 'danger', 'emoji' => '🔴'];
        }
    }

    // ============================================
    // ACHETER OPTION GOLD
    // ============================================
    public function buyGold()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if ($user['is_gold']) {
            return redirect()->back()->with('error', 'Vous avez deja l\'option Gold');
        }

        if ($user['solde_portefeuille'] < 9.99) {
            return redirect()->back()->with('error', 'Solde insuffisant (9.99€ requis)');
        }

        // Deduire et activer Gold
        $this->userModel->update($userId, [
            'solde_portefeuille' => $user['solde_portefeuille'] - 9.99,
            'is_gold' => true,
        ]);

        return redirect()->back()->with('success', 'Option Gold activee ! 15% de remise sur tous les regimes');
    }

    public function editProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $user = $this->userModel->find(session()->get('user_id'));
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Utilisateur non trouve');
        }

        return view('dashboard/edit_profile', ['user' => $user]);
    }

    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $rules = [
            'nom' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'genre' => 'required|in_list[M,F]',
            'taille' => 'required|numeric|greater_than[1.0]|less_than[3.0]',
            'poids' => 'required|numeric|greater_than[20]|less_than[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'email' => trim((string) $this->request->getPost('email')),
            'genre' => $this->request->getPost('genre'),
            'taille' => (float) $this->request->getPost('taille'),
            'poids' => (float) $this->request->getPost('poids'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->request->getPost('password')) {
            $data['password_hash'] = password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        Database::connect()->table('users')->where('id', session()->get('user_id'))->update($data);
        session()->set('nom', $data['nom']);
        session()->set('email', $data['email']);

        return redirect()->to('/dashboard')->with('success', 'Profil mis a jour avec succes');
    }

    // ============================================
    // VALIDER UN CODE PORTEFEUILLE (AJAX)
    // ============================================
    public function validateCodeAjax()
    {
        // Verifier si c'est une requete AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Requete invalide']);
        }

        // Verifier authentification
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Non authentifie']);
        }

        $code = trim($this->request->getPost('code'));
        $userId = session()->get('user_id');

        if (!$code) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Veuillez entrer un code',
            ]);
        }

        // Verifier que le code n'est pas vide
        if (strlen($code) < 3) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Code trop court',
            ]);
        }

        // Chercher le code
        $codeData = $this->codeModel->where('code', $code)->first();

        if (!$codeData) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Code invalide ou n\'existe pas',
            ]);
        }

        // Verifier l'etat du code
        if (!$codeData['valide']) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ce code a ete desactive ou est expire',
            ]);
        }

        if ($codeData['utilisateur_id'] !== null) {
            if ($codeData['utilisateur_id'] == $userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Vous avez deja utilise ce code',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Ce code a deja ete utilise par un autre utilisateur',
                ]);
            }
        }

        // Code valide - ajouter les fonds
        $montant = (float) $codeData['montant'];
        $user = $this->userModel->find($userId);
        $newBalance = $user['solde_portefeuille'] + $montant;

        // Mettre a jour le solde
        $this->userModel->update($userId, [
            'solde_portefeuille' => $newBalance
        ]);

        // Marquer le code comme utilise
        $this->codeModel->update($codeData['id'], [
            'utilisateur_id' => $userId,
            'date_utilisation' => date('Y-m-d H:i:s'),
            'valide' => false,
        ]);

        // Retourner succes
        return $this->response->setJSON([
            'success' => true,
            'message' => "+{$montant}€ ajoute a votre portefeuille !",
            'newBalance' => round($newBalance, 2),
            'montant' => $montant,
        ]);
    }
}

