<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONlinebrary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 10px 0 10px 0;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .buttonA {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080;
            padding: 30px 30px;
            font-size: 16px;
            margin: 20px 0;
            cursor: pointer;
            width: 200px;
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080;
            padding: 20px 30px;
            font-size: 16px;
            margin: 20px 0;
            cursor: pointer;
            width: 200px;
        }
        .button:hover {
            background-color: #357ABD;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ONlinebrary</h1>
    </div>
    <div class="container">
        <button class="buttonA" onclick="location.href='login.php'">ログイン</button><br>
        <button class="button" onclick="location.href='loginnew.php'">ログイン<br>情報登録</button><br>
        <button class="button" onclick="location.href='search_all.php'">データベース検索</button>
    </div>
</body>
</html>
