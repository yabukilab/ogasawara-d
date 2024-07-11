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
    <link rel="stylesheet" href="style.css?<?php date_default_timezone_set('Asia/Tokyo'); echo date("ymdHi",filemtime("style.css")); ?>">
</head>
<body>
    <div class="header">
        <h1>書籍情報確認</h1>
    </div>
    <div>
        <button class="return-button" onclick="location.href='imgimport.php'">戻る</button>
    </div>
    <div class="container">
        <div class="title">以下の情報で登録しますか？</div>
        <div class="form-group">
            <label>タイトル名:</label>
            <p><?php echo htmlspecialchars($title); ?></p>
        </div>
        <div class="form-group">
            <label>画像:</label>
            <img src="data:image/jpeg;base64,<?php echo $img_base64; ?>" alt="<?php echo htmlspecialchars($title); ?>" class="responsive-img center-img">
        </div>
        <form action="save_detail.php" method="post">
            <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <input type="hidden" name="imgData" value="<?php echo htmlspecialchars($img_base64); ?>">
            <div class="right-align">
                <button type="submit" name="confirm" value="yes" class="onebtn">確認</button>
            </div>
            <div class="right-align">
                <button type="submit" name="confirm" value="no" class="onebtn">キャンセル</button>
            </div>
        </form>
    </div>
</body>
</html>

