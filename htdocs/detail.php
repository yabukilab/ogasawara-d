<!--detail.php-->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$username = $_SESSION['username'];

$stmt = $db->prepare("SELECT id FROM logininf WHERE user = ?");
$stmt->execute([$username]);
$user_id = $stmt->fetchColumn();

$id = $_GET['id'];

$stmt = $db->prepare("SELECT `title`, `img`, `give`, `get`, `text`, `owner` FROM detail WHERE `id` = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

$show_data = ($book['owner'] == $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $show_data) {
    $give = isset($_POST['give']) ? 1 : 0;
    $get = isset($_POST['get']) ? 1 : 0;
    $text = $_POST['text'];

    $stmt = $db->prepare("UPDATE detail SET give = ?, get = ?, text = ? WHERE id = ?");
    $stmt->execute([$give, $get, $text, $id]);

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
    <link rel="stylesheet" href="style.css?<?php date_default_timezone_set('Asia/Tokyo'); echo date("ymdHi",filemtime("style.css")); ?>">
</head>
<body>
    <div class="header">
        <h1><?php echo htmlspecialchars($book['title']); ?> - 詳細</h1>
    </div>
    <div>
        <button class="return-button" onclick="location.href='home.php'">戻る</button>
    </div>
    <div class="container">
        <?php if ($show_data): ?>
            <div class="image-container">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($book['img']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="responsive-img center-img">
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
                    <div class="right-align">
                        <button type="submit" class="onebtn">更新</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <p>この情報を表示する権限がありません。</p>
        <?php endif; ?>
    </div>
</body>
</html>


