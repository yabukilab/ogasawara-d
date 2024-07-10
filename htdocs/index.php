<!--index.php-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONlinebrary</title>
    <link rel="stylesheet" href="style.css?<?php date_default_timezone_set('Asia/Tokyo'); echo date("ymdHi",filemtime("style.css")); ?>">
</head>
<body>
    <div class="header">
        <h1>ONlinebrary</h1>
    </div>
    <div class="container">
    <div class="btn2">
        <button class="button" onclick="location.href='login.php'">ログイン</button><br>
        <button class="button" onclick="location.href='loginnew.php'">新規登録</button><br>
    </div>
    </div>
</body>
</html>
