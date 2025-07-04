<?php
$applicationId = '1016089100424721277'; // ご自身の楽天アプリID

// 楽天カテゴリ取得関数
function fetchCategories($type = '', $parentId = '') {
    global $applicationId;
    $baseUrl = 'https://app.rakuten.co.jp/services/api/Recipe/CategoryList/20170426';
    $params = [
        'applicationId' => $applicationId,
        'format' => 'json'
    ];
    if ($type) $params['categoryType'] = $type;
    if ($parentId) $params['categoryId'] = $parentId;

    $url = $baseUrl . '?' . http_build_query($params);
    $response = @file_get_contents($url);
    if (!$response) return [];
    $data = json_decode($response, true);
    $key = $type ?: 'large';
    return $data['result'][$key] ?? [];
}

// 各カテゴリの取得
$largeCategories = fetchCategories();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>楽天レシピカテゴリ検索</title>
    <style>
        body { font-family: sans-serif; padding: 2em; background-color: #f0fff0; }
        select, label { margin: 1em 0; display: block; }
        .recipe { display: flex; margin: 1em 0; padding: 1em; background: #fff; border-radius: 8px; }
        .recipe img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; margin-right: 1em; }
    </style>
</head>
<body>
    <h2>カテゴリを選んでレシピを表示</h2>

    <label>大カテゴリ：
        <select id="largeCategory">
            <option value="">-- 選択してください --</option>
            <?php foreach ($largeCategories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['categoryId']) ?>"><?= htmlspecialchars($cat['categoryName']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>中カテゴリ：
        <select id="mediumCategory" disabled></select>
    </label>

    <label>小カテゴリ：
        <select id="smallCategory" disabled></select>
    </label>

    <div id="recipes"></div>

    <script>
        const appId = '<?= $applicationId ?>';

        const mediumSel = document.getElementById('mediumCategory');
        const smallSel = document.getElementById('smallCategory');
        const recipeDiv = document.getElementById('recipes');

        document.getElementById('largeCategory').addEventListener('change', function () {
            const largeId = this.value;
            mediumSel.innerHTML = '';
            smallSel.innerHTML = '';
            recipeDiv.innerHTML = '';
            if (!largeId) return;
            fetch(`https://app.rakuten.co.jp/services/api/Recipe/CategoryList/20170426?format=json&applicationId=${appId}&categoryType=medium&categoryId=${largeId}`)
                .then(res => res.json())
                .then(data => {
                    const mediums = data.result.medium || [];
                    mediumSel.disabled = false;
                    mediumSel.innerHTML = '<option value="">-- 選択してください --</option>';
                    mediums.forEach(c => {
                        mediumSel.innerHTML += `<option value="${c.categoryId}">${c.categoryName}</option>`;
                    });
                });
        });

        mediumSel.addEventListener('change', function () {
            const mediumId = this.value;
            smallSel.innerHTML = '';
            recipeDiv.innerHTML = '';
            if (!mediumId) return;
            fetch(`https://app.rakuten.co.jp/services/api/Recipe/CategoryList/20170426?format=json&applicationId=${appId}&categoryType=small&categoryId=${mediumId}`)
                .then(res => res.json())
                .then(data => {
                    const smalls = data.result.small || [];
                    smallSel.disabled = false;
                    smallSel.innerHTML = '<option value="">-- 選択してください --</option>';
                    smalls.forEach(c => {
                        smallSel.innerHTML += `<option value="${c.categoryId}">${c.categoryName}</option>`;
                    });
                });
        });

        smallSel.addEventListener('change', function () {
            const smallId = this.value;
            recipeDiv.innerHTML = '';
            if (!smallId) return;
            fetch(`https://app.rakuten.co.jp/services/api/Recipe/CategoryRanking/20170426?format=json&formatVersion=2&applicationId=${appId}&categoryId=${smallId}`)
                .then(res => res.json())
                .then(data => {
                    const recipes = data.result || [];
                    if (recipes.length === 0) {
                        recipeDiv.innerHTML = '<p>レシピが見つかりませんでした。</p>';
                        return;
                    }
                    recipes.forEach(r => {
                        recipeDiv.innerHTML += `
                            <div class="recipe">
                                <img src="${r.foodImageUrl}" alt="${r.recipeTitle}">
                                <div>
                                    <h4>${r.recipeTitle}</h4>
                                    <p>時間: ${r.recipeIndication}</p>
                                    <p>費用: ${r.recipeCost}</p>
                                    <a href="${r.recipeUrl}" target="_blank">▶ 作り方を見る</a>
                                </div>
                            </div>`;
                    });
                });
        });
    </script>
</body>
</html>