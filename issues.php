<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['description'])) {
    $description = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO issues (user_id, description, status) VALUES (?, ?, 'open')");
    $stmt->bind_param("is", $user_id, $description);
    $stmt->execute();
}

$issues = [];
if ($role == 'admin') {
    $result = $conn->query("SELECT issues.*, users.first_name, users.last_name FROM issues JOIN users ON issues.user_id = users.id");
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM issues WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контроль устранения сбоев</title>
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
        <h2>Контроль устранения сбоев</h2>
        <?php if ($role != 'admin'): ?>
        <form method="POST" action="issues.php" class="issue-form">
            <label for="description">Описание проблемы:</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit">Отправить</button>
        </form>
        <?php endif; ?>
        <h3>Список заявок:</h3>
        <table>
            <tr>
                <th>Пользователь</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Дата создания</th>
                <th>Последнее обновление</th>
                <?php if ($role == 'admin'): ?>
                <th>Действия</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($issues as $issue): ?>
            <tr>
                <td><?php echo htmlspecialchars($issue['first_name'] ?? 'Неизвестно') . ' ' . htmlspecialchars($issue['last_name'] ?? 'Неизвестно'); ?></td>
                <td><?php echo htmlspecialchars($issue['description']); ?></td>
                <td><?php echo htmlspecialchars($issue['status']); ?></td>
                <td><?php echo htmlspecialchars($issue['created_at']); ?></td>
                <td><?php echo htmlspecialchars($issue['updated_at']); ?></td>
                <?php if ($role == 'admin'): ?>
                <td>
                    <form method="POST" action="update_issue.php">
                        <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
                        <select name="status">
                            <option value="open" <?php if ($issue['status'] == 'open') echo 'selected'; ?>>Open</option>
                            <option value="in_progress" <?php if ($issue['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                            <option value="closed" <?php if ($issue['status'] == 'closed') echo 'selected'; ?>>Closed</option>
                        </select>
                        <button type="submit">Обновить</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
