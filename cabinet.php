<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Логотип" class="logo-img">
        </div>
        <nav>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Главная</a></li>
                <li><a href="logout.php" class="nav-link">Выход</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="cabinet-container">
        <h2>Личный кабинет</h2>
        <p>Добро пожаловать, <?php echo htmlspecialchars($role); ?>!</p>

        <?php if ($role == 'admin'): ?>
            <div class="admin-section">
                <h3>Администраторские функции</h3>
                <ul>
                    <li><a href="issues.php">Контроль устранения сбоев</a></li>
                    <li><a href="training.php">Систематическое обучение</a></li>
                    <li><a href="archives.php">Ведение архива</a></li>
                    <li><a href="equipment.php">Управление оборудованием</a></li>
                </ul>
            </div>
        <?php elseif ($role == 'user'): ?>
            <div class="user-section">
                <h3>Функции пользователя</h3>
                <ul>
                    <li><a href="issues.php">Создать заявку на устранение сбоев</a></li>
                    <li><a href="training.php">Просмотреть обучающие материалы</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
