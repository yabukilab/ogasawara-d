<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>ホーム画面</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <h2><br>冷蔵庫内管理＆</br>レシピ考案システム</h2>

  <div class="button-group">
    <a href="post.php" class="btn">食材リストにポチッと登録！</a>
    <a href="recipe_delete.php" class="btn">食材コレクション！</a>
    <a href="recipe.php" class="btn">献立！</a>

    <form action="logout.php" method="post">
      <button type="submit" class="btn">ログアウト</button>
    </form>
  </div>

</div>

</body>
</html>