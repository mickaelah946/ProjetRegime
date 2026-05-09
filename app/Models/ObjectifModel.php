<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table          = 'objectifs';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = ['nom', 'description'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';

    // Récupérer les objectifs d'un user
    public function getUserObjectifs($userId)
    {
        return $this->select('objectifs.*')
                    ->join('user_objectifs', 'user_objectifs.objectif_id = objectifs.id')
                    ->where('user_objectifs.user_id', $userId)
                    ->findAll();
    }

    // Effacer les objectifs d'un user
    public function deleteUserObjectifs($userId)
    {
        return $this->db->table('user_objectifs')
                        ->where('user_id', $userId)
                        ->delete();
    }

    // Insérer un user objectif
    public function addUserObjectif($userId, $objectifId)
    {
        return $this->db->table('user_objectifs')->insert([
            'user_id' => $userId,
            'objectif_id' => $objectifId,
        ]);
    }
}
