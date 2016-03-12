<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];
$dbh = connectDb(); //データベースへの接続

//SQLの準備と実行
$sql = "select * from tasks where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$post = $stmt->fetch(PDO::FETCH_ASSOC); //結果の取得

//タスクの編集
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

  $title = $_POST['title'];
  $errors = array();

// バリデーション
  if ($title == ''){
    $errors['title'] = 'タスク名を入力してください';
  }
  if ($title == $post['title']){
    $errors['title'] = 'タスク名が変更されていません';
  }

//エラーがなければレコード更新
  if (empty($errors)){
    $dbh = connectDb();
    $sql = "update tasks set title = :title, updated_at = now() where id = :id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    header('Location: index.php');
    exit;
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>編集画面</title>
</head>
<body>
<h2>タスクの編集</h2>
<p>
  <form action="" method="post">
    <input type="text" name="title" value="<?php echo h($post['title']); ?>">
    <input type="submit" value="編集">
    <span style="color:red;"><?php echo h($errors['title']); ?></span>
  </form>
</p>
</body>
</html>
