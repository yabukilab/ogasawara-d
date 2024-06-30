<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン情報登録 - ONlinebrary</title>
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
            text-align: center;
        }
        .title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            padding: 10px;
            font-size: 16px;
            width: 200px;
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #357ABD;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
        .conditions {
            margin-bottom: 20px;
            color: #4A90E2;
        }
    </style>
</head>
<body>
    <div class="header">
    <button class="return-button" onclick="location.href='menu.php'">戻る</button>
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
                $servername = "localhost";
                $dbusername = "root";
                $dbpassword = "";
                $dbname = "librarydb";

                $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

                if ($conn->connect_error) {
                    die("データベース接続失敗: " . $conn->connect_error);
                }

                // ユーザー名の重複チェック
                $stmt = $conn->prepare("SELECT COUNT(*) FROM logininf WHERE user = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($user_count);
                $stmt->fetch();
                $stmt->close();

                if ($user_count > 0) {
                    $error = "このアカウントは既に存在しています。";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO logininf (user, password) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $hashed_password);

                    if ($stmt->execute()) {
                        echo "<script>alert('登録が完了しました。'); window.location.href = 'menu.php';</script>";
                    } else {
                        $error = "登録中にエラーが発生しました: " . $stmt->error;
                    }

                    $stmt->close();
                }

                $conn->close();
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
