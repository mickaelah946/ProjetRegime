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
     * Generer une facture PDF pour un utilisateur
     */
    public function invoiceUser($userId)
    {
        // Verifie que l'utilisateur est connecte
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin' && (int) session()->get('user_id') !== (int) $userId) {
            return redirect()->to('/dashboard')->with('error', 'Acces non autorise');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setBody('Utilisateur non trouve');
        }

        // Preparation des donnees
        $data = [
            'user' => $user,
            'generatedAt' => date('d/m/Y H:i:s'),
        ];

        // Rendu HTML
        $html = view('pdf/invoice_user', $data);

        // Creation du PDF
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
        $mpdf->Output($filename, 'D'); // D = telechargement
    }

    /**
     * Generer un recu d'achat de regime
     */
    public function receiptRegime($regimeId)
    {
        // Verifie que l'utilisateur est connecte
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $regime = $this->regimeModel->find($regimeId);
        if (!$regime) {
            return $this->response->setStatusCode(404)->setBody('Regime non trouve');
        }

        $user = $this->userModel->find(session()->get('user_id'));

        // Preparation des donnees
        $data = [
            'user' => $user,
            'regime' => $regime,
            'purchaseDate' => date('d/m/Y H:i:s'),
            'receiptNumber' => 'REC-' . date('YmdHis'),
        ];

        // Rendu HTML
        $html = view('pdf/receipt_regime', $data);

        // Creation du PDF
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
     * Generer un recu admin (rapport generaliste)
     */
    public function reportAdmin()
    {
        // Verifie que c'est un admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Acces non autorise');
        }

        $totalUsers = $this->userModel->countAllResults();
        $totalRegimes = $this->regimeModel->countAllResults();
        $goldUsers = $this->userModel->where('is_gold', true)->countAllResults();

        // Preparation des donnees
        $data = [
            'totalUsers' => $totalUsers,
            'totalRegimes' => $totalRegimes,
            'goldUsers' => $goldUsers,
            'reportDate' => date('d/m/Y H:i:s'),
        ];

        // Rendu HTML
        $html = view('pdf/report_admin', $data);

        // Creation du PDF
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

