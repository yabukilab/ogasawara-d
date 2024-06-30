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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: relative;
        }
        .header .return-button {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            background-color: white;
            color: #4A90E2;
            border: 2px solid #fff;
            padding: 10px 20px;
            margin-left: 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .header .return-button:hover {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #fff;

        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            justify-items: center;
        }
        @media (min-width: 1200px) {
            .grid-container {
                grid-template-columns: repeat(7, 1fr);
            }
        }
        @media (max-width: 1199px) and (min-width: 900px) {
            .grid-container {
                grid-template-columns: repeat(5, 1fr);
            }
        }
        @media (max-width: 899px) {
            .grid-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .book-item {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            background-color: #000;
            position: relative;
        }
        .book-item img {
            max-width: 100%;
            height: auto;
            cursor: default; /* クリックできないようにする */
        }
        .book-item-title {
            margin-top: 10px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            cursor: default; /* クリックできないようにする */
        }
        .buttons {
            text-align: center;
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
        }
        .button:hover {
            background-color: #357ABD;
        }
        .checkbox {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 20px;
            height: 20px;
            transform: scale(1.5); /* チェックボックスのサイズを大きくする */
        }
    </style>
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
