<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table          = 'users';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'nom',
        'email',
        'username',
        'password_hash',
        'genre',
        'taille',
        'poids',
        'solde_portefeuille',
        'is_gold',
        'role',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nom'           => 'required|min_length[3]|max_length[100]',
        'email'         => 'required|valid_email|is_unique[users.email]',
        'username'      => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
        'password_hash' => 'required|min_length[6]',
        'genre'         => 'required|in_list[M,F]',
        'taille'        => 'required|numeric',
        'poids'         => 'required|numeric',
        'role'          => 'required|in_list[admin,user]',
    ];

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function loginUser(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }
}
