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
            margin: 20px auto;
            width: 80%;
        }
        .title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .image-container {
            text-align: center;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        .form-container {
            text-align: center;
            margin: 20px 0;
        }
        .form-container input[type="checkbox"] {
            margin: 10px;
        }
        .form-container textarea {
            width: 100%;
            height: 100px;
            margin: 20px 0;
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px;
        }
        .button:hover {
            background-color: #357ABD;
        }
    </style>
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
