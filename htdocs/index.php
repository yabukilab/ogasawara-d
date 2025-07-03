<?php
session_start();

require 'db.php';          // データベースパスワード（XAMPPの場合は通常空）

$message = '';
$messageType = 'default';

$sql = 'SELECT * FROM users';
$prepare = $db->prepare($sql);
$prepare->execute();
$users = $prepare->fetchAll(PDO::FETCH_ASSOC);
// ログイン処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user_id'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
        $message = 'ユーザ名とパスワードを入力してください．';
        $messageType = 'error';
    } else {
        // ユーザー認証
        $user = null;
        foreach ($users as $u) {
            if ($u['user_id'] === $username && $u['password'] === $password) {
                $user = $u;
                break;
            }
        }
        
        if ($user) {
            // ログイン成功
            $_SESSION['user_id'] = $user['user_id'];
            
            $message = 'ログイン成功！';
            $messageType = 'success';
                header('Location: home.php');
                exit();
        } else {
            // ログイン失敗
            $message = 'ユーザ名またはパスワードが違います．';
            $messageType = 'error';
        }
    }
}

// デフォルトメッセージ
if (empty($message)) {
    $message = '⇓ IDとパスワードを入力 ⇓';
    $messageType = 'default';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8' />
    <title>ログイン</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><br>冷蔵庫内管理＆</br>レシピ考案システム</h2>
        <div id="message" class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        
        <form id="loginForm" method="post" action="">
            <ul>
                <li><input type="text" id="user_id" name="user_id" placeholder="ユーザ名" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" /></li>
                <li><input type="password" id="password" name="password" placeholder="パスワード" required /></li>
                <li><input type="submit" value="ログイン" /></li>
            </ul>
        </form>
    </div>
    <div class="form-links">
        <a href="akaunt.php">新規登録はこちら</a>
    </div>
</body>
</html>