<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト完了 - ONlinebrary</title>
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
        }
        .container {
            width: 90%;
            margin: 20px auto;
            text-align: center;
        }
        .title {
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
            display: inline-block;
        }
        .button:hover {
            background-color: #357ABD;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ログアウト画面</h1>
    </div>
    <div class="container">
        <div class="title">ログアウトしました!</div>
        <form action="menu.php" method="post">
            <button type="submit" class="button">メニューへ</button>
        </form>
    </div>
</body>
</html>
