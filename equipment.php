<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['quantity'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $status = 'in_stock';
    $stmt = $conn->prepare("INSERT INTO equipment (name, quantity, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $quantity, $status);
    $stmt->execute();
}

$equipment_list = [];
$result = $conn->query("SELECT * FROM equipment");
while ($row = $result->fetch_assoc()) {
    $equipment_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление оборудованием</title>
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
        <h2>Управление оборудованием</h2>
        <?php if ($role == 'admin'): ?>
        <form method="POST" action="equipment.php" class="equipment-form">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required>
            <label for="quantity">Количество:</label>
            <input type="number" id="quantity" name="quantity" required>
            <button type="submit">Добавить</button>
        </form>
        <?php endif; ?>
        <h3>Список оборудования:</h3>
        <table>
            <tr>
                <th>Название</th>
                <th>Количество</th>
                <th>Статус</th>
                <th>Дата получения</th>
            </tr>
            <?php foreach ($equipment_list as $equipment): ?>
            <tr>
                <td><?php echo htmlspecialchars($equipment['name']); ?></td>
                <td><?php echo htmlspecialchars($equipment['quantity']); ?></td>
                <td><?php echo htmlspecialchars($equipment['status']); ?></td>
                <td><?php echo htmlspecialchars($equipment['received_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
