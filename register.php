<?php
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => '数据库连接失败'
    ]));
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 验证
if (strlen($username) < 3) {
    die(json_encode(['success' => false, 'message' => '用户名至少3个字符']));
}
if (strlen($password) < 3) {
    die(json_encode(['success' => false, 'message' => '密码至少3个字符']));
}

// 检查用户名是否存在
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => '用户名已存在']);
    exit;
}
$stmt->close();

// 插入新用户
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '注册成功']);
} else {
    echo json_encode(['success' => false, 'message' => '注册失败']);
}

$stmt->close();
$conn->close();
?>