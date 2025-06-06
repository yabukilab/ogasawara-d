<?php

$applicationId = '1016089100424721277';
$targetKeyword = '人気メニュー';

// 楽天カテゴリ一覧APIの基本URL（パラメータなし）
$baseUrl = 'https://app.rakuten.co.jp/services/api/Recipe/CategoryList/20170426?';

// カテゴリ一覧を取得する関数
function fetchCategoryList($applicationId, $type = '', $parentId = '') {

    $params = [
        'applicationId' => $applicationId,
        'format' => 'json',
    ];
    if ($type !== '') $params['categoryType'] = $type;
    if ($parentId !== '') $params['categoryId'] = $parentId;

    $url = $baseUrl . '?' . http_build_query($params);

    $response = file_get_contents($url);
    if ($response === false) return [];

    $data = json_decode($response, true);
    $key = $type ?: 'large';

    return $data['result'][$key] ?? [];
}

// 再帰的にカテゴリを探索する関数
function searchCategories($applicationId, $keyword) {
    $results = [];

    $largeCategories = fetchCategoryList($applicationId);
    foreach ($largeCategories as $large) {
        if (mb_strpos($large['categoryName'], $keyword) !== false) {
            $results[] = $large;
        }

        $mediumCategories = fetchCategoryList($applicationId, 'medium', $large['categoryId']);
        foreach ($mediumCategories as $medium) {
            if (mb_strpos($medium['categoryName'], $keyword) !== false) {
                $results[] = $medium;
            }

            $smallCategories = fetchCategoryList($applicationId, 'small', $medium['categoryId']);
            foreach ($smallCategories as $small) {
                if (mb_strpos($small['categoryName'], $keyword) !== false) {
                    $results[] = $small;
                }
            }
        }
    }

    return $results;
}

// 実行
$matchedCategories = searchCategories($applicationId, $targetKeyword);

// 結果表示
if (empty($matchedCategories)) {
    echo "キーワード '{$targetKeyword}' に一致するカテゴリは見つかりませんでした。";
} else {
    echo "キーワード '{$targetKeyword}' に一致したカテゴリ一覧:<br>";
    foreach ($matchedCategories as $cat) {
        echo "- {$cat['categoryName']} (ID: {$cat['categoryId']})<br>";
    }
}
?>