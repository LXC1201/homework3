<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// 数据库连接
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => '数据库连接失败'
    ]));
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 查询用户
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        echo json_encode(['success' => true, 'message' => '登录成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '密码错误']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '用户不存在']);
}

$stmt->close();
$conn->close();
?>