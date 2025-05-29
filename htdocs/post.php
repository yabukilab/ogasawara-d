<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <link rel='stylesheet' href='style.css' />
    <title>食材追加完了</title>
  </head>
  <body>
<?php
$name = $_POST['name']; # 食材名
$g = $_POST['g']; # g/個
$y = $_POST['y'];
$m= $_POST['m'];
$d = $_POST['d']; # 賞味期限
$id = $_POST['id'];

require 'db.php'; # 接続
$sql ='insert into food (name, g, y,m,d,id) values (:name, :g, :y,:m,:d,:id)';
$prepare = $db->prepare($sql); # 準備

$prepare->bindValue(':name', $name, PDO::PARAM_STR); # 埋め込み1
$prepare->bindValue(':g', $g, PDO::PARAM_STR);         # 埋め込み2
$prepare->bindValue(':y', $y, PDO::PARAM_STR);         # 埋め込み3
$prepare->bindValue(':m', $m, PDO::PARAM_STR);
$prepare->bindValue(':d', $d, PDO::PARAM_STR);
$prepare->bindValue(':id', $id, PDO::PARAM_STR);

$prepare->execute(); # 実行
?>
  </body>
</html>