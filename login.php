<?php
session_start();
require 'db.php'; // Подключение к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $password = $_POST['password'];

    // Поиск пользователя в базе данных
    $stmt = $conn->prepare("SELECT * FROM users WHERE first_name = ?");
    $stmt->bind_param("s", $first_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: cabinet.php");
    } else {
        echo "Неверный логин или пароль";
    }
}
?>
