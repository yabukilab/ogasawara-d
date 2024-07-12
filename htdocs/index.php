<!-- index.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONlinebrary</title>
    <link rel="stylesheet" href="style.css?<?php date_default_timezone_set('Asia/Tokyo'); echo date("ymdHi",filemtime("style.css")); ?>">
</head>
<body>
    <div class="btn-container">
        <button class="btnreaf" onclick="location.href='login.php'">ログイン</button><br>
        <button class="btnreaf" onclick="location.href='loginnew.php'">新規登録</button><br>
    </div>
</html>
