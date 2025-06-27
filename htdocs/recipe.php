<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

require 'db.php';

// メッセージ表示
if (!empty($_SESSION['message'])) {
    echo "<p style='color:green'>" . h($_SESSION['message']) . "</p>";
    unset($_SESSION['message']);
}

// foodテーブルから全データ取得
$sql = 'SELECT * FROM food ORDER BY kigen ASC';
$prepare = $db->prepare($sql);
$prepare->execute();
$result = $prepare->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="style.css" />
  <title>レシピ画面</title>
</head>
<body>

<div class="container">
  <h2>レシピ検索</h2>

  <div class="table-container">
    <form action="API.php" method="post">
      <table border="1">
        <tr>
          <th>食材名</th>
          <th>g/個</th>
          <th>賞味期限</th>
          <th>選択</th>
        </tr>

        <?php
        foreach ($result as $index => $row) {
            if ($row['user_id'] == $user_id) {
                echo '<tr>';
                echo '<td>' . h($row['name']) . '</td>';
                echo '<td>' . h($row['g']) . '</td>';
                echo '<td>' . h($row['kigen']) . '</td>';
                echo '<td>';
                echo '<input type="radio" name="selected" value="' . $index . '">';
                echo '<input type="hidden" name="data[' . $index . '][name]" value="' . h($row['name']) . '">';
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
      </table>
      <div class="button-group">
        <input type="submit" value="作る！" class="btn" />
      </div>
    </form>
  </div>
  <div class="button-group">
    <form action="home.php" method="get">
      <button type="submit" class="btn">戻る</button>
    </form>
  </div>

</div>

</body>
</html>