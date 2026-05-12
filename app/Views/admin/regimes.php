<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Regimes - Admin</title>
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
        .badge-perte {
            background: #e74c3c;
        }
        .badge-prise {
            background: #27ae60;
        }
        .badge-maintien {
            background: #f39c12;
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
        <h1 class="page-title">🥗 Gestion des Regimes</h1>

        <a href="<?= base_url('admin') ?>" class="btn-back">← Retour</a>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card-form">
            <h3 style="margin-bottom: 20px;"><?= isset($editingRegime) && $editingRegime ? 'Modifier le regime' : 'Ajouter un regime' ?></h3>
            <form method="POST" action="<?= base_url('admin/regimes/save') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($editingRegime['id'] ?? '') ?>">

                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= esc(old('nom', $editingRegime['nom'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <?php $selectedType = old('type', $editingRegime['type'] ?? 'perte'); ?>
                        <option value="perte" <?= $selectedType === 'perte' ? 'selected' : '' ?>>Perte</option>
                        <option value="prise" <?= $selectedType === 'prise' ? 'selected' : '' ?>>Prise</option>
                        <option value="maintien" <?= $selectedType === 'maintien' ? 'selected' : '' ?>>Maintien</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc(old('description', $editingRegime['description'] ?? '')) ?></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Duree (jours)</label>
                    <input type="number" name="duree_jours" class="form-control" value="<?= esc(old('duree_jours', $editingRegime['duree_jours'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prix (€)</label>
                    <input type="number" step="0.01" name="prix" class="form-control" value="<?= esc(old('prix', $editingRegime['prix'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Variation min (kg)</label>
                    <input type="number" step="0.01" name="poids_variation_min" class="form-control" value="<?= esc(old('poids_variation_min', $editingRegime['poids_variation_min'] ?? '')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Variation max (kg)</label>
                    <input type="number" step="0.01" name="poids_variation_max" class="form-control" value="<?= esc(old('poids_variation_max', $editingRegime['poids_variation_max'] ?? '')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Viande %</label>
                    <input type="number" name="pourcentage_viande" class="form-control" value="<?= esc(old('pourcentage_viande', $editingRegime['pourcentage_viande'] ?? '0')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Poisson %</label>
                    <input type="number" name="pourcentage_poisson" class="form-control" value="<?= esc(old('pourcentage_poisson', $editingRegime['pourcentage_poisson'] ?? '0')) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Volaille %</label>
                    <input type="number" name="pourcentage_volaille" class="form-control" value="<?= esc(old('pourcentage_volaille', $editingRegime['pourcentage_volaille'] ?? '0')) ?>" required>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark"><?= isset($editingRegime) && $editingRegime ? 'Mettre a jour' : 'Ajouter' ?></button>
                    <?php if (isset($editingRegime) && $editingRegime): ?>
                        <a href="<?= base_url('admin/regimes') ?>" class="btn btn-outline-secondary">Annuler</a>
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
                        <th>Duree</th>
                        <th>Prix</th>
                        <th>Variation Poids</th>
                        <th>Viande %</th>
                        <th>Poisson %</th>
                        <th>Volaille %</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regimes as $regime): ?>
                        <tr>
                            <td><?= htmlspecialchars($regime['nom']) ?></td>
                            <td>
                                <?php if ($regime['type'] === 'perte'): ?>
                                    <span class="badge badge-perte">Perte</span>
                                <?php elseif ($regime['type'] === 'prise'): ?>
                                    <span class="badge badge-prise">Prise</span>
                                <?php else: ?>
                                    <span class="badge badge-maintien">Maintien</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $regime['duree_jours'] ?> j</td>
                            <td><?= number_format($regime['prix'], 2) ?>€</td>
                            <td><?= $regime['poids_variation_min'] ?> a <?= $regime['poids_variation_max'] ?> kg</td>
                            <td><?= $regime['pourcentage_viande'] ?>%</td>
                            <td><?= $regime['pourcentage_poisson'] ?>%</td>
                            <td><?= $regime['pourcentage_volaille'] ?>%</td>
                            <td>
                                <a href="<?= base_url('admin/regimes?edit=' . $regime['id']) ?>" class="btn btn-sm btn-primary">Modifier</a>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#tariffs-<?= $regime['id'] ?>">Tarifs</button>
                                <form method="POST" action="<?= base_url('admin/regimes/delete/' . $regime['id']) ?>" style="display:inline;" onsubmit="return confirm('Supprimer ce regime ?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <!-- Row Tarifs -->
                        <tr class="collapse" id="tariffs-<?= $regime['id'] ?>">
                            <td colspan="9">
                                <div style="padding: 20px; background: #f9f9f9; border-radius: 5px;">
                                    <h5>💰 Tarifs pour <?= htmlspecialchars($regime['nom']) ?></h5>
                                    
                                    <!-- Tableau des tarifs existants -->
                                    <?php if (!empty($regime['tariffs'])): ?>
                                        <table class="table table-sm" style="margin-top: 15px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Duree</th>
                                                    <th>Prix (€)</th>
                                                    <th>Reduction %</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($regime['tariffs'] as $tariff): ?>
                                                    <tr>
                                                        <td><?= $tariff['duree_jours'] ?> jours</td>
                                                        <td><?= number_format($tariff['prix'], 2) ?>€</td>
                                                        <td><?= $tariff['reduction_pourcentage'] ?>%</td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-warning" data-bs-toggle="modal" data-bs-target="#editTariff<?= $tariff['id'] ?>">editer</button>
                                                            <form method="POST" action="<?= base_url('admin/regimes/tariff/delete/' . $tariff['id']) ?>" style="display:inline;" onsubmit="return confirm('Supprimer ce tarif ?');">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="btn btn-xs btn-danger">Supprimer</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal edition Tarif -->
                                                    <div class="modal fade" id="editTariff<?= $tariff['id'] ?>" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Modifier tarif - <?= htmlspecialchars($regime['nom']) ?> (<?= $tariff['duree_jours'] ?> j)</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form method="POST" action="<?= base_url('admin/regimes/tariff/save') ?>">
                                                                    <?= csrf_field() ?>
                                                                    <input type="hidden" name="regime_id" value="<?= $regime['id'] ?>">
                                                                    <input type="hidden" name="duree_jours" value="<?= $tariff['duree_jours'] ?>">
                                                                    
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Prix (€)</label>
                                                                            <input type="number" step="0.01" name="prix" class="form-control" value="<?= $tariff['prix'] ?>" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Reduction (%)</label>
                                                                            <input type="number" min="0" max="100" name="reduction_pourcentage" class="form-control" value="<?= $tariff['reduction_pourcentage'] ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                        <button type="submit" class="btn btn-primary">Mettre a jour</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p style="color: #999; margin-top: 10px;">Aucun tarif defini pour ce regime.</p>
                                    <?php endif; ?>

                                    <!-- Formulaire Ajouter Tarif -->
                                    <h6 style="margin-top: 20px;">Ajouter un nouveau tarif</h6>
                                    <form method="POST" action="<?= base_url('admin/regimes/tariff/save') ?>" class="row g-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="regime_id" value="<?= $regime['id'] ?>">
                                        
                                        <div class="col-md-3">
                                            <label class="form-label">Duree (jours)</label>
                                            <select name="duree_jours" class="form-select" required>
                                                <option value="">Selectionner</option>
                                                <?php 
                                                    $existingDurees = array_column($regime['tariffs'] ?? [], 'duree_jours');
                                                    foreach ([7, 14, 30, 90] as $duree): 
                                                        if (!in_array($duree, $existingDurees)):
                                                ?>
                                                    <option value="<?= $duree ?>"><?= $duree ?> jours</option>
                                                <?php 
                                                        endif;
                                                    endforeach; 
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Prix (€)</label>
                                            <input type="number" step="0.01" name="prix" class="form-control" placeholder="0.00" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Reduction (%)</label>
                                            <input type="number" min="0" max="100" name="reduction_pourcentage" class="form-control" placeholder="0" value="0">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-success w-100">+ Ajouter</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
            <small style="color: #999;">Total: <?= count($regimes) ?> regimes</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

