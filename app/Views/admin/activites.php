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
        .card-form {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
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

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h3 style="margin-bottom: 20px;"><?= isset($editingActivite) && $editingActivite ? 'Modifier l’activité' : 'Ajouter une activité' ?></h3>
            <form method="POST" action="<?= base_url('admin/activites/save') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($editingActivite['id'] ?? '') ?>">

                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= esc(old('nom', $editingActivite['nom'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <?php $selectedType = old('type', $editingActivite['type'] ?? 'cardio'); ?>
                    <select name="type" class="form-select" required>
                        <option value="cardio" <?= $selectedType === 'cardio' ? 'selected' : '' ?>>Cardio</option>
                        <option value="musculation" <?= $selectedType === 'musculation' ? 'selected' : '' ?>>Musculation</option>
                        <option value="yoga" <?= $selectedType === 'yoga' ? 'selected' : '' ?>>Yoga</option>
                        <option value="autre" <?= $selectedType === 'autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Intensité</label>
                    <?php $selectedIntensite = old('intensite', $editingActivite['intensite'] ?? 'moyenne'); ?>
                    <select name="intensite" class="form-select" required>
                        <option value="basse" <?= $selectedIntensite === 'basse' ? 'selected' : '' ?>>Basse</option>
                        <option value="moyenne" <?= $selectedIntensite === 'moyenne' ? 'selected' : '' ?>>Moyenne</option>
                        <option value="haute" <?= $selectedIntensite === 'haute' ? 'selected' : '' ?>>Haute</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc(old('description', $editingActivite['description'] ?? '')) ?></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Durée (jours)</label>
                    <input type="number" name="duree_jours" class="form-control" value="<?= esc(old('duree_jours', $editingActivite['duree_jours'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Calories brûlées</label>
                    <input type="number" name="calories_brulees" class="form-control" value="<?= esc(old('calories_brulees', $editingActivite['calories_brulees'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prix (€)</label>
                    <input type="number" step="0.01" name="prix" class="form-control" value="<?= esc(old('prix', $editingActivite['prix'] ?? '')) ?>" required>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark"><?= isset($editingActivite) && $editingActivite ? 'Mettre à jour' : 'Ajouter' ?></button>
                    <?php if (isset($editingActivite) && $editingActivite): ?>
                        <a href="<?= base_url('admin/activites') ?>" class="btn btn-outline-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

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
                        <th>Actions</th>
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
                            <td>
                                <a href="<?= base_url('admin/activites?edit=' . $activite['id']) ?>" class="btn btn-sm btn-primary">Modifier</a>
                                <form method="POST" action="<?= base_url('admin/activites/delete/' . $activite['id']) ?>" style="display:inline;" onsubmit="return confirm('Supprimer cette activité ?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
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
