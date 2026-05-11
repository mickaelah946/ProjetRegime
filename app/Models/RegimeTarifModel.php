<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeTarifModel extends Model
{
    protected $table = 'regime_tarifs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['regime_id', 'duree_jours', 'prix', 'reduction_pourcentage', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'regime_id' => 'required|integer',
        'duree_jours' => 'required|integer|in_list[7,14,30,90]',
        'prix' => 'required|decimal',
        'reduction_pourcentage' => 'integer|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Récupère les tarifs d'un régime
     */
    public function getByRegime($regimeId)
    {
        return $this->where('regime_id', $regimeId)
                   ->orderBy('duree_jours', 'ASC')
                   ->findAll();
    }

    /**
     * Obtient le prix d'un régime pour une durée donnée
     */
    public function getPrix($regimeId, $dureeJours)
    {
        $tarif = $this->where('regime_id', $regimeId)
                     ->where('duree_jours', $dureeJours)
                     ->first();
        
        return $tarif ? (float)$tarif['prix'] : null;
    }

    /**
     * Initialise les tarifs par défaut pour un nouveau régime
     */
    public function initializeTariffs($regimeId, $basePrice)
    {
        $durees = [7, 14, 30, 90];
        $reductions = [0, 5, 10, 15]; // % de réduction progressif

        foreach ($durees as $index => $duree) {
            $prix = $basePrice * ($duree / 7); // Calcul proportionnel
            $prixAvecReduction = $prix * (1 - ($reductions[$index] / 100));

            $this->insert([
                'regime_id' => $regimeId,
                'duree_jours' => $duree,
                'prix' => round($prixAvecReduction, 2),
                'reduction_pourcentage' => $reductions[$index],
            ]);
        }
    }
}
