<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegimeModel;
use Mpdf\Mpdf;

class PdfController extends BaseController
{
    protected $userModel;
    protected $regimeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->regimeModel = new RegimeModel();
    }

    /**
     * Générer une facture PDF pour un utilisateur
     */
    public function invoiceUser($userId)
    {
        // Vérifie que l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setBody('Utilisateur non trouvé');
        }

        // Préparation des données
        $data = [
            'user' => $user,
            'generatedAt' => date('d/m/Y H:i:s'),
        ];

        // Rendu HTML
        $html = view('pdf/invoice_user', $data);

        // Création du PDF
        $mpdf = new Mpdf([
            'tempDir' => WRITEPATH . 'temp',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        // Retour du PDF
        $filename = 'facture_' . $user['username'] . '_' . date('Y-m-d') . '.pdf';
        $mpdf->Output($filename, 'D'); // D = téléchargement
    }

    /**
     * Générer un reçu d'achat de régime
     */
    public function receiptRegime($regimeId)
    {
        // Vérifie que l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $regime = $this->regimeModel->find($regimeId);
        if (!$regime) {
            return $this->response->setStatusCode(404)->setBody('Régime non trouvé');
        }

        $user = $this->userModel->find(session()->get('userId'));

        // Préparation des données
        $data = [
            'user' => $user,
            'regime' => $regime,
            'purchaseDate' => date('d/m/Y H:i:s'),
            'receiptNumber' => 'REC-' . date('YmdHis'),
        ];

        // Rendu HTML
        $html = view('pdf/receipt_regime', $data);

        // Création du PDF
        $mpdf = new Mpdf([
            'tempDir' => WRITEPATH . 'temp',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        // Retour du PDF
        $filename = 'recu_' . $regime['id'] . '_' . date('Y-m-d') . '.pdf';
        $mpdf->Output($filename, 'D');
    }

    /**
     * Générer un reçu admin (rapport généraliste)
     */
    public function reportAdmin()
    {
        // Vérifie que c'est un admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $totalUsers = $this->userModel->countAllResults();
        $totalRegimes = $this->regimeModel->countAllResults();
        $goldUsers = $this->userModel->where('is_gold', true)->countAllResults();

        // Préparation des données
        $data = [
            'totalUsers' => $totalUsers,
            'totalRegimes' => $totalRegimes,
            'goldUsers' => $goldUsers,
            'reportDate' => date('d/m/Y H:i:s'),
        ];

        // Rendu HTML
        $html = view('pdf/report_admin', $data);

        // Création du PDF
        $mpdf = new Mpdf([
            'tempDir' => WRITEPATH . 'temp',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        // Retour du PDF
        $filename = 'rapport_admin_' . date('Y-m-d') . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
