<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau Croise Utilisateurs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }
        .navbar-admin .brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .navbar-admin a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .navbar-admin a:hover {
            background: rgba(255, 255, 255, 0.2);
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
        .btn-back {
            background: #2c3e50;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-back:hover {
            background: #34495e;
            color: white;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
            margin-bottom: 30px;
        }
        .cross-tab-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .cross-tab-table thead {
            background: #2c3e50;
            color: white;
            position: sticky;
            top: 0;
        }
        .cross-tab-table th,
        .cross-tab-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .cross-tab-table th {
            font-weight: 600;
            min-width: 100px;
            font-size: 0.75rem;
        }
        .user-cell {
            text-align: left;
            font-weight: 500;
            white-space: nowrap;
            background: #f8f9fa;
            position: sticky;
            left: 0;
            z-index: 10;
            min-width: 150px;
        }
        .status-actif {
            background: #d4edda;
            color: #155724;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 3px;
        }
        .status-termine {
            background: #cce5ff;
            color: #004085;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 3px;
        }
        .status-annule {
            background: #f8d7da;
            color: #721c24;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 3px;
        }
        .status-none {
            background: #e9ecef;
            color: #666;
            padding: 4px 8px;
            border-radius: 3px;
        }
        .tabs-section {
            margin-bottom: 30px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            border: 2px solid #2c3e50;
            background: white;
            color: #2c3e50;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .tab-btn.active {
            background: #2c3e50;
            color: white;
        }
        .tab-btn:hover {
            background: #34495e;
            color: white;
        }
        .legend {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .legend-color {
            width: 30px;
            height: 25px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
        .gold-badge {
            display: inline-block;
            background: #f39c12;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 5px;
        }
        @media (max-width: 768px) {
            .cross-tab-table {
                font-size: 0.7rem;
            }
            .cross-tab-table th,
            .cross-tab-table td {
                padding: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar-admin">
        <div class="brand">🔐 RegimeApp Admin</div>
        <div>
            <a href="<?= base_url('admin') ?>">← Retour Dashboard</a>
            <form method="POST" action="<?= base_url('logout') ?>" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Deconnexion</button>
            </form>
        </div>
    </div>

    <div class="container-custom">
        <h1 class="page-title">📊 Tableau Croise Utilisateurs</h1>

        <a href="<?= base_url('admin') ?>" class="btn-back">← Retour Dashboard</a>

        <!-- Tabs -->
        <div class="tabs-section">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="switchTab('regimes')">
                    🥗 Regimes Utilisateurs
                </button>
                <button class="tab-btn" onclick="switchTab('activites')">
                    💪 Activites Utilisateurs
                </button>
            </div>

            <!-- TAB 1: ReGIMES -->
            <div id="regimes" class="tab-content active">
                <div class="table-container">
                    <h3 style="margin-bottom: 15px;">Regimes par Utilisateur</h3>
                    
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #d4edda;"></div>
                            <span>Actif</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #cce5ff;"></div>
                            <span>Termine</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f8d7da;"></div>
                            <span>Annule</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #e9ecef;"></div>
                            <span>Aucun</span>
                        </div>
                    </div>

                    <table class="cross-tab-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <?php foreach ($regimes as $regime): ?>
                                    <th><?= htmlspecialchars(substr($regime['nom'], 0, 12)) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="user-cell">
                                        <?= htmlspecialchars($user['nom']) ?>
                                        <?php if ($user['is_gold']): ?>
                                            <span class="gold-badge">GOLD</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php foreach ($regimes as $regime): ?>
                                        <td>
                                            <?php 
                                                $status = $crossTabRegimes[$user['id']][$regime['id']] ?? null;
                                                if ($status === 'actif'):
                                            ?>
                                                <span class="status-actif">✓ Actif</span>
                                            <?php elseif ($status === 'termine'): ?>
                                                <span class="status-termine">✓ Fini</span>
                                            <?php elseif ($status === 'annule'): ?>
                                                <span class="status-annule">✗ Annule</span>
                                            <?php else: ?>
                                                <span class="status-none">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div style="text-align: center; margin-top: 15px; color: #666; font-size: 0.9rem;">
                        <p>
                            Total: <?= count($users) ?> utilisateurs × <?= count($regimes) ?> regimes
                            = <?= count($users) * count($regimes) ?> croisements
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ACTIVITeS -->
            <div id="activites" class="tab-content">
                <div class="table-container">
                    <h3 style="margin-bottom: 15px;">Activites par Utilisateur</h3>
                    
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #d4edda;"></div>
                            <span>Actif</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #cce5ff;"></div>
                            <span>Termine</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f8d7da;"></div>
                            <span>Annule</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #e9ecef;"></div>
                            <span>Aucune</span>
                        </div>
                    </div>

                    <table class="cross-tab-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <?php foreach ($activites as $activite): ?>
                                    <th><?= htmlspecialchars(substr($activite['nom'], 0, 12)) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="user-cell">
                                        <?= htmlspecialchars($user['nom']) ?>
                                        <?php if ($user['is_gold']): ?>
                                            <span class="gold-badge">GOLD</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php foreach ($activites as $activite): ?>
                                        <td>
                                            <?php 
                                                $status = $crossTabActivites[$user['id']][$activite['id']] ?? null;
                                                if ($status === 'actif'):
                                            ?>
                                                <span class="status-actif">✓ Actif</span>
                                            <?php elseif ($status === 'termine'): ?>
                                                <span class="status-termine">✓ Fini</span>
                                            <?php elseif ($status === 'annule'): ?>
                                                <span class="status-annule">✗ Annule</span>
                                            <?php else: ?>
                                                <span class="status-none">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div style="text-align: center; margin-top: 15px; color: #666; font-size: 0.9rem;">
                        <p>
                            Total: <?= count($users) ?> utilisateurs × <?= count($activites) ?> activites
                            = <?= count($users) * count($activites) ?> croisements
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function switchTab(tabName) {
            // Masquer tous les tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Desactiver tous les boutons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Afficher le tab selectionne
            document.getElementById(tabName).classList.add('active');

            // Activer le bouton clique
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

