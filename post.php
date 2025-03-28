<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $category_id, $title, $content]);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Post - Kratos.lua Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Create a New Post</h1>
    <form method="POST">
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Category: 
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Content: <textarea name="content" required></textarea></label><br>
        <button type="submit">Post</button>
    </form>
    <a href="index.php">Back to Forum</a>
</body>
</html>