<?php
session_start();
require 'db_config.php';

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);

// Fetch categories and recent posts
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$posts = $pdo->query("SELECT p.*, u.username, c.name AS category_name 
                      FROM posts p 
                      JOIN users u ON p.user_id = u.id 
                      JOIN categories c ON p.category_id = c.id 
                      ORDER BY p.created_at DESC 
                      LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kratos.lua Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Kratos.lua Forum</h1>
        <nav>
            <?php if ($logged_in): ?>
                <a href="post.php">New Post</a>
                <?php if ($_SESSION['is_admin']): ?>
                    <a href="admin.php">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Categories</h2>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li><?php echo htmlspecialchars($category['name']); ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>Recent Posts</h2>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <small>Posted by <?php echo htmlspecialchars($post['username']); ?> 
                       in <?php echo htmlspecialchars($post['category_name']); ?> 
                       on <?php echo $post['created_at']; ?></small>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
