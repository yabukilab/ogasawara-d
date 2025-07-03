<?php
require 'db.php';

// 楽天APIアプリID（必ず自分のIDに置き換えてください）
$applicationId = '1016089100424721277';

// フォーム処理
$ingredient = $_GET['ingredient'] ?? '';
$recipes = [];

if ($ingredient !== '') {
    try {

        // カテゴリIDをDBから取得
        $sql = 'SELECT rakuten_category_id FROM ingredient_categories WHERE ingredient_name = :ingredient';
        $prepare = $db->prepare($sql);
        $prepare->execute([':ingredient' => $ingredient]);
        $categoryId = $prepare->fetchColumn();

        if ($categoryId) {
            // 楽天レシピAPIを呼び出す（カテゴリランキング）
            $apiUrl = 'https://app.rakuten.co.jp/services/api/Recipe/CategoryRanking/20170426';
            $params = [
                'applicationId' => $applicationId,
                'categoryId' => $categoryId,
                'format' => 'json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl . '?' . http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 本番では true 推奨
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (!isset($data['error'])) {
                $recipes = $data['result'];
            } else {
                $errorMessage = "楽天APIエラー: " . htmlspecialchars($data['error_description'] ?? '詳細不明');
            }
        } else {
            $errorMessage = "「{$ingredient}」に対応するカテゴリが見つかりませんでした。";
        }

    } catch (Exception $e) {
        $errorMessage = "エラーが発生しました: " . $e->getMessage();
    }
}
?>

<!-- HTML表示部分 -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レシピ検索</title>
</head>
<body>
    <h1>楽天レシピ検索（カテゴリ連携）</h1>

    <form method="get" action="">
        <label>食材名：</label>
        <input type="text" name="ingredient" value="<?= htmlspecialchars($ingredient) ?>" required>
        <button type="submit">検索</button>
    </form>

    <?php if (!empty($errorMessage)): ?>
        <p style="color:red;"><?= $errorMessage ?></p>
    <?php endif; ?>

    <?php if (!empty($recipes)): ?>
        <h2>「<?= htmlspecialchars($ingredient) ?>」の人気レシピ</h2>
        <?php foreach ($recipes as $recipe): ?>
            <div style="border:1px solid #ccc; padding:10px; margin:10px;">
                <h3><?= htmlspecialchars($recipe['recipeTitle']) ?></h3>
                <p><img src="<?= htmlspecialchars($recipe['foodImageUrl']) ?>" width="200"></p>
                <p><a href="<?= htmlspecialchars($recipe['recipeUrl']) ?>" target="_blank">レシピを見る</a></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
