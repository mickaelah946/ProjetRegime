<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            background: #e74c3c;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background: #ecf0f1;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #e74c3c;
        }
        .stat-box {
            display: inline-block;
            background: #f0f0f0;
            padding: 15px 25px;
            margin: 10px 10px 10px 0;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
        }
        .stat-label {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 RegimeApp Admin</h1>
        <p>Rapport Administrateur</p>
    </div>

    <div class="section">
        <div class="section-title">Statistiques Globales</div>
        
        <div class="stat-box">
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Utilisateurs Totaux</div>
        </div>

        <div class="stat-box">
            <div class="stat-value"><?= $totalRegimes ?></div>
            <div class="stat-label">Regimes Disponibles</div>
        </div>

        <div class="stat-box">
            <div class="stat-value"><?= $goldUsers ?></div>
            <div class="stat-label">Utilisateurs Gold</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Details du Rapport</div>
        <table>
            <tr>
                <td style="font-weight: bold; width: 40%;">Nombre Total d'Utilisateurs :</td>
                <td><?= $totalUsers ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Nombre Total de Regimes :</td>
                <td><?= $totalRegimes ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Utilisateurs Gold :</td>
                <td><?= $goldUsers ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Pourcentage Gold :</td>
                <td><?= $totalUsers > 0 ? number_format(($goldUsers / $totalUsers) * 100, 2) : 0 ?>%</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Rapport genere le <?= $reportDate ?></p>
        <p>© RegimeApp 2026 - Tous droits reserves</p>
    </div>
</body>
</html>

