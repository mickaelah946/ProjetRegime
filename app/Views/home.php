<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegimeApp - Accueil</title>
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
        .container-custom {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            background: white;
            overflow: hidden;
        }
        .card-body {
            padding: 50px 30px;
            text-align: center;
        }
        .logo {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #999;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .user-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 5px;
        }
        .user-email {
            color: #999;
            font-size: 0.95rem;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 0 5px;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-secondary-custom {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            margin: 0 5px;
        }
        .btn-secondary-custom:hover {
            background: #e0e0e0;
        }
        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .divider {
            margin: 20px 0;
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="card">
            <div class="card-body">
                <div class="logo">🏋️</div>
                <h1>RegimeApp</h1>
                <p class="subtitle">Votre compagnon de fitness personnel</p>

                <?php if (session()->get('isLoggedIn')): ?>
                    <!-- Utilisateur connecte -->
                    <div class="user-info">
                        <div class="user-name">Bienvenue, <?= htmlspecialchars(session()->get('nom')) ?> !</div>
                        <div class="user-email"><?= htmlspecialchars(session()->get('email')) ?></div>
                    </div>

                    <p style="color: #666; margin-bottom: 20px;">
                        Pret a commencer votre transformation ? Accedez a votre dashboard personnalise.
                    </p>

                    <div class="buttons">
                        <a href="<?= base_url('dashboard') ?>" class="btn-primary-custom">
                            📊 Aller au Dashboard
                        </a>
                    </div>

                    <div class="divider">ou</div>

                    <form method="POST" action="<?= base_url('logout') ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-secondary-custom">
                            🚪 Se deconnecter
                        </button>
                    </form>

                <?php else: ?>
                    <!-- Utilisateur non connecte -->
                    <p style="color: #666; margin-bottom: 30px;">
                        Rejoignez des milliers de personnes qui ont transforme leur vie avec nos plans personnalises.
                    </p>

                    <div class="buttons">
                        <a href="<?= base_url('login') ?>" class="btn-primary-custom">
                            🔓 Se Connecter
                        </a>
                        <a href="<?= base_url('register/step1') ?>" class="btn-secondary-custom">
                            ✍️ S'Inscrire
                        </a>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

