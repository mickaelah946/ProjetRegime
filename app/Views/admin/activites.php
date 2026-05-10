<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Activités - Admin</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .page-title {
            color: white;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        table {
            margin: 0;
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
        .badge-cardio {
            background: #e74c3c;
        }
        .badge-muscu {
            background: #27ae60;
        }
        .badge-yoga {
            background: #9b59b6;
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
                <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container-custom">
        <h1 class="page-title">💪 Gestion des Activités</h1>

        <a href="<?= base_url('admin') ?>" class="btn-back">← Retour</a>

        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Intensité</th>
                        <th>Durée</th>
                        <th>Calories Brûlées</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activites as $activite): ?>
                        <tr>
                            <td><?= htmlspecialchars($activite['nom']) ?></td>
                            <td>
                                <?php if ($activite['type'] === 'cardio'): ?>
                                    <span class="badge badge-cardio">Cardio</span>
                                <?php elseif ($activite['type'] === 'musculation'): ?>
                                    <span class="badge badge-muscu">Musculation</span>
                                <?php elseif ($activite['type'] === 'yoga'): ?>
                                    <span class="badge badge-yoga">Yoga</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Autre</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="text-transform: capitalize;">
                                    <?php if ($activite['intensite'] === 'basse'): ?>
                                        🟢
                                    <?php elseif ($activite['intensite'] === 'moyenne'): ?>
                                        🟡
                                    <?php else: ?>
                                        🔴
                                    <?php endif; ?>
                                    <?= $activite['intensite'] ?>
                                </span>
                            </td>
                            <td><?= $activite['duree_jours'] ?> j</td>
                            <td><?= $activite['calories_brulees'] ?> kcal</td>
                            <td><?= number_format($activite['prix'], 2) ?>€</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
            <small style="color: #999;">Total: <?= count($activites) ?> activités</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
