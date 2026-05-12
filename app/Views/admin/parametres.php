<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parametres systeme - Admin</title>
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
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }
        .card-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="navbar-admin">
        <div style="font-size: 1.5rem; font-weight: 700;">⚙️ Parametres systeme</div>
        <div>
            <a href="<?= base_url('admin') ?>" class="btn btn-light btn-sm">Retour Dashboard</a>
        </div>
    </div>

    <div class="container-custom">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card-box">
            <h2 class="h4 mb-3"><?= $editingParametre ? 'Modifier le parametre' : 'Ajouter un parametre' ?></h2>
            <form method="POST" action="<?= base_url('admin/parametres/save') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($editingParametre['id'] ?? '') ?>">
                <div class="col-md-3">
                    <label class="form-label">Cle</label>
                    <input type="text" name="cle" class="form-control" value="<?= esc(old('cle', $editingParametre['cle'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valeur</label>
                    <input type="text" name="valeur" class="form-control" value="<?= esc(old('valeur', $editingParametre['valeur'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" value="<?= esc(old('description', $editingParametre['description'] ?? '')) ?>">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark"><?= $editingParametre ? 'Mettre a jour' : 'Ajouter' ?></button>
                    <?php if ($editingParametre): ?>
                        <a href="<?= base_url('admin/parametres') ?>" class="btn btn-outline-secondary">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card-box">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cle</th>
                        <th>Valeur</th>
                        <th>Description</th>
                        <th style="width: 190px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parametres as $parametre): ?>
                        <tr>
                            <td><code><?= esc($parametre['cle']) ?></code></td>
                            <td><?= esc($parametre['valeur']) ?></td>
                            <td><?= esc($parametre['description']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/parametres?edit=' . $parametre['id']) ?>" class="btn btn-sm btn-primary">Modifier</a>
                                <form method="POST" action="<?= base_url('admin/parametres/delete/' . $parametre['id']) ?>" style="display:inline;" onsubmit="return confirm('Supprimer ce parametre ?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

