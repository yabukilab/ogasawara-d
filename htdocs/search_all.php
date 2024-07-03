<?php
require 'db.php';

try {
    // データベース内のすべてのテーブルを取得
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $database_contents = [];
    foreach ($tables as $table) {
        // テーブル内のすべてのカラムを取得
        $stmt = $db->query("SHOW COLUMNS FROM $table");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // テーブルの内容を取得
        $stmt = $db->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $database_contents[$table] = [
            'columns' => $columns,
            'rows' => $rows
        ];
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベース検索 - ONlinebrary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4A90E2;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>データベース検索</h1>
    </div>
    <div class="container">
        <?php foreach ($database_contents as $table => $data): ?>
            <h2><?php echo htmlspecialchars($table); ?></h2>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($data['columns'] as $column): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['rows'] as $row): ?>
                        <tr>
                            <?php foreach ($data['columns'] as $column): ?>
                                <td><?php echo htmlspecialchars($row[$column]); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</body>
</html>
