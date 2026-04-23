<?php
session_start();

// 检查是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>学生信息列表</title>
    <style>
        body {
            font-family: Arial, "Microsoft YaHei", sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
        }
        .welcome {
            color: #667eea;
            font-size: 16px;
        }
        .logout-btn {
            padding: 8px 20px;
            background: #e53e3e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .logout-btn:hover {
            background: #c53030;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .count {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .error {
            color: #e53e3e;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📚 学生信息管理系统</h1>
    
    <div class="user-info">
        <span class="welcome">👤 欢迎，<?php echo htmlspecialchars($_SESSION['username']); ?>！</span>
        <button class="logout-btn" onclick="logout()">退出登录</button>
    </div>
    
    <?php
    // 连接学生数据库
    $link = new mysqli("localhost", "root", "", "school");
    
    if ($link->connect_error) {
        echo "<div class='error'>❌ 数据库连接失败: " . $link->connect_error . "</div>";
    } else {
        $link->set_charset("utf8mb4");
        
        $sql = "SELECT * FROM student ORDER BY id";
        $res = $link->query($sql);
        
        if ($res && $res->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>姓名</th><th>性别</th></tr>";
            
            while ($student = $res->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($student['id']) . "</td>";
                echo "<td>" . htmlspecialchars($student['realname']) . "</td>";
                echo "<td>" . htmlspecialchars($student['gender']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            echo "<div class='count'>📊 共 " . $res->num_rows . " 条记录</div>";
        } else {
            echo "<div class='error'>📭 暂无学生数据</div>";
        }
        
        $link->close();
    }
    ?>
</div>

<script>
    function logout() {
        if (confirm('确定要退出登录吗？')) {
            window.location.href = 'logout.php';
        }
    }
</script>
</body>
</html>