<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ActiviteModel;
use App\Models\ObjectifModel;
use Config\Database;

class ActivityController extends BaseController
{
    protected $userModel;
    protected $activiteModel;
    protected $objectifModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->activiteModel = new ActiviteModel();
        $this->objectifModel = new ObjectifModel();
    }

    /**
     * Afficher les activites recommandees
     */
    public function browse()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        try {
            $userId = session()->get('user_id');
            $user = $this->userModel->find($userId);

            if (!$user) {
                return redirect()->to('/login')->with('error', 'Utilisateur non trouve');
            }

            // Recuperer les activites recommandees
            $activites = $this->activiteModel->getRecommendedActivities($userId);
            $userActivites = $this->activiteModel->getUserActivities($userId);
            $userObjectifs = $this->objectifModel->getUserObjectifs($userId);

            // Calculer prix avec remise Gold si applicable
            foreach ($activites as &$activite) {
                $activite['prix'] = (float)$activite['prix'];
                $activite['prix_original'] = $activite['prix'];
                if ($user['is_gold']) {
                    $activite['prix'] = round($activite['prix'] * 0.85, 2); // 15% de remise
                }
                $activite['already_selected'] = $this->activiteModel->hasUserSelectedActivity($userId, $activite['id']);
            }

            $data = [
                'user' => $user,
                'activites' => $activites,
                'userActivites' => $userActivites ?? [],
                'userObjectifs' => $userObjectifs,
            ];

            return view('activites/browse', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Selectionner une activite
     */
    public function select($activiteId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        try {
            $userId = session()->get('user_id');
            $user = $this->userModel->find($userId);
            $activite = $this->activiteModel->find($activiteId);

            if (!$activite) {
                return redirect()->back()->with('error', 'Activite non trouvee');
            }

            // Verifier si deja selectionnee
            if ($this->activiteModel->hasUserSelectedActivity($userId, $activiteId)) {
                return redirect()->back()->with('error', 'Vous avez deja cette activite active');
            }

            // Calculer le prix avec remise Gold
            $prix_paye = (float)$activite['prix'];
            if ($user['is_gold']) {
                $prix_paye = round($prix_paye * 0.85, 2); // 15% de remise
            }

            // Verifier le solde
            if ($user['solde_portefeuille'] < $prix_paye) {
                return redirect()->back()->with('error', "Solde insuffisant. Vous avez {$user['solde_portefeuille']}€, il en faut {$prix_paye}€");
            }

            // Inserer l'activite pour l'utilisateur
            $db = Database::connect();
            $duree_jours = (int)$activite['duree_jours'];
            $db->table('user_activites')->insert([
                'user_id' => $userId,
                'activite_id' => $activiteId,
                'prix_paye' => $prix_paye,
                'date_selection' => date('Y-m-d H:i:s'),
                'date_fin_prevu' => date('Y-m-d H:i:s', strtotime("+{$duree_jours} days")),
                'statut' => 'actif',
            ]);

            // Deduire du solde
            $newBalance = $user['solde_portefeuille'] - $prix_paye;
            $this->userModel->update($userId, [
                'solde_portefeuille' => $newBalance,
            ]);

            return redirect()->to('/activity/browse')->with('success', "Activite '{$activite['nom']}' selectionnee ! -{$prix_paye}€");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une activite
     */
    public function cancel($userActivityId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        try {
            $userId = session()->get('user_id');
            $db = Database::connect();
            $userActivity = $db->table('user_activites')
                            ->where('id', $userActivityId)
                            ->where('user_id', $userId)
                            ->get()
                            ->getRowArray();

            if (!$userActivity) {
                return redirect()->back()->with('error', 'Activite non trouvee');
            }

            // Mettre a jour le statut
            $db->table('user_activites')->where('id', $userActivityId)->update([
                'statut' => 'annule'
            ]);

            // Rembourser 50% du prix paye
            $remboursement = $userActivity['prix_paye'] * 0.5;
            $user = $this->userModel->find($userId);
            $newBalance = $user['solde_portefeuille'] + $remboursement;
            $this->userModel->update($userId, [
                'solde_portefeuille' => $newBalance,
            ]);

            return redirect()->to('/activity/browse')->with('success', "Activite annulee. Remboursement de {$remboursement}€");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les activites actives de l'utilisateur
     */
    public function active()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        try {
            $userId = session()->get('user_id');
            $user = $this->userModel->find($userId);

            if (!$user) {
                return redirect()->to('/login')->with('error', 'Utilisateur non trouve');
            }

            $userActivites = $this->activiteModel->getUserActivities($userId);

            $data = [
                'user' => $user,
                'userActivites' => $userActivites ?? [],
            ];

            return view('activites/active', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}

