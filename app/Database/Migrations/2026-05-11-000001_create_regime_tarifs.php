<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimeTarifs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'regime_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'duree_jours' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Nombre de jours (7, 14, 30, 90)',
            ],
            'prix' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
                'default' => 0.00,
            ],
            'reduction_pourcentage' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'comment' => 'Réduction en pourcentage',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('regime_id', 'regimes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['regime_id', 'duree_jours']);
        $this->forge->createTable('regime_tarifs');
    }

    public function down()
    {
        $this->forge->dropTable('regime_tarifs');
    }
}
