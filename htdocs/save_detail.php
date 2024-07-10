<!--save_detail.php-->
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $confirm = $_POST['confirm'];
    if ($confirm == 'yes') {
        $title = $_POST['title'];
        $imgData = $_POST['imgData'];

        // 画像データをデコード
        $img = base64_decode($imgData);

        // ログインしているユーザーの ID を取得
        $username = $_SESSION['username'];

        // データベース接続情報
        require 'db.php';

        // ログインしているユーザーの ID を取得
        $stmt = $db->prepare("SELECT id FROM logininf WHERE user = ?");
        $stmt->execute([$username]);
        $user_id = $stmt->fetchColumn();

        // 重複チェック
        $stmt = $db->prepare("SELECT COUNT(*) FROM detail WHERE title = ? AND owner = ?");
        $stmt->execute([$title, $user_id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "登録が重複しています。";
        } else {
            // データを挿入
            $stmt = $db->prepare("INSERT INTO detail (title, img, owner) VALUES (?, ?, ?)");
            if ($stmt->execute([$title, $img, $user_id])) {
                echo "書籍情報が保存されました。";
                $randomVideoNumber = rand(1, 8);
                echo '<script>
                        const overlay = document.createElement("div");
                        overlay.id = "overlay";
                        overlay.style.position = "fixed";
                        overlay.style.top = "0";
                        overlay.style.left = "0";
                        overlay.style.width = "100%";
                        overlay.style.height = "100%";
                        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
                        overlay.style.display = "flex";
                        overlay.style.justifyContent = "center";
                        overlay.style.alignItems = "center";
                        overlay.style.zIndex = "9999";

                        const video = document.createElement("video");
                        video.src = "motion/motion' . $randomVideoNumber . '.mp4";
                        video.controls = false;
                        video.muted = true;  // ミュート設定
                        video.style.maxWidth = "80%";
                        video.style.maxHeight = "80%";
                        video.autoplay = true;

                        overlay.appendChild(video);
                        document.body.appendChild(overlay);

                        video.addEventListener("ended", () => {
                            document.body.removeChild(overlay);
                            window.location.href = "home.php";
                        });
                      </script>';
                exit();
            } else {
                echo "データ保存中にエラーが発生しました: " . $stmt->errorInfo()[2];
            }
        }

        $db = null;
    } else {
        header("Location: imgimport.php");
        exit();
    }
}
?>


