<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Activités Actives - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 30px;
            border-radius: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            color: white;
        }
        .navbar-custom .brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .navbar-custom .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .navbar-custom a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .navbar-custom a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
        }
        .page-title {
            color: white;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
        }
        .activites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .activite-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .activite-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .activite-header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            padding: 20px;
            color: white;
        }
        .activite-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }
        .activite-body {
            padding: 20px;
        }
        .activite-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .stat-item {
            text-align: center;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #999;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-top: 5px;
        }
        .activite-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .detail-label {
            color: #666;
            font-weight: 600;
        }
        .detail-value {
            color: #333;
            font-weight: 700;
        }
        .btn-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-action:hover {
            transform: scale(1.05);
            color: white;
        }
        .btn-cancel {
            background: #e74c3c;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
        }
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .empty-state-text {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 20px;
        }
        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .nav-buttons a {
            flex: 1;
            text-align: center;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-custom">
        <div class="brand">🏋️ RegimeApp</div>
        <div class="user-info">
            <span>Bienvenue, <?= htmlspecialchars(session()->get('nom')) ?> !</span>
            <form method="POST" action="<?= base_url('logout') ?>" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; text-decoration: underline;">Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container-custom">
        <h1 class="page-title">💪 Mes Activités Actives</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Info utilisateur -->
        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 30px; flex-wrap: wrap;">
            <div>
                <strong>Solde:</strong> <span style="color: #667eea; font-size: 1.2rem; font-weight: 700;"><?= number_format($user['solde_portefeuille'], 2) ?>€</span>
            </div>
            <div>
                <strong>Activités Actives:</strong> <span style="color: #e74c3c; font-size: 1.2rem; font-weight: 700;"><?= count($userActivites) ?></span>
            </div>
        </div>

        <!-- Activités actives -->
        <?php if (!empty($userActivites)): ?>
            <div class="activites-grid">
                <?php foreach ($userActivites as $activite): ?>
                    <div class="activite-card">
                        <div class="activite-header">
                            <h3 class="activite-title"><?= htmlspecialchars($activite['nom']) ?></h3>
                            <p style="margin: 8px 0 0 0; font-size: 0.9rem;">✓ Actif</p>
                        </div>
                        <div class="activite-body">
                            <p style="margin-bottom: 15px; color: #666; line-height: 1.5;"><?= htmlspecialchars($activite['description']) ?></p>

                            <div class="activite-stats">
                                <div class="stat-item">
                                    <div class="stat-label">Durée</div>
                                    <div class="stat-value"><?= $activite['duree_jours'] ?> j</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Calories</div>
                                    <div class="stat-value"><?= $activite['calories_brulees'] ?> kcal</div>
                                </div>
                            </div>

                            <div class="activite-details">
                                <div class="detail-row">
                                    <span class="detail-label">📅 Début:</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($activite['date_selection'])) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">📅 Fin prévue:</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($activite['date_fin_prevu'])) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">💰 Payé:</span>
                                    <span class="detail-value"><?= number_format($activite['prix_paye'], 2) ?>€</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">🔄 Remboursement (50%):</span>
                                    <span class="detail-value" style="color: #e74c3c;"><?= number_format($activite['prix_paye'] * 0.5, 2) ?>€</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Type:</span>
                                    <span class="detail-value" style="text-transform: capitalize;"><?= $activite['type'] ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Intensité:</span>
                                    <span class="detail-value" style="text-transform: capitalize;"><?= $activite['intensite'] ?></span>
                                </div>
                            </div>

                            <form method="POST" action="<?= base_url('activity/cancel/' . $activite['user_activite_id']) ?>" style="display: inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-action btn-cancel">Annuler cette activité</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <div class="empty-state-text">Vous n'avez aucune activité active</div>
                <p style="color: #999; margin-bottom: 30px;">Parcourez nos activités physiques recommandées et en sélectionnez une pour commencer !</p>
                <a href="<?= base_url('activity/browse') ?>" class="btn-action">🏃 Voir les activités</a>
            </div>
        <?php endif; ?>

        <!-- Boutons de navigation -->
        <div style="text-align: center; margin-top: 40px;">
            <div class="nav-buttons">
                <a href="<?= base_url('activity/browse') ?>" class="btn-action">← Activités recommandées</a>
                <a href="<?= base_url('regime/browse') ?>" class="btn-action">← Régimes</a>
                <a href="<?= base_url('dashboard') ?>" class="btn-action">← Retour au Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
