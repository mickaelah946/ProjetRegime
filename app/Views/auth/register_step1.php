<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription etape 1 - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
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
            background: #00BCD4;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 auto 5px;
        }
        .step-item.active .step-number {
            background: #0097A7;
            box-shadow: 0 5px 15px rgba(0, 151, 167, 0.4);
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
            border-color: #00BCD4;
            box-shadow: 0 0 5px rgba(0, 188, 212, 0.5);
        }
        .btn-next {
            width: 100%;
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
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
            box-shadow: 0 5px 15px rgba(0, 188, 212, 0.4);
        }
        .register-footer {
            text-align: center;
            margin-top: 20px;
        }
        .register-footer a {
            color: #00BCD4;
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
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input {
            flex: 1;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #00BCD4;
            font-size: 1.2rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .password-toggle:hover {
            color: #0097A7;
        }
        .form-error {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }
        .form-control.has-error {
            border-color: #e74c3c !important;
            background-color: #ffe6e6;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>🍽️ RegimeApp</h2>
            <p class="text-muted">Creez votre compte</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item active">
                <div class="step-number">1</div>
                <p>Infos perso</p>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <p>Sante</p>
            </div>
        </div>

        <form method="POST" action="<?= base_url('register/step1') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nom" class="form-label">Nom complet</label>
                <input 
                    type="text" 
                    class="form-control <?= session('validation') && session('validation')->hasError('nom') ? 'has-error' : '' ?>" 
                    id="nom" 
                    name="nom" 
                    placeholder="Jean Dupont"
                    value="<?= old('nom') ?>"
                    required
                >
                <?php if (session('validation') && session('validation')->hasError('nom')): ?>
                    <span class="form-error"><?= session('validation')->getError('nom') ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    class="form-control <?= session('validation') && session('validation')->hasError('email') ? 'has-error' : '' ?>" 
                    id="email" 
                    name="email" 
                    placeholder="votre@email.com"
                    value="<?= old('email') ?>"
                    required
                >
                <?php if (session('validation') && session('validation')->hasError('email')): ?>
                    <span class="form-error"><?= session('validation')->getError('email') ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input 
                    type="text" 
                    class="form-control <?= session('validation') && session('validation')->hasError('username') ? 'has-error' : '' ?>" 
                    id="username" 
                    name="username" 
                    placeholder="jdupont"
                    value="<?= old('username') ?>"
                    required
                >
                <?php if (session('validation') && session('validation')->hasError('username')): ?>
                    <span class="form-error"><?= session('validation')->getError('username') ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-control <?= session('validation') && session('validation')->hasError('genre') ? 'has-error' : '' ?>" id="genre" name="genre" required>
                    <option value="">-- Selectionner --</option>
                    <option value="M" <?= old('genre') === 'M' ? 'selected' : '' ?>>Homme</option>
                    <option value="F" <?= old('genre') === 'F' ? 'selected' : '' ?>>Femme</option>
                </select>
                <?php if (session('validation') && session('validation')->hasError('genre')): ?>
                    <span class="form-error"><?= session('validation')->getError('genre') ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        class="form-control <?= session('validation') && session('validation')->hasError('password') ? 'has-error' : '' ?>" 
                        id="password" 
                        name="password" 
                        placeholder="Minimum 6 caracteres"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()" title="Afficher/masquer">
                        <span id="eye-icon">●</span>
                    </button>
                </div>
                <?php if (session('validation') && session('validation')->hasError('password')): ?>
                    <span class="form-error"><?= session('validation')->getError('password') ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-next">Continuer vers etape 2 →</button>
        </form>

        <div class="register-footer">
            <p>Deja inscrit ? <a href="<?= base_url('login') ?>">Se connecter</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '◉';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '●';
            }
        }
    </script>
</body>
</html>

