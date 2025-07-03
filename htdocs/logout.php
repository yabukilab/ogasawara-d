<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
  <title>ログアウト</title>
</head>
<body>

<div class="container">
  <h2>ログアウトしました</h2>
  <p style="text-align: center; font-size: 18px; margin-top: 20px;">
    またのご利用をお待ちしています。
  </p>

  <div class="button-group">
    <a href="index.php" class="btn">ログインページへ</a>
  </div>
</div>

</body>
</html>