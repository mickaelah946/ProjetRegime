<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HashExistingPasswords extends Migration
{
    public function up()
    {
        // Récupérer tous les utilisateurs avec des mots de passe en texte clair
        $db = \Config\Database::connect();
        $users = $db->table('users')->get()->getResultArray();

        foreach ($users as $user) {
            // Si le mot de passe ne commence pas par $ (hash bcrypt), le hacher
            if (!empty($user['password_hash']) && strpos($user['password_hash'], '$') !== 0) {
                $hashed = password_hash($user['password_hash'], PASSWORD_BCRYPT);
                $db->table('users')
                    ->where('id', $user['id'])
                    ->update(['password_hash' => $hashed]);
            }
        }
    }

    public function down()
    {
        // Impossible de reverser (les mots de passe originaux sont perdus)
        // Cette migration n'est qu'une direction
    }
}
