<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étape 1 - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            align-items: center;
        }
        .step-item {
            text-align: center;
            flex: 1;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 auto 5px;
        }
        .step-item.active .step-number {
            background: #764ba2;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }
        .step-item p {
            font-size: 0.9rem;
            color: #999;
        }
        .form-control {
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }
        .btn-next {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .register-footer {
            text-align: center;
            margin-top: 20px;
        }
        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>🍽️ RegimeApp</h2>
            <p class="text-muted">Créez votre compte</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item active">
                <div class="step-number">1</div>
                <p>Infos perso</p>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <p>Santé</p>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?php if (is_array(session()->getFlashdata('error'))): ?>
                    <ul class="mb-0">
                        <?php foreach(session()->getFlashdata('error') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= session()->getFlashdata('error') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('register/step1') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nom" class="form-label">Nom complet</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="nom" 
                    name="nom" 
                    placeholder="Jean Dupont"
                    value="<?= old('nom') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="votre@email.com"
                    value="<?= old('email') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="username" 
                    name="username" 
                    placeholder="jdupont"
                    value="<?= old('username') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-control" id="genre" name="genre" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="M" <?= old('genre') === 'M' ? 'selected' : '' ?>>Homme</option>
                    <option value="F" <?= old('genre') === 'F' ? 'selected' : '' ?>>Femme</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Minimum 6 caractères"
                    required
                >
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmer mot de passe</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Confirmer votre mot de passe"
                    required
                >
            </div>

            <button type="submit" class="btn-next">Continuer vers étape 2 →</button>
        </form>

        <div class="register-footer">
            <p>Déjà inscrit ? <a href="<?= base_url('login') ?>">Se connecter</a></p>
        </div>
    </div>
</body>
</html>
