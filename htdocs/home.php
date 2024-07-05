<!--home.php-->
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
    <title>ホーム - ONlinebrary</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function sortBooks(order) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'home.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sort_order';
            input.value = order;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>ONlinebrary</h1>
    </div>
    <div class="container">
        <div class="buttons">
            <button class="button" onclick="location.href='logout.php'">ログアウト</button>
            <button class="button" onclick="location.href='imgimport.php'">書籍登録</button>
            <button class="button" onclick="sortBooks('asc')">名前昇順</button>
            <button class="button" onclick="sortBooks('desc')">名前降順</button>
            <button class="button" onclick="location.href='show_all.php'">書籍削除</button>
        </div>
        <div class="grid-container">
            <?php foreach ($books as $book): ?>
                <div class="book-item">
                    <a href="detail.php?id=<?php echo $book['id']; ?>">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($book['img']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <div class="book-item-title"><?php echo htmlspecialchars($book['title']); ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
