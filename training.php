<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['description'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO training (title, description, date, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $date, $user_id);
    $stmt->execute();
}

$trainings = [];
$result = $conn->query("SELECT training.*, users.first_name, users.last_name FROM training JOIN users ON training.user_id = users.id");
while ($row = $result->fetch_assoc()) {
    $trainings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Систематическое обучение</title>
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
                <li><a href="cabinet.php" class="nav-link">Личный кабинет</a></li>
                <li><a href="logout.php" class="nav-link">Выход</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="content-container">
        <h2>Систематическое обучение</h2>
        <?php if ($role == 'admin'): ?>
        <form method="POST" action="training.php" class="training-form">
            <label for="title">Название:</label>
            <input type="text" id="title" name="title" required>
            <label for="description">Описание:</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit">Добавить</button>
        </form>
        <?php endif; ?>
        <h3>Список обучающих материалов:</h3>
        <table>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Дата</th>
                <th>Автор</th>
            </tr>
            <?php foreach ($trainings as $training): ?>
            <tr>
                <td><?php echo htmlspecialchars($training['title']); ?></td>
                <td><?php echo htmlspecialchars($training['description']); ?></td>
                <td><?php echo htmlspecialchars($training['date']); ?></td>
                <td><?php echo htmlspecialchars($training['first_name'] . ' ' . $training['last_name']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
