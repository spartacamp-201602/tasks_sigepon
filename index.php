<?php

require_once('config.php');     //設定ファイルを読み込む
require_once('functions.php');  //関数ファイルを読み込む
$dbh = connectDb();

$sql = 'select * from tasks';
$stmt = $dbh->prepare($sql);
$stmt->execute(); //SQL文実行


//SELECT
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $title = $_POST['title'];

  $errors = array();//エラーの情報を格納する配列

  //バリデーション処理
  if ($title == ''){
    $errors['title'] = 'タスク名を入力してください';
  }
  //エラーがないかどうかの確認
  // if ($errors == array()){

  // }
  if (empty($errors)) {
    $dbh = connectDb();

    $sql = 'insert into tasks (title, created_at, updated_at)';
    $sql.= 'values (:title, now(), now())';

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->execute(); //insert実行完了

    //自分自身にリダイレクト→ブラウザ更新
    header('Location: index.php');
    exit;
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>タスク管理</title>
</head>
<body>
  <h1>タスク管理アプリ</h1>
  <p>
  <form action="" method="post">
    <input type="text" name="title">
    <input type="submit" value="追加">
    <span style="color:red;"><?php echo h($errors['title']) ?></span>
  </form>
  </p>

  <h2>未完了タスク</h2>
  <ul>
    <?php foreach ($tasks as $task): ?>
      <?php if ($task['status'] == 'notyet'): ?>
      <li>
        <?php echo h($task['title']) ?>
        <a href="done.php?id=<?php echo h($task['id']); ?>">[完了]</a>
        <a href="edit.php?id=<?php echo h($task['id']); ?>">[編集]</a>
        <a href="delete.php?id=<?php echo h($task['id']); ?>">[削除]</a>
      </li>
    <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  <hr>

  <h2>完了したタスク</h2>

  <ul>
    <?php foreach($tasks as $task):?>

      <?php if ($task['status'] == 'done'): ?>
        <li><?php echo h($task['title'])?></li>
      <?php endif ?>

    <?php endforeach ?>
  </ul>
</body>
</html>
