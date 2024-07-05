<!--loginnew.php-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン情報登録 - ONlinebrary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <button class="return-button" onclick="location.href='index.php'">戻る</button>
        <h1>ログイン情報登録画面</h1>
    </div>
    <div class="container">
        <div class="conditions">
            パスワード条件：<br>
            ・入力できるのは全て半角英数字の大文字と小文字のみ<br>
            ・入力できる文字数は4桁～15桁まで
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $error = "";

            if ($password !== $confirm_password) {
                $error = "パスワードが一致しません。";
            } elseif (!preg_match('/^[a-zA-Z0-9]{4,15}$/', $password)) {
                $error = "パスワード条件に合致していません。";
            } else {
                require 'db.php';

                $stmt = $db->prepare("SELECT COUNT(*) FROM logininf WHERE user = ?");
                $stmt->execute([$username]);
                $user_count = $stmt->fetchColumn();

                if ($user_count > 0) {
                    $error = "このアカウントは既に存在しています。";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO logininf (user, password) VALUES (?, ?)");

                    if ($stmt->execute([$username, $hashed_password])) {
                        echo "<script>alert('登録が完了しました。'); window.location.href = 'index.php';</script>";
                    } else {
                        $error = "登録中にエラーが発生しました: " . $stmt->errorInfo()[2];
                    }
                }
            }

            if ($error) {
                echo "<div class='error'>$error</div>";
            }
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="ユーザー名" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="パスワード" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="パスワード確認" required>
            </div>
            <button type="submit" class="button">登録</button>
        </form>
    </div>
</body>
</html>
