<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <link rel='stylesheet' href='style.css' />
    <title>全データ表示</title>
  </head>
  <body>

<table>
  <caption>全データ</caption>
  <tr>
    <th>食材名</th>
    <th>g/個</th>
    <th>賞味期限</th>
  </tr>
<?php
require 'db.php';                               # 接続
$sql = 'SELECT * FROM food';                  # SQL文
$prepare = $db->prepare($sql);                  # 準備
$prepare->execute();                            # 実行
$result = $prepare->fetchAll(PDO::FETCH_ASSOC); # 結果の取得

foreach ($result as $row) {
  $name       = h($row['name']);
  $g = h($row['g']);
  $y = h($row['y']);
  $m = h($row['m']);
  $d = h($row['d']);
  echo '<tr>' .
    "<td>{$name}</td>".
    "<td>{$g}</td>".
    "<td>{$y}年{$m}月{$d}日</td>".
    '</tr>';
}
?>
</table>

  </body>
</html>