<?php
require 'db.php';
checkRole('admin');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Логотип" class="logo-img">
        </div>
        <nav>
            <ul class="nav-menu">
                <li><a href="index.html" class="nav-link">Главная</a></li>
                <li><a href="logout.php" class="nav-link">Выход</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="admin-container">
        <h2 class="admin-title">Панель администратора</h2>
        <p>Добро пожаловать, администратор!</p>
        <!-- Здесь будет функционал панели администратора -->
    </div>
</body>
</html>
