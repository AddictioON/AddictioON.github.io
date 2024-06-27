<?php
session_start();
require 'db.php';

// Проверка авторизации
$role = $_SESSION['role'] ?? 'guest';

// Обработка добавления новости
if ($role == 'admin' && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image'] ?? null;

    $stmt = $conn->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $image);
    $stmt->execute();
}

// Загрузка новостей
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
$news = [];
while ($row = $result->fetch_assoc()) {
    $news[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ООО «Новое дело»</title>
    <link rel="stylesheet" href="styles.css">
    <script src="main.js" defer></script>
</head>
<body>
    <!-- Шапка -->
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Логотип" class="logo-img">
        </div>
        <nav>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Главная</a></li>
                <li><a href="#about" class="nav-link">О нас</a></li>
                <li><a href="#news" class="nav-link">Новости</a></li>
                <li><a href="#contact" class="nav-link">Контакты</a></li>
                <?php if ($role == 'guest'): ?>
                    <li><a href="login.html" class="nav-link">Вход</a></li>
                    <li><a href="register.html" class="nav-link">Регистрация</a></li>
                <?php else: ?>
                    <li><a href="cabinet.php" class="nav-link">Личный кабинет</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Секция о фирме -->
    <section id="about" class="about-section">
        <h1 class="section-title">О нас</h1>
        <p>Фирма ООО «Новое дело» предоставляет образовательные услуги в сфере информационных технологий. Наши ключевые направления включают администрирование компьютерных сетей, создание образовательных курсов, разработку ПО и графического дизайна. Мы также поддерживаем Колледж АГУ, создавая для них программные компоненты и инструменты для учебных процессов.</p>
    </section>

    <!-- Новости -->
    <section id="news" class="news-section">
        <h2 class="section-title">Новости</h2>
        <div class="news-slider">
            <button class="news-nav prev">Prev</button>
            <div class="news-grid">
                <?php foreach ($news as $news_item): ?>
                <div class="news-item">
                    <?php 
                    $image_url = htmlspecialchars($news_item['image']); 
                    echo "<img src='{$image_url}' alt='Новость' class='news-img'>";
                    ?>
                    <h3><?php echo htmlspecialchars($news_item['title']); ?></h3>
                    <button class="news-link" onclick="openNews(<?php echo $news_item['id']; ?>)">Подробнее</button>
                    <div class="news-content" id="news-content-<?php echo $news_item['id']; ?>">
                        <?php echo nl2br(htmlspecialchars($news_item['content'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="news-nav next">Next</button>
        </div>
        <?php if ($role == 'admin'): ?>
        <div class="add-news">
            <h3>Добавить новость</h3>
            <form method="POST" action="index.php" class="add-news-form">
                <label for="title">Заголовок:</label>
                <input type="text" id="title" name="title" required>
                <label for="content">Содержание:</label>
                <textarea id="content" name="content" required></textarea>
                <label for="image">Изображение (URL):</label>
                <input type="text" id="image" name="image">
                <button type="submit">Добавить</button>
            </form>
        </div>
        <?php endif; ?>
    </section>

    <!-- Подвал -->
    <footer id="contact" class="footer">
        <div class="footer-container">
            <div class="contacts">
                <h3 class="footer-title">Контакты</h3>
                <p>Адрес: ул. Ленина 123</p>
                <p>Телефон: +7 (951) 426-77-70</p>
                <p>Email: primer@yandex.ru</p>
            </div>
            <div class="menu">
                <h3 class="footer-title">Меню</h3>
                <ul class="footer-menu">
                    <li><a href="#" class="footer-link">Главная</a></li>
                    <li><a href="#about" class="footer-link">О нас</a></li>
                    <li><a href="#news" class="footer-link">Новости</a></li>
                    <li><a href="#contact" class="footer-link">Контакты</a></li>
                    <?php if ($role == 'guest'): ?>
                        <li><a href="login.html" class="footer-link">Вход</а></ли>
                        <li><a href="register.html" class="footer-link">Регистрация</a></li>
                    <?php else: ?>
                        <li><a href="cabinet.php" class="footer-link">Личный кабинет</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
