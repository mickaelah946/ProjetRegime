<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selectionner Objectifs - RegimeApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 20px;
        }
        .container-custom {
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }
        .card-body {
            padding: 30px;
        }
        .objectif-item {
            padding: 15px;
            margin-bottom: 12px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        .objectif-item:hover {
            background: #f0f1ff;
            border-color: #667eea;
        }
        .objectif-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
            accent-color: #667eea;
        }
        .objectif-item.checked {
            background: #e6e9ff;
            border-color: #667eea;
        }
        .objectif-label {
            margin: 0;
            cursor: pointer;
            flex: 1;
            font-weight: 500;
            color: #333;
        }
        .objectif-description {
            font-size: 0.9rem;
            color: #999;
            margin-top: 5px;
        }
        .count-info {
            text-align: center;
            padding: 15px;
            background: #e6e9ff;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #667eea;
        }
        .count-info.complete {
            background: #c8e6c9;
            color: #27ae60;
        }
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-save {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-save:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
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
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="card">
            <div class="card-header">
                🎯 Selectionner vos Objectifs
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('dashboard/save-objectifs') ?>">
                    <?= csrf_field() ?>

                    <div id="objectifsContainer">
                        <?php foreach ($objectifs as $objectif): 
                            $isSelected = false;
                            foreach ($userObjectifs as $userObj) {
                                if ($userObj['id'] == $objectif['id']) {
                                    $isSelected = true;
                                    break;
                                }
                            }
                        ?>
                            <label class="objectif-item<?= $isSelected ? ' checked' : '' ?>">
                                <input 
                                    type="checkbox" 
                                    name="objectifs[]" 
                                    value="<?= $objectif['id'] ?>"
                                    class="objectif-checkbox"
                                    <?= $isSelected ? 'checked' : '' ?>
                                    onchange="updateCount()"
                                >
                                <div style="flex: 1;">
                                    <div class="objectif-label"><?= htmlspecialchars($objectif['nom']) ?></div>
                                    <div class="objectif-description"><?= htmlspecialchars($objectif['description']) ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="buttons">
                        <a href="<?= base_url('dashboard') ?>" class="btn-back">← Retour</a>
                        <button type="submit" class="btn-save" id="submitBtn" disabled>Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateCount() {
            const checkboxes = document.querySelectorAll('.objectif-checkbox:checked');
            const count = checkboxes.length;
            const submitBtn = document.getElementById('submitBtn');

            // Enable/disable submit button based on selection
            if (count >= 1) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }

            // Update visual state
            document.querySelectorAll('.objectif-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox.checked) {
                    item.classList.add('checked');
                } else {
                    item.classList.remove('checked');
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', updateCount);
    </script>
</body>
</html>

