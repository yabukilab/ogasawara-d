<!--imgverify.php-->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];

    // 画像ファイルがアップロードされているかチェック
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img = $_FILES['img'];
        $imgData = file_get_contents($img['tmp_name']);
        $img_base64 = base64_encode($imgData);
    } else {
        die("画像ファイルのアップロードに失敗しました。");
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍情報確認 - ONlinebrary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>書籍情報確認</h1>
    <div>
        <p>タイトル名: <?php echo htmlspecialchars($title); ?></p>
        <img src="data:image/jpeg;base64,<?php echo $img_base64; ?>" alt="<?php echo htmlspecialchars($title); ?>">
    </div>
    <form action="save_detail.php" method="post">
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
        <input type="hidden" name="imgData" value="<?php echo htmlspecialchars($img_base64); ?>">
        <button type="submit" name="confirm" value="yes">確認</button>
        <button type="submit" name="confirm" value="no">キャンセル</button>
    </form>
</body>
</html>
