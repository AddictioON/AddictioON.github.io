<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_id']) && isset($_POST['status']) && $_SESSION['role'] == 'admin') {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE issues SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $issue_id);
    $stmt->execute();
}

header("Location: issues.php");
?>
