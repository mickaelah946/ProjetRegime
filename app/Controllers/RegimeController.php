<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegimeModel;
use App\Models\ActiviteModel;
use App\Models\ObjectifModel;
use App\Models\RegimeTarifModel;
use Config\Database;

class RegimeController extends BaseController
{
    protected $userModel;
    protected $regimeModel;
    protected $activiteModel;
    protected $objectifModel;
    protected $tarifModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteModel();
        $this->objectifModel = new ObjectifModel();
        $this->tarifModel = new RegimeTarifModel();
    }

    /**
     * Afficher les régimes recommandés
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
                return redirect()->to('/login')->with('error', 'Utilisateur non trouvé');
            }

            // Récupérer les régimes recommandés
            $regimes = $this->regimeModel->getRecommendedRegimes($userId);
            $userRegimes = $this->regimeModel->getUserRegimes($userId);
            $userObjectifs = $this->objectifModel->getUserObjectifs($userId);

            // Calculer prix avec remise Gold et ajouter les tarifs
            foreach ($regimes as &$regime) {
                $regime['prix'] = (float)$regime['prix'];
                $regime['prix_original'] = $regime['prix'];
                if ($user['is_gold']) {
                    $regime['prix'] = round($regime['prix'] * 0.85, 2); // 15% de remise
                }
                $regime['already_selected'] = $this->regimeModel->hasUserSelectedRegime($userId, $regime['id']);
                
                // Récupérer les tarifs pour ce régime
                $tariffs = $this->tarifModel->getByRegime($regime['id']);
                $regime['tariffs'] = [];
                
                foreach ($tariffs as $tariff) {
                    $tariffPrice = (float)$tariff['prix'];
                    if ($user['is_gold']) {
                        $tariffPrice = round($tariffPrice * 0.85, 2); // 15% de remise
                    }
                    $regime['tariffs'][$tariff['duree_jours']] = [
                        'prix' => $tariffPrice,
                        'prix_original' => (float)$tariff['prix'],
                        'reduction' => $tariff['reduction_pourcentage'],
                    ];
                }
            }

            $data = [
                'user' => $user,
                'regimes' => $regimes,
                'userRegimes' => $userRegimes ?? [],
                'userObjectifs' => $userObjectifs,
            ];

            return view('regimes/browse', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Sélectionner un régime
     */
    public function select($regimeId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        try {
            $userId = session()->get('user_id');
            $user = $this->userModel->find($userId);
            $regime = $this->regimeModel->find($regimeId);

            if (!$regime) {
                return redirect()->back()->with('error', 'Régime non trouvé');
            }

            // Vérifier si déjà sélectionné
            if ($this->regimeModel->hasUserSelectedRegime($userId, $regimeId)) {
                return redirect()->back()->with('error', 'Vous avez déjà ce régime actif');
            }

            $duree_jours = (int) ($this->request->getPost('duree_jours') ?? $regime['duree_jours']);
            $tarif = $this->tarifModel->where('regime_id', $regimeId)
                                      ->where('duree_jours', $duree_jours)
                                      ->first();

            // Calculer le prix avec remise Gold
            $prix_paye = $tarif ? (float) $tarif['prix'] : (float)$regime['prix'];
            if ($user['is_gold']) {
                $prix_paye = round($prix_paye * 0.85, 2); // 15% de remise
            }

            // Vérifier le solde
            if ($user['solde_portefeuille'] < $prix_paye) {
                return redirect()->back()->with('error', "Solde insuffisant. Vous avez {$user['solde_portefeuille']}€, il en faut {$prix_paye}€");
            }

            // Insérer le régime pour l'utilisateur
            $db = Database::connect();
            $db->table('user_regimes')->insert([
                'user_id' => $userId,
                'regime_id' => $regimeId,
                'prix_paye' => $prix_paye,
                'date_selection' => date('Y-m-d H:i:s'),
                'date_fin_prevu' => date('Y-m-d H:i:s', strtotime("+{$duree_jours} days")),
                'statut' => 'actif',
            ]);

            // Déduire du solde
            $newBalance = $user['solde_portefeuille'] - $prix_paye;
            $this->userModel->update($userId, [
                'solde_portefeuille' => $newBalance,
            ]);

            return redirect()->to('/regime/browse')->with('success', "Régime '{$regime['nom']}' sélectionné ! -{$prix_paye}€");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Annuler un régime
     */
    public function cancel($userRegimeId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez d\'abord vous connecter');
        }

        $userId = session()->get('user_id');
        $db = Database::connect();
        $userRegime = $db->table('user_regimes')
                        ->where('id', $userRegimeId)
                        ->where('user_id', $userId)
                        ->get()
                        ->getRowArray();

        if (!$userRegime) {
            return redirect()->back()->with('error', 'Régime non trouvé');
        }

        // Mettre à jour le statut
        $db->table('user_regimes')->where('id', $userRegimeId)->update([
            'statut' => 'annule'
        ]);

        // Rembourser 50% du prix payé
        $remboursement = $userRegime['prix_paye'] * 0.5;
        $user = $this->userModel->find($userId);
        $newBalance = $user['solde_portefeuille'] + $remboursement;
        $this->userModel->update($userId, [
            'solde_portefeuille' => $newBalance,
        ]);

        return redirect()->to('/regime/browse')->with('success', "Régime annulé. Remboursement de {$remboursement}€");
    }

    /**
     * Afficher les régimes actifs de l'utilisateur
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
                return redirect()->to('/login')->with('error', 'Utilisateur non trouvé');
            }

            $userRegimes = $this->regimeModel->getUserRegimes($userId);

            $data = [
                'user' => $user,
                'userRegimes' => $userRegimes ?? [],
            ];

            return view('regimes/active', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
