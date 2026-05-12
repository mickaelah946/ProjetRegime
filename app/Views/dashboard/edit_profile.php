<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier profil - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px 15px;
        }
        .panel {
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 28px;
        }
        .btn-main {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
            color: #fff;
            border: 0;
            font-weight: 600;
        }
        .btn-main:hover {
            color: #fff;
            filter: brightness(0.96);
        }
    </style>
</head>
<body>
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Modifier mon profil</h1>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Retour</a>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('profile/update') ?>" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= esc(old('nom', $user['nom'])) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc(old('email', $user['email'])) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Genre</label>
                <?php $genre = old('genre', $user['genre']); ?>
                <select name="genre" class="form-select" required>
                    <option value="M" <?= $genre === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= $genre === 'F' ? 'selected' : '' ?>>Feminin</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Taille (m)</label>
                <input type="number" step="0.01" name="taille" class="form-control" value="<?= esc(old('taille', $user['taille'])) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Poids (kg)</label>
                <input type="number" step="0.01" name="poids" class="form-control" value="<?= esc(old('poids', $user['poids'])) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="Laisser vide pour conserver le mot de passe actuel">
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-main">Enregistrer</button>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>

