<?php

namespace App\Models;

use CodeIgniter\Model;

class ParametreModel extends Model
{
    protected $table = 'parametres';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['cle', 'valeur', 'description'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'cle' => 'required|min_length[2]|max_length[100]',
        'valeur' => 'required',
        'description' => 'permit_empty|max_length[255]',
    ];
}
