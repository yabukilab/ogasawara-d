<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// データベース接続情報
require 'db.php';

// ログインしているユーザーの ID を取得
$username = $_SESSION['username'];
$stmt = $db->prepare("SELECT id FROM logininf WHERE user = ?");
$stmt->execute([$username]);
$user_id = $stmt->fetchColumn();

// 並び替えオプションを取得
$order = "title ASC";
if (isset($_POST['sort_order'])) {
    if ($_POST['sort_order'] == 'asc') {
        $order = "title ASC";
    } elseif ($_POST['sort_order'] == 'desc') {
        $order = "title DESC";
    }
}

// タイトルと画像を取得
$stmt = $db->prepare("SELECT id, title, img FROM detail WHERE owner = ? ORDER BY $order");
$stmt->execute([$user_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>全データ表示（削除） - ONlinebrary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <button class="return-button" onclick="location.href='home.php'">戻る</button>
        <h1>削除したいデータの左上にチェックを入れ、削除を押してください</h1>
    </div>
    <div class="container">
        <div class="buttons">
            <form method="post" action="show_all.php" style="display:inline;">
                <button type="submit" class="button" name="sort_order" value="asc">名前昇順</button>
                <button type="submit" class="button" name="sort_order" value="desc">名前降順</button>
            </form>
            <form method="post" action="delete_item.php" style="display:inline;">
                <button type="submit" class="button">削除</button>
        </div>
        <div class="grid-container">
            <?php foreach ($books as $book): ?>
                <div class="book-item">
                    <input type="checkbox" name="chk[]" value="<?php echo $book['id']; ?>" class="checkbox">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($book['img']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <div class="book-item-title"><?php echo htmlspecialchars($book['title']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        </form>
    </div>
</body>
</html>
