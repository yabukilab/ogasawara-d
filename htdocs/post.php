<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

require 'db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = $_POST['name'] ?? '';
    $g      = $_POST['g'] ?? '';
    $kigen  = $_POST['kigen'] ?? '';

    if ($name === '' || $g === '' || $kigen === '') {
        $errors[] = 'すべての項目を入力してください。';
    } elseif (!is_numeric($g)) {
        $errors[] = '量は数値で入力してください。';
    }

    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO food (ID, name, g, kigen, user_id)
                    VALUES (NULL, :name, :g, :kigen, :user_id)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':g', $g, PDO::PARAM_STR);
            $stmt->bindValue(':kigen', $kigen, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->execute();

            $success = true;

            $name = '';
            $g = '';
            $kigen = '';
            $_POST = [];

        } catch (PDOException $e) {
            $errors[] = 'データベースエラー: ' . h($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>食材登録</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <h2>食材登録フォーム</h2>

  <?php if (!empty($errors)) : ?>
    <div class="message error">
      <?php foreach ($errors as $error) : ?>
        <p><?= h($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($success) : ?>
    <div class="message success">
      登録が完了しました！
    </div>
  <?php endif; ?>

  <form action="post.php" method="post" accept-charset="UTF-8">
    <input type="hidden" name="user_id" value="<?= h($user_id) ?>">

    <div class="table-container">
      <table border="1">
        <tr>
          <th>食材の名前</th>
          <td><input name="name" type="text" value="<?= h($name ?? '') ?>" /></td>
        </tr>
        <tr>
          <th>量(g/個)</th>
          <td><input name="g" type="text" value="<?= h($g ?? '') ?>" /></td>
        </tr>
        <tr>
          <th>賞味期限</th>
          <td><input name="kigen" type="date" value="<?= h($kigen ?? '') ?>" /></td>
        </tr>
      </table>
    </div>
    <div class="button-group">
      <input type="submit" class="btn" value="登録" />
    </div>
  </form>
  <div class="button-group">
    <form action="home.php" method="get">
      <button type="submit" class="btn">戻る</button>
    </form>
  </div>

</div>

</body>
</html>
