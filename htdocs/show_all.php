<!--show_all.php-->
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
    <link rel="stylesheet" href="style.css?<?php date_default_timezone_set('Asia/Tokyo'); echo date("ymdHi",filemtime("style.css")); ?>">
    <script>
        function toggleMenu() {
            const buttons = document.querySelector('.buttons');
            buttons.classList.toggle('show');
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>画像を押して<br>消したいものを選択</h1>
        <button class="menu-button" onclick="toggleMenu()">メニュー</button>
        <div class="containerbtn">
            <div class="buttons menu-buttons">
                <button class="button" onclick="location.href='home.php'">ホームへ</button>
                <form method="post" action="show_all.php" style="display:inline;">
                    <button type="submit" class="button" name="sort_order" value="asc">名前昇順</button>
                    <button type="submit" class="button" name="sort_order" value="desc">名前降順</button>
                </form>
                <form method="post" action="delete_item.php" style="display:inline;">
                    <button type="submit" class="button">チェックしてここをプッシュ</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="grid-container">
            <?php foreach ($books as $book): ?>
                <div class="book-itemNot">
                    <label class="book-item-label">
                        <input type="checkbox" name="chk[]" value="<?php echo $book['id']; ?>" class="checkboxNot">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($book['img']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <div class="book-item-titleNot"><?php echo htmlspecialchars($book['title']); ?></div>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
