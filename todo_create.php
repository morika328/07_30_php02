<?php

// ↓データが送られているか確認
// var_dump($_POST);
// exit();


// ↓ 'ParamError'が表示されたらデータが送られていない
// ＝空欄で送信されたとか
// ＝'ParamError'が表示されなければ、正常にデータ送られている
if (
  !isset($_POST['todo']) || $_POST['todo'] == '' ||
  !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
  exit('ParamError');
}

// ↓POSTで送ったname属性のtodoとdeadlineを、
// $todoと$deadlineの変数に入れる
$todo = $_POST['todo'];
$deadline = $_POST['deadline'];



// DB接続情報
$dbn = 'mysql:dbname=gsacf_d07_30;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
// "db error"が表示されたらエラーが発生していることになる
// 何も表示されなければ正常な動きをしていることになる

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// SQL作成&実行
$sql = 'INSERT INTO
todo_table(id, todo, deadline, created_at, updated_at)
VALUES(NULL, :todo, :deadline, sysdate(), sysdate())';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);

// SQLを実行する
$status = $stmt->execute();

// データ登録失敗次にエラーを表示
if ($status == false) {
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
} else {
  // 登録ページへ移動
  header('Location:todo_input.php');
}

// ↑ここまでちゃんと動いていれば、SQLにデータが蓄積されるので、確認必要。