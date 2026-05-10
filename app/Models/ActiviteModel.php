<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class ActiviteModel extends Model
{
    protected $table          = 'activites_sportives';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = ['nom', 'description', 'type', 'intensite', 'duree_jours', 'calories_brulees', 'prix'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Récupérer les activités recommandées basées sur les objectifs
     */
    public function getRecommendedActivities($userId)
    {
        $db = Database::connect();
        
        // Récupérer les objectifs de l'utilisateur
        $userObjectifs = $db->table('user_objectifs')
                            ->select('objectif_id')
                            ->where('user_id', $userId)
                            ->get()
                            ->getResultArray();

        if (empty($userObjectifs)) {
            return $this->findAll();
        }

        $objectifIds = array_column($userObjectifs, 'objectif_id');

        // Logique :
        // - Objectif 1 (Augmenter poids) → musculation (construction musculaire)
        // - Objectif 2 (Réduire poids) → cardio haute intensité (brûler calories)
        // - Objectif 3 (Atteindre IMC idéal) → tous les types

        $types = [];
        $intensites = [];
        
        if (in_array(1, $objectifIds)) {
            $types[] = 'musculation'; // Augmenter poids
        }
        if (in_array(2, $objectifIds)) {
            $types[] = 'cardio'; // Réduire poids
            $intensites[] = 'haute'; // Haute intensité pour plus de brûlage
        }
        if (in_array(3, $objectifIds)) {
            // Atteindre IMC idéal - afficher toutes les activités
            return $this->findAll();
        }

        if (!empty($types)) {
            return $this->whereIn('type', $types)->findAll();
        } elseif (!empty($intensites)) {
            return $this->whereIn('intensite', $intensites)->findAll();
        }

        return $this->findAll();
    }

    /**
     * Récupérer les activités actives d'un utilisateur
     */
    public function getUserActivities($userId)
    {
        $db = Database::connect();
        $result = $db->table('activites_sportives')
                     ->select('activites_sportives.*, user_activites.id as user_activite_id, user_activites.prix_paye, user_activites.date_selection, user_activites.date_fin_prevu, user_activites.statut')
                     ->join('user_activites', 'user_activites.activite_id = activites_sportives.id')
                     ->where('user_activites.user_id', $userId)
                     ->where('user_activites.statut', 'actif')
                     ->get()
                     ->getResultArray();
        
        return $result ?: [];
    }

    /**
     * Vérifier si l'utilisateur a déjà cette activité active
     */
    public function hasUserSelectedActivity($userId, $activiteId)
    {
        $db = Database::connect();
        $result = $db->table('user_activites')
                     ->where('user_id', $userId)
                     ->where('activite_id', $activiteId)
                     ->where('statut', 'actif')
                     ->get()
                     ->getRowArray();
        
        return !empty($result);
    }
}

