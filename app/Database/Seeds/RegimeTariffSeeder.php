<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\RegimeModel;
use App\Models\RegimeTarifModel;

class RegimeTariffSeeder extends Seeder
{
    public function run()
    {
        $regimeModel = new RegimeModel();
        $tarifModel = new RegimeTarifModel();

        // Récupère tous les régimes
        $regimes = $regimeModel->findAll();

        foreach ($regimes as $regime) {
            // Prix de base (utilise le prix du régime)
            $basePrice = (float) $regime['prix'];

            // Calcule les tarifs pour différentes durées
            $tariffs = [
                ['duree' => 7, 'reduction' => 0],    // 1 semaine - pas de réduction
                ['duree' => 14, 'reduction' => 5],   // 2 semaines - 5% réduction
                ['duree' => 30, 'reduction' => 10],  // 1 mois - 10% réduction
                ['duree' => 90, 'reduction' => 15],  // 3 mois - 15% réduction
            ];

            foreach ($tariffs as $tariff) {
                // Vérifie si le tarif existe déjà
                $existing = $tarifModel->where('regime_id', $regime['id'])
                                       ->where('duree_jours', $tariff['duree'])
                                       ->first();

                if (!$existing) {
                    // Calcule le prix proportionnel à la durée
                    $prixProportionnel = $basePrice * ($tariff['duree'] / 7);
                    
                    // Applique la réduction
                    $prixFinal = $prixProportionnel * (1 - ($tariff['reduction'] / 100));

                    $tarifModel->insert([
                        'regime_id' => $regime['id'],
                        'duree_jours' => $tariff['duree'],
                        'prix' => round($prixFinal, 2),
                        'reduction_pourcentage' => $tariff['reduction'],
                    ]);
                }
            }
        }

        echo "Tarifs initialisés avec succès !";
    }
}
