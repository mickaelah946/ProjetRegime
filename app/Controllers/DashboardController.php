<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ObjectifModel;
use App\Models\CodePortefeuilleModel;

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
        // Vérifier authentification
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Utilisateur non trouvé');
        }

        // Récupérer les objectifs de l'utilisateur
        $userObjectifs = $this->objectifModel->getUserObjectifs($userId);

        // Calculer l'IMC (poids / (taille * taille))
        $imc = $user['poids'] / ($user['taille'] * $user['taille']);

        // Déterminer la catégorie IMC
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

        // Insérer les nouveaux
        foreach ($selectedObjectifs as $objectifId) {
            $this->objectifModel->addUserObjectif($userId, (int) $objectifId);
        }

        return redirect()->to('/regime/browse')->with('success', 'Objectifs mis à jour ! Découvrez nos régimes recommandés');
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

        // Vérifier le code
        $codeData = $this->codeModel->where('code', $code)->first();

        if (!$codeData) {
            return redirect()->back()->with('error', 'Code invalide');
        }

        if ($codeData['valide'] && $codeData['utilisateur_id'] === null) {
            // Code valide et non utilisé
            $montant = (float)$codeData['montant'];
            $user = $this->userModel->find($userId);
            $newBalance = $user['solde_portefeuille'] + $montant;

            // Ajouter au solde
            $this->userModel->update($userId, [
                'solde_portefeuille' => $newBalance
            ]);

            // Marquer le code comme utilisé
            $this->codeModel->update($codeData['id'], [
                'utilisateur_id' => $userId,
                'date_utilisation' => date('Y-m-d H:i:s'),
                'valide' => false,
            ]);

            return redirect()->back()->with('success', "+{$montant}€ ajouté au portefeuille !");
        } elseif ($codeData['utilisateur_id'] === $userId) {
            return redirect()->back()->with('error', 'Ce code a déjà été utilisé par vous');
        } else {
            return redirect()->back()->with('error', 'Ce code a déjà été utilisé');
        }
    }

    // ============================================
    // HELPER: Déterminer catégorie IMC
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
            return redirect()->back()->with('error', 'Vous avez déjà l\'option Gold');
        }

        if ($user['solde_portefeuille'] < 9.99) {
            return redirect()->back()->with('error', 'Solde insuffisant (9.99€ requis)');
        }

        // Déduire et activer Gold
        $this->userModel->update($userId, [
            'solde_portefeuille' => $user['solde_portefeuille'] - 9.99,
            'is_gold' => true,
        ]);

        return redirect()->back()->with('success', 'Option Gold activée ! 15% de remise sur tous les régimes');
    }
}
