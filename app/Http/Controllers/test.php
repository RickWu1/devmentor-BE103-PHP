<?php
$dsn  = 'mysql:host=127.0.0.1;dbname=devmentor-be103-php-mysql-1;charset=utf8mb4';
$user = 'root';
$pass = 'your_password';

try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "数据库连接成功！";
} catch (PDOException $e) {
    echo "数据库连接失败：" . $e->getMessage();
}
