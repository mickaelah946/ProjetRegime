<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegimeModel;
use App\Models\ActiviteModel;
use App\Models\ObjectifModel;
use Config\Database;

class AdminController extends BaseController
{
    protected $userModel;
    protected $regimeModel;
    protected $activiteModel;
    protected $objectifModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteModel();
        $this->objectifModel = new ObjectifModel();
    }

    /**
     * Dashboard admin avec statistiques
     */
    public function dashboard()
    {
        // Vérifier que c'est un admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $db = Database::connect();

        // Statistiques générales
        $stats = [
            'total_users' => $this->userModel->countAllResults(),
            'total_regimes' => $this->regimeModel->countAllResults(),
            'total_activites' => $this->activiteModel->countAllResults(),
            'total_codes' => $db->table('codes_portefeuille')->countAllResults(),
            'codes_utilises' => $db->table('codes_portefeuille')->where('valide', false)->countAllResults(),
            'revenus_total' => $db->table('user_regimes')
                                 ->selectSum('prix_paye')
                                 ->get()
                                 ->getRowArray()['prix_paye'] ?? 0,
            'utilisateurs_gold' => $this->userModel->where('is_gold', true)->countAllResults(),
        ];

        // Derniers utilisateurs
        $derniers_users = $this->userModel->orderBy('created_at', 'DESC')->limit(5)->findAll();

        // Régimes populaires
        $regimes_populaires = $db->table('user_regimes')
                                 ->select('regimes.nom, COUNT(user_regimes.id) as count')
                                 ->join('regimes', 'regimes.id = user_regimes.regime_id')
                                 ->where('user_regimes.statut', 'actif')
                                 ->groupBy('regime_id')
                                 ->orderBy('count', 'DESC')
                                 ->limit(5)
                                 ->get()
                                 ->getResultArray();

        $data = [
            'stats' => $stats,
            'derniers_users' => $derniers_users,
            'regimes_populaires' => $regimes_populaires,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Liste des utilisateurs
     */
    public function users()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $users = $this->userModel->findAll();

        return view('admin/users', ['users' => $users]);
    }

    /**
     * Liste des régimes
     */
    public function regimes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $regimes = $this->regimeModel->findAll();

        return view('admin/regimes', ['regimes' => $regimes]);
    }

    /**
     * Liste des activités
     */
    public function activites()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $activites = $this->activiteModel->findAll();

        return view('admin/activites', ['activites' => $activites]);
    }

    /**
     * Gestion des codes
     */
    public function codes()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $db = Database::connect();
        $codes = $db->table('codes_portefeuille')
                   ->orderBy('created_at', 'DESC')
                   ->get()
                   ->getResultArray();

        return view('admin/codes', ['codes' => $codes]);
    }
}
