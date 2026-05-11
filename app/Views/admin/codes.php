<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Codes Portefeuille - Admin</title>
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
        .badge-valide {
            background: #27ae60;
        }
        .badge-utilisee {
            background: #e74c3c;
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
        <h1 class="page-title">💳 Gestion des Codes Portefeuille</h1>

        <a href="<?= base_url('admin') ?>" class="btn-back">← Retour</a>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h3 style="margin-bottom: 20px;"><?= isset($editingCode) && $editingCode ? 'Modifier le code' : 'Ajouter un code' ?></h3>
            <form method="POST" action="<?= base_url('admin/codes/save') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($editingCode['id'] ?? '') ?>">

                <div class="col-md-5">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="<?= esc(old('code', $editingCode['code'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant (€)</label>
                    <input type="number" step="0.01" name="montant" class="form-control" value="<?= esc(old('montant', $editingCode['montant'] ?? '')) ?>" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <?php $checked = old('valide', isset($editingCode) ? (int) $editingCode['valide'] : 1); ?>
                    <input type="hidden" name="valide" value="0">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="valide" value="1" id="valideCode" <?= $checked ? 'checked' : '' ?>>
                        <label class="form-check-label" for="valideCode">Valide</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark"><?= isset($editingCode) && $editingCode ? 'Mettre à jour' : 'Ajouter' ?></button>
                    <?php if (isset($editingCode) && $editingCode): ?>
                        <a href="<?= base_url('admin/codes') ?>" class="btn btn-outline-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Utilisé Par</th>
                        <th>Date Utilisation</th>
                        <th>Date Création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($codes as $code): ?>
                        <tr>
                            <td><code style="font-weight: 700;"><?= htmlspecialchars($code['code']) ?></code></td>
                            <td><?= number_format($code['montant'], 2) ?>€</td>
                            <td>
                                <?php if ($code['valide'] == 1): ?>
                                    <span class="badge badge-valide">✓ Valide</span>
                                <?php else: ?>
                                    <span class="badge badge-utilisee">✗ Utilisée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($code['utilisateur_username'])): ?>
                                    <small><?= esc($code['utilisateur_username']) ?></small>
                                <?php else: ?>
                                    <small style="color: #999;">-</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($code['date_utilisation']): ?>
                                    <?= date('d/m/Y H:i', strtotime($code['date_utilisation'])) ?>
                                <?php else: ?>
                                    <small style="color: #999;">-</small>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($code['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('admin/codes?edit=' . $code['id']) ?>" class="btn btn-sm btn-primary">Modifier</a>
                                <form method="POST" action="<?= base_url('admin/codes/delete/' . $code['id']) ?>" style="display:inline;" onsubmit="return confirm('Supprimer ce code ?');">
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
            <small style="color: #999;">Total: <?= count($codes) ?> codes | 
                <?php 
                    $valid_codes = array_filter($codes, function($c) { return $c['valide'] == 1; });
                    echo count($valid_codes) . ' valides';
                ?>
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
