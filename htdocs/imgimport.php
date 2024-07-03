<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍登録 - ONlinebrary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <button class="return-button" onclick="location.href='home.php'">戻る</button>
        <h1>書籍登録画面</h1>
    </div>
    <div class="container">
        <div class="title">タイトル名とファイルを入れてください</div>
        <form action="imgverify.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">タイトル名:</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="fileToUpload">ファイルを選択して下さい:</label>
                <input type="file" name="img" id="fileToUpload" accept=".jpg, .jpeg, .png" required>
            </div>
            <button type="submit" class="button">アップロード</button>
        </form>
    </div>
</body>
</html>
