<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegimeModel;
use App\Models\ActiviteModel;
use App\Models\CodePortefeuilleModel;
use App\Models\ObjectifModel;
use App\Models\RegimeTarifModel;
use App\Models\ParametreModel;
use Config\Database;

class AdminController extends BaseController
{
    protected $userModel;
    protected $regimeModel;
    protected $activiteModel;
    protected $codeModel;
    protected $objectifModel;
    protected $tarifModel;
    protected $parametreModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteModel();
        $this->codeModel = new CodePortefeuilleModel();
        $this->objectifModel = new ObjectifModel();
        $this->tarifModel = new RegimeTarifModel();
        $this->parametreModel = new ParametreModel();
    }

    private function requireAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        return null;
    }

    /**
     * Dashboard admin avec statistiques
     */
    public function dashboard()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
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

        // GRAPHES CHART.JS
        
        // 1. Pie Chart: Gold vs Normal users
        $goldUsers = $stats['utilisateurs_gold'];
        $normalUsers = $stats['total_users'] - $goldUsers;
        $chartGoldVsNormal = [
            'labels' => ['Gold', 'Normal'],
            'data' => [$goldUsers, $normalUsers],
            'colors' => ['#f39c12', '#95a5a6'],
        ];

        // 2. Bar Chart: Régimes by count
        $regimesChartData = [];
        $regimesChartLabels = [];
        foreach ($regimes_populaires as $regime) {
            $regimesChartLabels[] = $regime['nom'];
            $regimesChartData[] = $regime['count'];
        }
        if (empty($regimesChartLabels)) {
            $regimesChartLabels = ['Aucun'];
            $regimesChartData = [0];
        }
        $chartRegimes = [
            'labels' => $regimesChartLabels,
            'data' => $regimesChartData,
        ];

        // 3. Line Chart: Revenue trend (last 6 months)
        $revenuesTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $revenue = $db->table('user_regimes')
                         ->selectSum('prix_paye')
                         ->where('DATE_FORMAT(created_at, "%Y-%m") = ', $date)
                         ->get()
                         ->getRowArray()['prix_paye'] ?? 0;
            $revenuesTrend[] = [
                'date' => date('M Y', strtotime($date . '-01')),
                'revenue' => (float)$revenue,
            ];
        }
        $chartRevenues = [
            'labels' => array_column($revenuesTrend, 'date'),
            'data' => array_column($revenuesTrend, 'revenue'),
        ];

        $data = [
            'stats' => $stats,
            'derniers_users' => $derniers_users,
            'regimes_populaires' => $regimes_populaires,
            'chartGoldVsNormal' => $chartGoldVsNormal,
            'chartRegimes' => $chartRegimes,
            'chartRevenues' => $chartRevenues,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Liste des utilisateurs
     */
    public function users()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $users = $this->userModel->findAll();

        return view('admin/users', ['users' => $users]);
    }

    public function deleteUser($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Liste des régimes
     */
    public function regimes()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);
        $editingRegime = $editId ? $this->regimeModel->find($editId) : null;
        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->findAll();
        
        // Ajoute les tarifs pour chaque régime
        $regimesWithTariffs = [];
        foreach ($regimes as $regime) {
            $regime['tariffs'] = $this->tarifModel->getByRegime($regime['id']);
            $regimesWithTariffs[] = $regime;
        }

        return view('admin/regimes', [
            'regimes' => $regimesWithTariffs,
            'editingRegime' => $editingRegime,
        ]);
    }

    public function saveRegime()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $id = (int) ($this->request->getPost('id') ?? 0);

        $rules = [
            'nom' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty',
            'type' => 'required|in_list[perte,prise,maintien]',
            'duree_jours' => 'required|is_natural_no_zero',
            'prix' => 'required|decimal',
            'poids_variation_min' => 'required|decimal',
            'poids_variation_max' => 'required|decimal',
            'pourcentage_viande' => 'required|is_natural',
            'pourcentage_poisson' => 'required|is_natural',
            'pourcentage_volaille' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez corriger les champs du régime');
        }

        $data = [
            'nom' => trim($this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'type' => $this->request->getPost('type'),
            'duree_jours' => (int) $this->request->getPost('duree_jours'),
            'prix' => (float) $this->request->getPost('prix'),
            'poids_variation_min' => (float) $this->request->getPost('poids_variation_min'),
            'poids_variation_max' => (float) $this->request->getPost('poids_variation_max'),
            'pourcentage_viande' => (int) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (int) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (int) $this->request->getPost('pourcentage_volaille'),
        ];

        if ($id) {
            $this->regimeModel->update($id, $data);
            return redirect()->to('/admin/regimes')->with('success', 'Régime modifié avec succès');
        }

        $this->regimeModel->insert($data);
        $this->tarifModel->initializeTariffs($this->regimeModel->getInsertID(), $data['prix']);

        return redirect()->to('/admin/regimes')->with('success', 'Régime ajouté avec succès');
    }

    public function deleteRegime($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $this->regimeModel->delete($id);

        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé avec succès');
    }

    /**
     * Liste des activités
     */
    public function activites()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);
        $editingActivite = $editId ? $this->activiteModel->find($editId) : null;
        $activites = $this->activiteModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/activites', [
            'activites' => $activites,
            'editingActivite' => $editingActivite,
        ]);
    }

    public function saveActivite()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $id = (int) ($this->request->getPost('id') ?? 0);

        $rules = [
            'nom' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty',
            'type' => 'required|in_list[cardio,musculation,yoga,autre]',
            'intensite' => 'required|in_list[basse,moyenne,haute]',
            'duree_jours' => 'required|is_natural_no_zero',
            'calories_brulees' => 'required|is_natural',
            'prix' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez corriger les champs de l’activité');
        }

        $data = [
            'nom' => trim($this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'type' => $this->request->getPost('type'),
            'intensite' => $this->request->getPost('intensite'),
            'duree_jours' => (int) $this->request->getPost('duree_jours'),
            'calories_brulees' => (int) $this->request->getPost('calories_brulees'),
            'prix' => (float) $this->request->getPost('prix'),
        ];

        if ($id) {
            $this->activiteModel->update($id, $data);
            return redirect()->to('/admin/activites')->with('success', 'Activité modifiée avec succès');
        }

        $this->activiteModel->insert($data);

        return redirect()->to('/admin/activites')->with('success', 'Activité ajoutée avec succès');
    }

    public function deleteActivite($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $this->activiteModel->delete($id);

        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée avec succès');
    }

    /**
     * Gestion des codes
     */
    public function codes()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);
        $editingCode = $editId ? $this->codeModel->find($editId) : null;

        $db = Database::connect();
        $codes = $db->table('codes_portefeuille cp')
                   ->select('cp.*, users.username as utilisateur_username')
                   ->join('users', 'users.id = cp.utilisateur_id', 'left')
                   ->orderBy('cp.created_at', 'DESC')
                   ->get()
                   ->getResultArray();

        return view('admin/codes', [
            'codes' => $codes,
            'editingCode' => $editingCode,
        ]);
    }

    public function saveCode()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $id = (int) ($this->request->getPost('id') ?? 0);

        $rules = [
            'code' => 'required|min_length[4]|max_length[50]',
            'montant' => 'required|decimal',
            'valide' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez corriger les champs du code');
        }

        $data = [
            'code' => trim($this->request->getPost('code')),
            'montant' => (float) $this->request->getPost('montant'),
            'valide' => $this->request->getPost('valide') ? true : false,
        ];

        if ($id) {
            $this->codeModel->update($id, $data);
            return redirect()->to('/admin/codes')->with('success', 'Code modifié avec succès');
        }

        $data['utilisateur_id'] = null;
        $data['date_utilisation'] = null;

        $this->codeModel->insert($data);

        return redirect()->to('/admin/codes')->with('success', 'Code ajouté avec succès');
    }

    public function deleteCode($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $this->codeModel->delete($id);

        return redirect()->to('/admin/codes')->with('success', 'Code supprimé avec succès');
    }

    public function toggleCode($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $code = $this->codeModel->find($id);
        if (!$code) {
            return redirect()->to('/admin/codes')->with('error', 'Code introuvable');
        }

        if (!empty($code['utilisateur_id'])) {
            return redirect()->to('/admin/codes')->with('error', 'Impossible de réactiver un code déjà utilisé');
        }

        $this->codeModel->update($id, ['valide' => !$code['valide']]);

        return redirect()->to('/admin/codes')->with('success', 'Statut du code mis à jour');
    }

    public function parametres()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);
        $editingParametre = $editId ? $this->parametreModel->find($editId) : null;

        return view('admin/parametres', [
            'parametres' => $this->parametreModel->orderBy('cle', 'ASC')->findAll(),
            'editingParametre' => $editingParametre,
        ]);
    }

    public function saveParametre()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $id = (int) ($this->request->getPost('id') ?? 0);
        $data = [
            'cle' => trim((string) $this->request->getPost('cle')),
            'valeur' => trim((string) $this->request->getPost('valeur')),
            'description' => trim((string) $this->request->getPost('description')),
        ];

        if ($id) {
            $this->parametreModel->update($id, $data);
            return redirect()->to('/admin/parametres')->with('success', 'Paramètre modifié avec succès');
        }

        $this->parametreModel->insert($data);
        return redirect()->to('/admin/parametres')->with('success', 'Paramètre ajouté avec succès');
    }

    public function deleteParametre($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $this->parametreModel->delete($id);

        return redirect()->to('/admin/parametres')->with('success', 'Paramètre supprimé avec succès');
    }

    /**
     * API : Récupère les tarifs d'un régime (AJAX)
     */
    public function getTariffs($regimeId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Accès non autorisé']);
        }

        $tariffs = $this->tarifModel->getByRegime($regimeId);
        return $this->response->setJSON($tariffs);
    }

    /**
     * Sauvegarde/mise à jour d'un tarif
     */
    public function saveTariff()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $regimeId = (int) $this->request->getPost('regime_id');
        $dureeJours = (int) $this->request->getPost('duree_jours');
        $prix = (float) $this->request->getPost('prix');
        $reductionPourcentage = (int) ($this->request->getPost('reduction_pourcentage') ?? 0);

        $rules = [
            'regime_id' => 'required|integer',
            'duree_jours' => 'required|integer|in_list[7,14,30,90]',
            'prix' => 'required|decimal',
            'reduction_pourcentage' => 'integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez vérifier les champs du tarif');
        }

        // Cherche si le tarif existe
        $existing = $this->tarifModel->where('regime_id', $regimeId)
                                    ->where('duree_jours', $dureeJours)
                                    ->first();

        $data = [
            'regime_id' => $regimeId,
            'duree_jours' => $dureeJours,
            'prix' => $prix,
            'reduction_pourcentage' => $reductionPourcentage,
        ];

        if ($existing) {
            $this->tarifModel->update($existing['id'], $data);
            return redirect()->back()->with('success', 'Tarif modifié avec succès');
        }

        $this->tarifModel->insert($data);
        return redirect()->back()->with('success', 'Tarif ajouté avec succès');
    }

    /**
     * Supprime un tarif
     */
    public function deleteTariff($tarifId)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $this->tarifModel->delete($tarifId);
        return redirect()->back()->with('success', 'Tarif supprimé avec succès');
    }

    /**
     * Tableau croisé : Utilisateurs vs Régimes vs Activités
     */
    public function crossTabUsers()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $db = Database::connect();

        // Récupérer tous les utilisateurs (sauf admin)
        $users = $this->userModel->where('role', 'user')
                                 ->orderBy('nom', 'ASC')
                                 ->findAll();

        // Récupérer tous les régimes
        $regimes = $this->regimeModel->orderBy('nom', 'ASC')->findAll();

        // Récupérer tous les activités
        $activites = $this->activiteModel->orderBy('nom', 'ASC')->findAll();

        // Construire un tableau croisé : user_id => regime_id => statut
        $crossTabRegimes = [];
        $crossTabActivites = [];

        foreach ($users as $user) {
            $userId = $user['id'];
            
            // Régimes de cet utilisateur
            $userRegimes = $db->table('user_regimes')
                             ->where('user_id', $userId)
                             ->get()
                             ->getResultArray();
            
            $crossTabRegimes[$userId] = [];
            foreach ($regimes as $regime) {
                $found = null;
                foreach ($userRegimes as $ur) {
                    if ($ur['regime_id'] == $regime['id']) {
                        $found = $ur['statut'];
                        break;
                    }
                }
                $crossTabRegimes[$userId][$regime['id']] = $found;
            }

            // Activités de cet utilisateur
            $userActivites = $db->table('user_activites')
                               ->where('user_id', $userId)
                               ->get()
                               ->getResultArray();

            $crossTabActivites[$userId] = [];
            foreach ($activites as $activite) {
                $found = null;
                foreach ($userActivites as $ua) {
                    if ($ua['activite_id'] == $activite['id']) {
                        $found = $ua['statut'];
                        break;
                    }
                }
                $crossTabActivites[$userId][$activite['id']] = $found;
            }
        }

        $data = [
            'users' => $users,
            'regimes' => $regimes,
            'activites' => $activites,
            'crossTabRegimes' => $crossTabRegimes,
            'crossTabActivites' => $crossTabActivites,
        ];

        return view('admin/cross_tab_users', $data);
    }
}
