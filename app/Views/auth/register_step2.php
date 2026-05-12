<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription etape 2 - RegimeApp</title>
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
        .buttons {
            display: flex;
            gap: 10px;
        }
        .btn-next {
            flex: 1;
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
        .btn-back {
            flex: 1;
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: #e0e0e0;
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
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            color: #666;
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
            <p class="text-muted">Informations de sante</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item">
                <div class="step-number">1</div>
                <p>Infos perso</p>
            </div>
            <div class="step-item active">
                <div class="step-number">2</div>
                <p>Sante</p>
            </div>
        </div>

        <div class="info-box">
            <strong>📏 etape 2 sur 2</strong><br>
            Veuillez entrer vos informations de sante. Ces donnees nous permettront de recommander le meilleur regime pour vous.
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

        <form method="POST" action="<?= base_url('register/step2') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="taille" class="form-label">Taille (en metres)</label>
                <input 
                    type="number" 
                    step="0.01"
                    class="form-control" 
                    id="taille" 
                    name="taille" 
                    placeholder="1.75"
                    min="1.0"
                    max="3.0"
                    value="<?= old('taille') ?>"
                    required
                >
                <small class="text-muted">Exemple: 1.75 pour 1m75</small>
            </div>

            <div class="form-group">
                <label for="poids" class="form-label">Poids (en kg)</label>
                <input 
                    type="number" 
                    step="0.1"
                    class="form-control" 
                    id="poids" 
                    name="poids" 
                    placeholder="70"
                    min="20"
                    max="500"
                    value="<?= old('poids') ?>"
                    required
                >
                <small class="text-muted">Exemple: 70 pour 70 kg</small>
            </div>

            <div class="buttons">
                <a href="<?= base_url('register/step1') ?>" class="btn-back">← Retour</a>
                <button type="submit" class="btn-next">Terminer l'inscription ✓</button>
            </div>
        </form>

        <div class="register-footer">
            <p>Deja inscrit ? <a href="<?= base_url('login') ?>">Se connecter</a></p>
        </div>
    </div>
</body>
</html>

