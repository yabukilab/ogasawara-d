<?php
session_start();

require 'db.php';

$error = '';
$success = ''; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $user_id = trim($_POST['user_id'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // バリデーション（修正済み）
    if (empty($user_id) || empty($password) || empty($confirm_password)) {
        $error = 'すべての項目を入力してください。';
    } elseif (strlen($user_id) < 3 || strlen($user_id) > 20) {  
        $error = 'ユーザーIDは3文字以上20文字以下で入力してください。';
    } elseif (strlen($password) < 6) {  
        $error = 'パスワードは6文字以上で入力してください。';
    } elseif ($password !== $confirm_password) {
        $error = 'パスワードが一致しません。';
    } else {
        try {
            // ユーザーIDの重複チェック
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            if ($stmt->fetchColumn() > 0) {
                $error = 'このユーザーIDは既に使用されています。';
            }else{
                // パスワードをハッシュ化
               
                
                // ユーザー登録
                $stmt = $db->prepare("INSERT INTO users (user_id, password) VALUES (?, ?)");
                $stmt->execute([$user_id, $password]);
                
                $success = '登録が完了しました。ログイン画面からログインしてください。';
            }
        } catch (PDOException $e) {
            $error = 'データベースエラーが発生しました: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント登録</title>
    <link rel='stylesheet' href='style.css' />
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>アカウント登録</h2>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>

                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <div class="form-links">
                    <a href="login.php">ログイン画面へ</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="input-group">
                        <label for="user_id">ユーザーID</label>
                        <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($_POST['user_id'] ?? ''); ?>" required>
                        <small>3文字以上20文字以下で入力してください</small>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">パスワード</label>
                        <input type="password" id="password" name="password" required>
                        <small>6文字以上で入力してください</small>
                    </div>
                    
                    <div class="input-group">
                        <label for="confirm_password">パスワード（確認）</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn">登録!!</button>
                </form>
                
                <div class="form-links">
                    <a href="login.php">-すでにアカウントをお持ちの方はこちら-</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>