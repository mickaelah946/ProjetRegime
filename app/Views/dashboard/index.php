<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            margin-left: 20px;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: white !important;
        }
        .container-main {
            max-width: 1000px;
            margin: 30px auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
            font-weight: 600;
        }
        .card-body {
            padding: 25px;
        }
        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .stat-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }
        .stat-label {
            color: #666;
            font-size: 0.95rem;
        }
        .stat-value {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }
        .imc-value {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
        }
        .imc-maigre { background: #3498db; }
        .imc-normal { background: #27ae60; }
        .imc-surpoids { background: #f39c12; }
        .imc-obese { background: #e74c3c; }
        .objectif-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }
        .objectif-item {
            padding: 10px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .objectif-item input[type="checkbox"] {
            margin-right: 10px;
            cursor: pointer;
        }
        .btn-main {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            width: 100%;
        }
        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .gold-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 10px;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }
        .action-btn {
            padding: 15px;
            border: 2px solid #667eea;
            border-radius: 8px;
            background: white;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }
        .action-btn:hover {
            background: #667eea;
            color: white;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            border: none;
        }
        .wallet-section {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .wallet-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
        }
        @media (max-width: 768px) {
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="navbar-brand">🍽️ RegimeApp</div>
            <div>
                <span class="text-white me-4">Bienvenue, <?= htmlspecialchars($user['nom']) ?></span>
                <a href="<?= base_url('logout') ?>" class="btn btn-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <!-- Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- SECTION 1: STATISTIQUES IMC -->
        <div class="card">
            <div class="card-header">
                📊 Vos Statistiques
            </div>
            <div class="card-body">
                <div class="stat-row">
                    <span class="stat-label">Taille</span>
                    <span class="stat-value"><?= $user['taille'] ?>m</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Poids</span>
                    <span class="stat-value"><?= $user['poids'] ?>kg</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">IMC</span>
                    <span class="imc-value imc-<?= strtolower(str_replace(' ', '-', $imcCategorie['nom'])) ?>">
                        <?= $imc ?> (<?= $imcCategorie['nom'] ?>)
                    </span>
                </div>
            </div>
        </div>

        <!-- SECTION 2: PORTEFEUILLE -->
        <div class="card">
            <div class="card-header">
                👛 Votre Portefeuille
            </div>
            <div class="card-body">
                <div class="wallet-section">
                    <div class="stat-label">Solde disponible</div>
                    <div class="wallet-amount"><?= number_format($user['solde_portefeuille'], 2) ?>€</div>
                </div>

                <!-- Ajouter argent avec code -->
                <form method="POST" action="<?= base_url('dashboard/validate-code') ?>" class="mb-3">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="code" 
                            placeholder="Entrez votre code..."
                            required
                        >
                        <button type="submit" class="btn btn-main" style="width: auto;">Valider</button>
                    </div>
                </form>

                <?php if ($user['is_gold']): ?>
                    <div style="text-align: center; padding: 10px; background: #fff3cd; border-radius: 5px;">
                        <span class="gold-badge">✓ OPTION GOLD ACTIVE - 15% de remise</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- SECTION 3: OBJECTIFS -->
        <div class="card">
            <div class="card-header">
                🎯 Vos Objectifs (<?= count($userObjectifs) ?>/3)
            </div>
            <div class="card-body">
                <?php if (count($userObjectifs) >= 3): ?>
                    <div class="objectif-list">
                        <?php foreach ($userObjectifs as $obj): ?>
                            <div class="objectif-item">
                                ✓ <?= htmlspecialchars($obj['nom']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= base_url('dashboard/select-objectifs') ?>" class="btn btn-secondary">Modifier</a>
                <?php else: ?>
                    <p class="mb-3">Vous devez choisir 3 objectives pour continuer.</p>
                    <a href="<?= base_url('dashboard/select-objectifs') ?>" class="btn btn-main">Choisir 3 objectifs</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- SECTION 4: OPTION GOLD -->
        <?php if (!$user['is_gold']): ?>
            <div class="card">
                <div class="card-header">
                    🏆 Option Gold
                </div>
                <div class="card-body" style="text-align: center;">
                    <p>Obtenez <strong>15% de remise</strong> sur tous les régimes</p>
                    <p style="font-size: 2rem; font-weight: 700; color: #f39c12; margin: 15px 0;">9.99€</p>
                    <p style="color: #999; margin-bottom: 20px;">Une seule fois, illimité</p>
                    <form method="POST" action="<?= base_url('dashboard/buy-gold') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-main">Acheter Option Gold</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION 5: ACTIONS PRINCIPALES -->
        <div class="card">
            <div class="card-header">
                🚀 Actions Principales
            </div>
            <div class="card-body">
                <div class="actions-grid">
                    <a href="<?= base_url('regime/browse') ?>" class="action-btn">📋 Voir régimes</a>
                    <a href="<?= base_url('regime/active') ?>" class="action-btn">📊 Mes régimes actifs</a>
                    <a href="#" class="action-btn">✏️ Modifier profil</a>
                    <a href="#" class="action-btn">📥 Exporter PDF</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
