<?php
session_start();
require_once 'db.php';

$applicationId = $_SERVER['API_KEY'];

if (isset($_POST['selected']) && isset($_POST['data'])) {
    $index = $_POST['selected'];
    if (isset($_POST['data'][$index]['name'])) {
        $name = $_POST['data'][$index]['name'];
    } else {
        $_SESSION['message'] = "削除するデータが選択されていません。";
        header('Location: recipe.php');
        exit;
    }
} else {
    $_SESSION['message'] = "削除するデータが選択されていません。";
    header('Location: recipe.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>レシピ結果</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <h2>レシピ検索結果</h2>

<?php if (!empty($error_message)) : ?>
  <div class="message error">
    <?= htmlspecialchars($error_message) ?>
  </div>

  <div class="button-group">
    <form action="recipe.php" method="get">
      <button type="submit" class="btn">◀ 食材一覧に戻る</button>
    </form>
  </div>


<?php else :

    // DBからカテゴリIDを取得
    $stmt = $db->prepare("SELECT rakuten_category_id FROM ingredient_categories WHERE ingredient_name = :name");
    $stmt->execute([':name' => $name]);
    $categoryId = $stmt->fetchColumn();

    if (!$categoryId) :
?>

  <div class="message error">
    申し訳ありません。データベースにカテゴリIDが登録されていません。
  </div>

<?php
    else :
        $params = [
            'applicationId' => $applicationId,
            'format'        => 'json',
            'categoryId'    => $categoryId,
        ];

        $url = 'https://app.rakuten.co.jp/services/api/Recipe/CategoryRanking/20170426?' . http_build_query($params);

$response = @file_get_contents($url);

if ($response !== false) {
    $http_status = 200;
    $data = json_decode($response, true);
?>

    <div class="message success">
      「<?= htmlspecialchars($name) ?>」の人気レシピを表示します！
    </div>

    <?php foreach ($data['result'] as $recipe) : ?>
      <div style="border: 1px solid #ccc; border-radius: 8px; padding: 15px; margin: 20px 0; display: flex; gap: 20px; align-items: flex-start;">
        <div>
          <a href="<?= htmlspecialchars($recipe['recipeUrl']) ?>" target="_blank" rel="noopener noreferrer">
            <img src="<?= htmlspecialchars($recipe['foodImageUrl']) ?>" alt="<?= htmlspecialchars($recipe['recipeTitle']) ?>" style="width: 150px; border-radius: 4px;">
          </a>
        </div>
        <div>
          <h3 style="margin-top: 0;">
            <a href="<?= htmlspecialchars($recipe['recipeUrl']) ?>" target="_blank" rel="noopener noreferrer">
              <?= htmlspecialchars($recipe['recipeTitle']) ?>
            </a>
          </h3>
          <?php if (!empty($recipe['recipeMaterial'])) : ?>
            <p><strong>主な材料：</strong></p>
            <ul style="padding-left: 20px;">
              <?php foreach ($recipe['recipeMaterial'] as $m) : ?>
                <li><?= htmlspecialchars($m) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php else : ?>
            <p>材料情報はありません。</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

<?php
} else {
?>

    <div class="message error">
      カテゴリ別レシピ取得に失敗しました（HTTPステータス: <?= htmlspecialchars($http_status) ?>）
    </div>

<?php
        }
    endif;
endif;
?>

  <div class="button-group">
    <form action="recipe.php" method="get">
      <button type="submit" class="btn">◀ 食材一覧に戻る</button>
    </form>
  </div>
</div>

</body>
</html>
