<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcourir les Activites - RegimeApp</title>
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
        .activite-type {
            display: inline-block;
            background: rgba(255, 255, 255, 0.3);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-top: 8px;
        }
        .activite-body {
            padding: 20px;
        }
        .activite-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.5;
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
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .activite-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .prix-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .prix {
            font-size: 1.4rem;
            font-weight: 700;
            color: #667eea;
        }
        .prix-original {
            font-size: 0.9rem;
            color: #999;
            text-decoration: line-through;
        }
        .gold-badge {
            background: #f39c12;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .btn-select {
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
        .btn-select:hover {
            transform: scale(1.05);
            color: white;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .selected-badge {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-header h1 {
            margin: 0;
        }
        .btn-secondary {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.2s;
            display: inline-block;
        }
        .btn-secondary:hover {
            transform: scale(1.05);
            color: white;
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
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; text-decoration: underline;">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container-custom">
        <div class="page-header">
            <h1 class="page-title">💪 Activites Physiques Recommandees</h1>
            <a href="<?= base_url('regime/browse') ?>" class="btn-secondary">🥗 Voir les regimes</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Info utilisateur -->
        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 30px; display: flex; gap: 30px;">
            <div>
                <strong>Solde:</strong> <span style="color: #667eea; font-size: 1.2rem; font-weight: 700;"><?= number_format($user['solde_portefeuille'], 2) ?>€</span>
            </div>
            <div>
                <strong>Statut:</strong> 
                <?php if ($user['is_gold']): ?>
                    <span class="gold-badge">✓ GOLD (15% remise)</span>
                <?php else: ?>
                    <span style="color: #999;">Utilisateur standard</span>
                <?php endif; ?>
            </div>
            <div>
                <strong>Objectifs:</strong> 
                <?php foreach ($userObjectifs as $obj): ?>
                    <span style="background: #667eea; color: white; padding: 2px 8px; border-radius: 3px; margin-left: 5px;"><?= htmlspecialchars($obj['nom']) ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Activites recommandees -->
        <div class="activites-grid">
            <?php foreach ($activites as $activite): ?>
                <div class="activite-card">
                    <div class="activite-header">
                        <h3 class="activite-title"><?= htmlspecialchars($activite['nom']) ?></h3>
                        <span class="activite-type"><?= ucfirst($activite['type']) ?> - <?= ucfirst($activite['intensite']) ?></span>
                    </div>

                    <div class="activite-body">
                        <p class="activite-description"><?= htmlspecialchars($activite['description']) ?></p>

                        <div class="activite-stats">
                            <div class="stat-item">
                                <div class="stat-label">Duree</div>
                                <div class="stat-value"><?= $activite['duree_jours'] ?> j</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Calories</div>
                                <div class="stat-value"><?= $activite['calories_brulees'] ?> kcal</div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="activite-details">
                            <div class="detail-row">
                                <span>Type:</span>
                                <span style="font-weight: 600; text-transform: capitalize;"><?= $activite['type'] ?></span>
                            </div>
                            <div class="detail-row">
                                <span>Intensite:</span>
                                <span style="font-weight: 600; text-transform: capitalize;"><?= $activite['intensite'] ?></span>
                            </div>
                        </div>

                        <!-- Bouton et prix -->
                        <div class="activite-footer">
                            <div class="prix-section">
                                <?php if ($user['is_gold'] && $activite['prix_original'] != $activite['prix']): ?>
                                    <span class="prix-original"><?= number_format($activite['prix_original'], 2) ?>€</span>
                                <?php endif; ?>
                                <span class="prix"><?= number_format($activite['prix'], 2) ?>€</span>
                            </div>

                            <?php if ($activite['already_selected']): ?>
                                <span class="selected-badge">✓ Selectionnee</span>
                            <?php else: ?>
                                <form method="POST" action="<?= base_url('activity/select/' . $activite['id']) ?>" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-select">Choisir</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Activites actives -->
        <?php if (!empty($userActivites)): ?>
            <div style="background: white; padding: 30px; border-radius: 10px; margin-top: 40px;">
                <h2 style="margin-bottom: 20px; color: #333;">📋 Vos Activites Actives</h2>
                <div class="activites-grid" style="margin-bottom: 0;">
                    <?php foreach ($userActivites as $userActivite): ?>
                        <div class="activite-card">
                            <div class="activite-header" style="background: #27ae60;">
                                <h3 class="activite-title"><?= htmlspecialchars($userActivite['nom']) ?></h3>
                                <span style="font-size: 0.9rem;">Depuis le <?= date('d/m/Y', strtotime($userActivite['date_selection'])) ?></span>
                            </div>
                            <div class="activite-body">
                                <p style="margin: 0; color: #666; margin-bottom: 15px;">Fin prevue: <strong><?= date('d/m/Y', strtotime($userActivite['date_fin_prevu'])) ?></strong></p>
                                <p style="margin: 0; color: #666; margin-bottom: 15px;">Prix paye: <strong><?= number_format($userActivite['prix_paye'], 2) ?>€</strong></p>
                                <form method="POST" action="<?= base_url('activity/cancel/' . $userActivite['user_activite_id']) ?>" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-select" style="background: #e74c3c;">Annuler</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Boutons de navigation -->
        <div style="text-align: center; margin-top: 40px;">
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= base_url('activity/active') ?>" class="btn-select">📋 Mes activites actives</a>
                <a href="<?= base_url('regime/browse') ?>" class="btn-select">🥗 Voir les regimes</a>
                <a href="<?= base_url('dashboard') ?>" class="btn-select">← Retour au Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>

