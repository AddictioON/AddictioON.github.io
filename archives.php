<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['description'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO archives (name, description, date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $date);
    $stmt->execute();
}

$archives = [];
$result = $conn->query("SELECT * FROM archives");
while ($row = $result->fetch_assoc()) {
    $archives[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ведение архива</title>
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
        <h2>Ведение архива</h2>
        <?php if ($role == 'admin'): ?>
        <form method="POST" action="archives.php" class="archive-form">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Описание:</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit">Добавить</button>
        </form>
        <?php endif; ?>
        <h3>Список архивных записей:</h3>
        <table>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Дата</th>
            </tr>
            <?php foreach ($archives as $archive): ?>
            <tr>
                <td><?php echo htmlspecialchars($archive['name']); ?></td>
                <td><?php echo htmlspecialchars($archive['description']); ?></td>
                <td><?php echo htmlspecialchars($archive['date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
