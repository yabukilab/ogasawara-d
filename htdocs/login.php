<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT password FROM logininf WHERE user = ?");
    $stmt->execute([$username]);
    $hashed_password = $stmt->fetchColumn();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        $error_message = "ユーザー名またはパスワードが違います。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - ONlinebrary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <button class="return-button" onclick="location.href='index.php'">戻る</button>
        <h1>ログイン画面</h1>
    </div>
    <div class="container">
        <?php if (!empty($error_message)): ?>
            <div class='error'><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="ユーザー名" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="パスワード" required>
            </div>
            <button type="submit" class="button">ログイン</button>
        </form>
    </div>
</body>
</html>
