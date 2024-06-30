<?php
session_start();

// ユーザーが既にログインしている場合、home.phpにリダイレクトする
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

// エラーメッセージを表示するための変数
$error_message = "";

// POSTリクエストがあった場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // データベース接続情報
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "librarydb";

    // ユーザー名とパスワードを取得
    $username = $_POST['username'];
    $password = $_POST['password'];

    // データベースに接続
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // 接続をチェック
    if ($conn->connect_error) {
        die("データベース接続失敗: " . $conn->connect_error);
    }

    // SQLクエリを準備
    $stmt = $conn->prepare("SELECT password FROM logininf WHERE user = ?");
    $stmt->bind_param("s", $username);

    // クエリを実行
    $stmt->execute();
    $stmt->bind_result($hashed_password);

    $login_successful = false;

    // パスワードの検証
    while ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            $login_successful = true;
            break;
        }
    }

    if ($login_successful) {
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        $error_message = "ユーザー名またはパスワードが違います。";
    }

    // ステートメントと接続を閉じる
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - ONlinebrary</title>
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
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            padding: 10px;
            font-size: 16px;
            width: 200px;
            display: block;
            margin: 0 auto;
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        .button:hover {
           背景色が  #357ABD;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
    <button class="return-button" onclick="location.href='menu.php'">戻る</button>
        <h1>ログイン画面</h1>
    </div>
    <div class="container">
        <?php
        if (!empty($error_message)) {
            echo "<div class='error'>$error_message</div>";
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="ユーザー名" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="パスワード" required>
            </div>
            <button type="submit" class="button">ログイン</button>
        </form>
    </div>
</body>
</html>
