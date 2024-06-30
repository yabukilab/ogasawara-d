<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍登録 - ONlinebrary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: relative;
        }
        .header .return-button {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            background-color: white;
            color: #4A90E2;
            border: 2px solid #fff; /* Changed border color */
            padding: 10px 20px;
            margin-left: 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .header .return-button:hover {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #fff;
        }
        .container {
            margin-top: 50px;
        }
        .title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        label {
            margin-right: 10px;
        }
        input[type="text"],
        input[type="file"] {
            padding: 10px;
            font-size: 16px;
            width: 200px; /* Adjusted width */
        }
        .button {
            background-color: #4A90E2;
            color: white;
            border: 2px solid #000080; /* Changed border color */
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #357ABD;
        }
    </style>
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
