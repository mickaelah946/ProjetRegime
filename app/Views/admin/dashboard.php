<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-admin {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-admin .brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .navbar-admin .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .navbar-admin a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .navbar-admin a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .admin-badge {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .page-title {
            color: white;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-top: 4px solid #2c3e50;
        }
        .stat-card.blue {
            border-top-color: #3498db;
        }
        .stat-card.green {
            border-top-color: #27ae60;
        }
        .stat-card.orange {
            border-top-color: #f39c12;
        }
        .stat-card.red {
            border-top-color: #e74c3c;
        }
        .stat-card.purple {
            border-top-color: #9b59b6;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 0.95rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .admin-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .menu-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .menu-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .menu-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .menu-description {
            font-size: 0.95rem;
            color: #999;
        }
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        .recent-list {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .recent-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .recent-item:last-child {
            border-bottom: none;
        }
        .recent-item strong {
            color: #2c3e50;
        }
        .recent-item small {
            color: #999;
        }
        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: #c0392b;
            color: white;
        }
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .chart-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body>
    <!-- Navbar Admin -->
    <div class="navbar-admin">
        <div class="brand">🔐 RegimeApp Admin</div>
        <div class="user-info">
            <span class="admin-badge">ADMIN</span>
            <span>Bienvenue, <?= htmlspecialchars(session()->get('nom')) ?> !</span>
            <form method="POST" action="<?= base_url('logout') ?>" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container-custom">
        <h1 class="page-title">📊 Tableau de Bord Administrateur</h1>

        <!-- STATISTIQUES -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-label">Utilisateurs</div>
                <div class="stat-number"><?= $stats['total_users'] ?></div>
                <div style="font-size: 0.9rem; color: #3498db;">
                    <?= $stats['utilisateurs_gold'] ?> Gold
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Régimes</div>
                <div class="stat-number"><?= $stats['total_regimes'] ?></div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Activités</div>
                <div class="stat-number"><?= $stats['total_activites'] ?></div>
            </div>
            <div class="stat-card red">
                <div class="stat-label">Codes Portefeuille</div>
                <div class="stat-number"><?= $stats['total_codes'] ?></div>
                <div style="font-size: 0.9rem; color: #e74c3c;">
                    <?= $stats['codes_utilises'] ?> utilisés
                </div>
            </div>
            <div class="stat-card purple">
                <div class="stat-label">Revenus Totaux</div>
                <div class="stat-number"><?= number_format($stats['revenus_total'], 2) ?>€</div>
            </div>
        </div>

        <!-- GRAPHES CHART.JS -->
        <div class="charts-grid">
            <!-- Pie Chart: Gold vs Normal -->
            <div class="chart-card">
                <div class="chart-title">📊 Utilisateurs Gold vs Normal</div>
                <div class="chart-container">
                    <canvas id="chartGoldVsNormal"></canvas>
                </div>
            </div>

            <!-- Bar Chart: Régimes populaires -->
            <div class="chart-card">
                <div class="chart-title">🥗 Régimes Populaires</div>
                <div class="chart-container">
                    <canvas id="chartRegimes"></canvas>
                </div>
            </div>

            <!-- Line Chart: Revenue Trend -->
            <div class="chart-card">
                <div class="chart-title">📈 Tendance des Revenus (6 mois)</div>
                <div class="chart-container">
                    <canvas id="chartRevenues"></canvas>
                </div>
            </div>
        </div>

        <!-- MENU DE GESTION -->
        <div class="section-title">🔧 Gestion du Système</div>
        <div class="admin-menu">
            <a href="<?= base_url('admin/users') ?>" class="menu-card">
                <div class="menu-icon">👥</div>
                <div class="menu-title">Utilisateurs</div>
                <div class="menu-description">Gérer les utilisateurs et leurs comptes</div>
            </a>
            <a href="<?= base_url('admin/regimes') ?>" class="menu-card">
                <div class="menu-icon">🥗</div>
                <div class="menu-title">Régimes</div>
                <div class="menu-description">Créer, modifier et supprimer les régimes</div>
            </a>
            <a href="<?= base_url('admin/activites') ?>" class="menu-card">
                <div class="menu-icon">💪</div>
                <div class="menu-title">Activités</div>
                <div class="menu-description">Gérer les activités physiques</div>
            </a>
            <a href="<?= base_url('admin/codes') ?>" class="menu-card">
                <div class="menu-icon">🎟️</div>
                <div class="menu-title">Codes Promo</div>
                <div class="menu-description">Créer et gérer les codes portefeuille</div>
            </a>
            <a href="<?= base_url('admin/cross-tab') ?>" class="menu-card">
                <div class="menu-icon">📊</div>
                <div class="menu-title">Tableau Croisé</div>
                <div class="menu-description">Voir les utilisateurs vs régimes/activités</div>
            </a>
            <a href="<?= base_url('dashboard') ?>" class="menu-card">
                <div class="menu-icon">🔄</div>
                <div class="menu-title">Mode Utilisateur</div>
                <div class="menu-description">Voir le dashboard utilisateur</div>
            </a>
        </div>

        <!-- DONNÉES RÉCENTES -->
        <div class="section-title">📋 Derniers Utilisateurs</div>
        <div class="recent-list">
            <?php if (!empty($derniers_users)): ?>
                <?php foreach ($derniers_users as $user): ?>
                    <div class="recent-item">
                        <div>
                            <strong><?= htmlspecialchars($user['nom']) ?></strong>
                            <small><?= htmlspecialchars($user['email']) ?></small>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 600; color: #2c3e50;">
                                IMC: <?= number_format($user['imc'], 2) ?>
                            </div>
                            <?php if ($user['is_gold']): ?>
                                <span style="background: #f39c12; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.8rem;">Gold</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="padding: 20px; text-align: center; color: #999;">
                    Aucun utilisateur
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($regimes_populaires)): ?>
            <div style="margin-top: 40px;">
                <div class="section-title">⭐ Régimes Populaires</div>
                <div class="recent-list">
                    <?php foreach ($regimes_populaires as $regime): ?>
                        <div class="recent-item">
                            <strong><?= htmlspecialchars($regime['nom']) ?></strong>
                            <span style="background: #3498db; color: white; padding: 5px 12px; border-radius: 20px; font-weight: 600;">
                                <?= $regime['count'] ?> sélections
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 50px; margin-bottom: 30px;">
            <p style="color: #999;">Tableau de bord administrateur © RegimeApp 2026</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Pie Chart: Gold vs Normal Users
        const ctxGold = document.getElementById('chartGoldVsNormal').getContext('2d');
        new Chart(ctxGold, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($chartGoldVsNormal['labels']) ?>,
                datasets: [{
                    data: <?= json_encode($chartGoldVsNormal['data']) ?>,
                    backgroundColor: [
                        '#f39c12', // Or
                        '#95a5a6'  // Gris
                    ],
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 12 },
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // 2. Bar Chart: Régimes Populaires
        const ctxRegimes = document.getElementById('chartRegimes').getContext('2d');
        new Chart(ctxRegimes, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartRegimes['labels']) ?>,
                datasets: [{
                    label: 'Sélections',
                    data: <?= json_encode($chartRegimes['data']) ?>,
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e74c3c',
                        '#f39c12',
                        '#9b59b6'
                    ],
                    borderRadius: 5,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...<?= json_encode($chartRegimes['data']) ?>) + 2
                    }
                }
            }
        });

        // 3. Line Chart: Revenue Trend
        const ctxRevenue = document.getElementById('chartRevenues').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartRevenues['labels']) ?>,
                datasets: [{
                    label: 'Revenus (€)',
                    data: <?= json_encode($chartRevenues['data']) ?>,
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    pointRadius: 6,
                    pointBackgroundColor: '#27ae60',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Revenus'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
