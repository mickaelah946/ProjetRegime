<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture Utilisateur</title>
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
            background: #2c3e50;
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
            border-left: 4px solid #3498db;
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
            width: 30%;
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
        <h1>🍽️ RegimeApp</h1>
        <p>Facture Utilisateur</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Personnelles</div>
        <table>
            <tr>
                <td class="label">Nom Complet :</td>
                <td><?= htmlspecialchars($user['nom'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Email :</td>
                <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Nom d'utilisateur :</td>
                <td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">IMC :</td>
                <td><?= number_format($user['imc'] ?? 0, 2) ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Données Compte</div>
        <table>
            <tr>
                <td class="label">Solde Portefeuille :</td>
                <td><?= number_format($user['solde_portefeuille'] ?? 0, 2) ?>€</td>
            </tr>
            <tr>
                <td class="label">Statut Gold :</td>
                <td><?= $user['is_gold'] ? '✓ Oui' : 'Non' ?></td>
            </tr>
            <tr>
                <td class="label">Rôle :</td>
                <td><?= htmlspecialchars($user['role'] ?? 'user') ?></td>
            </tr>
            <tr>
                <td class="label">Date d'inscription :</td>
                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Généré le <?= $generatedAt ?></p>
        <p>© RegimeApp 2026 - Tous droits réservés</p>
    </div>
</body>
</html>
