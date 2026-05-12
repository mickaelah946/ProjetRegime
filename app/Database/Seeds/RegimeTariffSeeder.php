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

        // Recupere tous les regimes
        $regimes = $regimeModel->findAll();

        foreach ($regimes as $regime) {
            // Prix de base (utilise le prix du regime)
            $basePrice = (float) $regime['prix'];

            // Calcule les tarifs pour differentes durees
            $tariffs = [
                ['duree' => 7, 'reduction' => 0],    // 1 semaine - pas de reduction
                ['duree' => 14, 'reduction' => 5],   // 2 semaines - 5% reduction
                ['duree' => 30, 'reduction' => 10],  // 1 mois - 10% reduction
                ['duree' => 90, 'reduction' => 15],  // 3 mois - 15% reduction
            ];

            foreach ($tariffs as $tariff) {
                // Verifie si le tarif existe deja
                $existing = $tarifModel->where('regime_id', $regime['id'])
                                       ->where('duree_jours', $tariff['duree'])
                                       ->first();

                if (!$existing) {
                    // Calcule le prix proportionnel a la duree
                    $prixProportionnel = $basePrice * ($tariff['duree'] / 7);
                    
                    // Applique la reduction
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

        echo "Tarifs initialises avec succes !";
    }
}

