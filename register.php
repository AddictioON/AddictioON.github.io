<?php
require 'db.php'; // Подключение к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $passport = $_POST['passport'];
    $phone = $_POST['phone'];
    $birth_date = $_POST['birth_date'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    // Проверка пароля
    if ($_POST['password'] !== $_POST['confirm_password']) {
        echo "Пароли не совпадают";
        exit;
    }

    // Вставка нового пользователя в базу данных
    $stmt = $conn->prepare("INSERT INTO users (last_name, first_name, middle_name, passport, phone, birth_date, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $last_name, $first_name, $middle_name, $passport, $phone, $birth_date, $password, $role);
    $stmt->execute();

    // Получение ID нового пользователя
    $user_id = $stmt->insert_id;

    // Сохранение сессии и перенаправление в личный кабинет
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;
    header("Location: cabinet.php");
}
?>
