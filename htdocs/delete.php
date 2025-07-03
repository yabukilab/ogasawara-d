<?php
session_start();
require 'db.php'; // DB接続

// POSTされたチェックボックスのデータを受け取る
if (isset($_POST['selected']) && is_array($_POST['selected'])) {
    // 選択された行のキー（インデックス）を取得
    $selectedIndexes = array_keys($_POST['selected']);

    // POSTされた全データも受け取る
    $data = $_POST['data'];

    // 削除対象のIDを集める
    $deleteIds = [];

    foreach ($selectedIndexes as $idx) {
        // $idxが存在し、IDがあれば追加
        if (isset($data[$idx]['ID'])) {
            $deleteIds[] = (int)$data[$idx]['ID'];
        }
    }

    if (count($deleteIds) > 0) {
        // プレースホルダーを作成（?, ?, ...）
        $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));

        // DELETE文準備
        $sql = "DELETE FROM food WHERE ID IN ($placeholders)";
        $stmt = $db->prepare($sql);

        try {
            $stmt->execute($deleteIds);
            $_SESSION['message'] = count($deleteIds) . " 件の食材を削除しました。";
        } catch (PDOException $e) {
            $_SESSION['message'] = "削除に失敗しました: " . h($e->getMessage());
        }
    } else {
        $_SESSION['message'] = "削除するデータが選択されていません。";
    }
} else {
    $_SESSION['message'] = "削除するデータが選択されていません。";
}

// 削除完了後は削除画面（または一覧画面）へリダイレクト
header('Location: recipe_delete.php');
exit;