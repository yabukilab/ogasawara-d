<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// データベース接続情報
require 'db.php';

// ユーザー名を取得
$username = $_SESSION['username'];

// ログインしているユーザーの ID を取得
$stmt = $db->prepare("SELECT id FROM logininf WHERE user = ?");
$stmt->execute([$username]);
$user_id = $stmt->fetchColumn();

// IDを取得
$id = $_GET['id'];

// データを取得
$stmt = $db->prepare("SELECT title, img, give, get, text, owner FROM detail WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

// データを表示するかどうかのフラグ
$show_data = ($book['owner'] == $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $show_data) {
    $give = isset($_POST['give']) ? 1 : 0;
    $get = isset($_POST['get']) ? 1 : 0;
    $text = $_POST['text'];

    $stmt = $db->prepare("UPDATE detail SET give = ?, get = ?, text = ? WHERE id = ?");
    $stmt->execute([$give, $get, $text, $id]);

    // 更新後のデータを再取得
    $stmt = $db->prepare("SELECT title, img, give, get, text, owner FROM detail WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
}

$db = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - 詳細</title>
</head>
<body>
    <div class="header">
    <button class="return-button" onclick="location.href='home.php'">戻る</button>
        <h1><?php echo htmlspecialchars($book['title']); ?> - 詳細</h1>
    </div>
    <div class="container">
        <?php if ($show_data): ?>
            <div class="image-container">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($book['img']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            </div>
            <form method="post" action="">
                <div class="form-container">
                    <label>
                        <input type="checkbox" name="give" <?php if ($book['give']) echo "checked"; ?>> 貸出中
                    </label>
                    <label>
                        <input type="checkbox" name="get" <?php if ($book['get']) echo "checked"; ?>> 借入中
                    </label>
                </div>
                <div class="form-container">
                    <textarea name="text"><?php echo htmlspecialchars($book['text']); ?></textarea>
                </div>
                <div class="form-container">
                    <button type="submit" class="button">更新</button>
                </div>
            </form>
        <?php else: ?>
            <p>この情報を表示する権限がありません。</p>
        <?php endif; ?>
    </div>
</body>
</html>
