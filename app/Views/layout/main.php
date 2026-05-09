<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Bibliotheque') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --bg-1: #f3efe5;
            --bg-2: #e6f0eb;
            --ink: #10211f;
            --muted: #55615f;
            --accent: #0f766e;
            --accent-strong: #115e59;
            --warm: #d97706;
            --card: #ffffff;
            --line: #d9ded6;
            --danger: #b42318;
            --shadow: 0 16px 40px rgba(16, 33, 31, 0.12);
            --radius-lg: 22px;
            --radius-md: 14px;
        }

        body {
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 0%, #f7cf8f55 0, transparent 42%),
                radial-gradient(circle at 88% 12%, #8fcdb955 0, transparent 34%),
                linear-gradient(140deg, var(--bg-1), var(--bg-2));
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5 {
            font-family: "Fraunces", serif;
            letter-spacing: 0.2px;
        }

        .shell-nav {
            background: rgba(16, 33, 31, 0.88);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        }

        .brand-title {
            color: #fff;
            text-decoration: none;
            font-family: "Fraunces", serif;
            font-size: 1.25rem;
            letter-spacing: 0.6px;
        }

        .content-shell {
            padding-top: 2rem;
            padding-bottom: 2.5rem;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            animation: lift-in 0.45s ease both;
        }

        .btn-main {
            background: linear-gradient(120deg, var(--accent), var(--accent-strong));
            color: #fff;
            border: none;
            border-radius: 999px;
            font-weight: 700;
            padding: 0.58rem 1.15rem;
        }

        .btn-main:hover {
            color: #fff;
            filter: brightness(0.96);
        }

        .btn-soft {
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            font-weight: 600;
        }

        .field-sleek {
            border-radius: var(--radius-md);
            border-color: #ccd4ca;
            padding: 0.68rem 0.9rem;
        }

        .field-sleek:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.16);
        }

        .pill {
            border-radius: 999px;
            padding: 0.28rem 0.7rem;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .pill-admin {
            background: #fff3e0;
            color: #8d5c00;
            border: 1px solid #f2cf90;
        }

        .pill-user {
            background: #e5f5ef;
            color: #0d5c4a;
            border: 1px solid #bbdfd3;
        }

        .note {
            color: var(--muted);
            font-size: 0.94rem;
        }

        .table-clean {
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .table-clean thead th {
            background: #113731;
            color: #fff;
            border: 0;
            font-weight: 700;
        }

        .table-clean td {
            vertical-align: middle;
        }

        .alert {
            border-radius: var(--radius-md);
            border: 0;
        }

        @keyframes lift-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<?php
$isLoggedIn = (bool) session()->get('isLoggedIn');
$role = (string) (session()->get('role') ?? '');
$username = (string) (session()->get('username') ?? '');
?>
<nav class="navbar shell-nav px-3 px-md-4 py-3">
    <div class="container-fluid px-0">
        <a class="brand-title" href="<?= $isLoggedIn ? '/' : '/login' ?>">Atelier Bibliotheque</a>
        <?php if ($isLoggedIn): ?>
            <div class="d-flex align-items-center gap-2">
                <span class="pill <?= $role === 'admin' ? 'pill-admin' : 'pill-user' ?>">
                    <?= esc($role) ?>
                </span>
                <span class="text-white-50 d-none d-md-inline">Connecte: <?= esc($username) ?></span>
                <?php if ($role === 'admin'): ?>
                    <a href="/livres/create" class="btn btn-main btn-sm">Ajouter un livre</a>
                <?php endif; ?>
                <form method="post" action="/logout" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-soft btn-sm">Deconnexion</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container content-shell">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
