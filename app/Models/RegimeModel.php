<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class RegimeModel extends Model
{
    protected $table          = 'regimes';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = ['nom', 'description', 'type', 'duree_jours', 'prix', 'poids_variation_min', 'poids_variation_max', 'pourcentage_viande', 'pourcentage_poisson', 'pourcentage_volaille'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Recuperer les regimes recommandes bases sur les objectifs de l'utilisateur
     */
    public function getRecommendedRegimes($userId)
    {
        $db = Database::connect();
        
        // Recuperer les objectifs de l'utilisateur
        $userObjectifs = $db->table('user_objectifs')
                            ->select('objectif_id')
                            ->where('user_id', $userId)
                            ->get()
                            ->getResultArray();

        if (empty($userObjectifs)) {
            // Si pas d'objectifs, retourner tous les regimes
            return $this->findAll();
        }

        $objectifIds = array_column($userObjectifs, 'objectif_id');

        // Logique : 
        // - Objectif 1 (Augmenter poids) → regimes de type 'prise'
        // - Objectif 2 (Reduire poids) → regimes de type 'perte'
        // - Objectif 3 (Atteindre IMC ideal) → tous les types selon besoin

        $types = [];
        
        if (in_array(1, $objectifIds)) {
            $types[] = 'prise'; // Augmenter poids
        }
        if (in_array(2, $objectifIds)) {
            $types[] = 'perte'; // Reduire poids
        }
        if (in_array(3, $objectifIds)) {
            // Atteindre IMC ideal - afficher tous les types
            $types = ['perte', 'prise', 'maintien'];
        }

        if (empty($types)) {
            return $this->findAll();
        }

        return $this->whereIn('type', $types)->findAll();
    }

    /**
     * Recuperer les regimes actifs d'un utilisateur
     */
    public function getUserRegimes($userId)
    {
        $db = Database::connect();
        $result = $db->table('regimes')
                     ->select('regimes.*, user_regimes.id as user_regime_id, user_regimes.prix_paye, user_regimes.date_selection, user_regimes.date_fin_prevu, user_regimes.statut')
                     ->join('user_regimes', 'user_regimes.regime_id = regimes.id')
                     ->where('user_regimes.user_id', $userId)
                     ->where('user_regimes.statut', 'actif')
                     ->get()
                     ->getResultArray();
        
        return $result ?: [];
    }

    /**
     * Verifier si l'utilisateur a deja selectionne ce regime
     */
    public function hasUserSelectedRegime($userId, $regimeId)
    {
        $db = Database::connect();
        return $db->table('user_regimes')
                  ->where('user_id', $userId)
                  ->where('regime_id', $regimeId)
                  ->where('statut', 'actif')
                  ->countAllResults() > 0;
    }
}

