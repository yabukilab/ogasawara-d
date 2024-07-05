<!--delete_item.php-->
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 削除するID（複数あり）
    $delete_ids = $_POST['chk'];

    // データベース接続情報
    require 'db.php';

    // データ数分削除処理を行う
    foreach ($delete_ids as $id) {
        $stmt = $db->prepare('DELETE FROM detail WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    header('Location: show_all.php');
    exit();
}
?>
