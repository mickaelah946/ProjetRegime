<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu Régime</title>
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
            background: #27ae60;
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
            border-left: 4px solid #27ae60;
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
        .label {
            font-weight: bold;
            width: 35%;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        .receipt-number {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🥗 RegimeApp</h1>
        <p>Reçu d'Achat - Régime</p>
    </div>

    <div class="receipt-number">
        Numéro de reçu : <?= $receiptNumber ?>
    </div>

    <div class="section">
        <div class="section-title">Informations Acheteur</div>
        <table>
            <tr>
                <td class="label">Nom :</td>
                <td><?= htmlspecialchars($user['nom'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Email :</td>
                <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Détails du Régime</div>
        <table>
            <tr>
                <td class="label">Nom du Régime :</td>
                <td><?= htmlspecialchars($regime['nom'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Type :</td>
                <td><?= htmlspecialchars($regime['type'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Durée :</td>
                <td><?= $regime['duree_jours'] ?> jours</td>
            </tr>
            <tr>
                <td class="label">Description :</td>
                <td><?= htmlspecialchars($regime['description'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Prix :</td>
                <td style="font-weight: bold; color: #27ae60;"><?= number_format($regime['prix'] ?? 0, 2) ?>€</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Détails de la Transaction</div>
        <table>
            <tr>
                <td class="label">Date d'Achat :</td>
                <td><?= $purchaseDate ?></td>
            </tr>
            <tr>
                <td class="label">Statut :</td>
                <td>✓ Approuvé</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Merci d'avoir choisi RegimeApp !</p>
        <p>© RegimeApp 2026 - Tous droits réservés</p>
    </div>
</body>
</html>
