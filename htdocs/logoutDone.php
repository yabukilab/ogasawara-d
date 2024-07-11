<!--logoutDone.php-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト完了 - ONlinebrary</title>
        <link rel="stylesheet" href="style.css?<?php date_default_timezone_set('Asia/Tokyo'); echo date("ymdHi",filemtime("style.css")); ?>">
</head>
<body>
    <div class="header">
        <h1>ログアウト画面</h1>
    </div>
    <div class="container">
        <div class="title">ログアウトしました!</div>
        <form action="index.php" method="post">
        <div class="right-align">
    <button type="submit" class="onebtn">メニューへ</button>
</div>
        </form>
    </div>
</body>
</html>
