<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePortefeuilleModel extends Model
{
    protected $table          = 'codes_portefeuille';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'code',
        'montant',
        'utilisateur_id',
        'date_utilisation',
        'valide',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Recuperer un code par son code
    public function findByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    // Recuperer les codes utilises par un user
    public function getUserCodes($userId)
    {
        return $this->where('utilisateur_id', $userId)->findAll();
    }

    // Recuperer les codes disponibles (non utilises et valides)
    public function getAvailableCodes()
    {
        return $this->where('utilisateur_id', NULL)
                    ->where('valide', true)
                    ->findAll();
    }
}

