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
    echo "<p class='message success'>" . h($_SESSION['message']) . "</p>";
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
  <title>食材一覧</title>
  <link rel='stylesheet' href='style.css' />
</head>
<body>

<div class="container">
  <h2>食材一覧</h2>

  <div class="table-container">
    <form action="delete.php" method="post">
      <table border="1">
        <thead>
          <tr>
            <th>食材名</th>
            <th>g/個</th>
            <th>賞味期限</th>
            <th>選択</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($result as $index => $row) {
              if ($row['user_id'] == $user_id) {
                  echo '<tr>';
                  echo '<td>' . h($row['name']) . '</td>';
                  echo '<td>' . h($row['g']) . '</td>';
                  echo '<td>' . h($row['kigen']) . '</td>';
                  echo '<td>';
                  echo '<input type="checkbox" name="selected[' . $index . ']" value="1" />';
                  echo '<input type="hidden" name="data[' . $index . '][ID]" value="' . h($row['ID']) . '">';
                  echo '<input type="hidden" name="data[' . $index . '][name]" value="' . h($row['name']) . '">';
                  echo '<input type="hidden" name="data[' . $index . '][g]" value="' . h($row['g']) . '">';
                  echo '<input type="hidden" name="data[' . $index . '][kigen]" value="' . h($row['kigen']) . '">';
                  echo '<input type="hidden" name="data[' . $index . '][user_id]" value="' . h($row['user_id']) . '">';
                  echo '</td>';
                  echo '</tr>';
              }
          }
          ?>
        </tbody>
      </table>
      <div class="button-group">
        <input type="submit" class="btn" value="削除処理へ送信">
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