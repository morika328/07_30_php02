<?php

// ↓データが送られているか確認
// var_dump($_POST);
// exit();

// ↓POSTで送ったname属性のtodoとdeadlineを、
// $todoと$deadlineの変数に入れる
$name = $_POST['name'];
$interest = $_POST['interest'];
$Dr = $_POST['Dr'];
$DH = $_POST['DH'];
$sonota = $_POST['sonota'];
$maker = $_POST['maker'];
$kikan = $_POST['kikan'];
$problem = $_POST['problem'];
$point = $_POST['point'];



// DB接続情報
$dbn = 'mysql:dbname=gsacf_d07_30_memoapp;charset=utf8;port=3306;host=localhost';
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

// ↓ 'ParamError'が表示されたらデータが送られていない
// ＝空欄で送信されたとか
// ＝'ParamError'が表示されなければ、正常にデータ送られている
if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['interest']) || $_POST['interest'] == '' ||
  !isset($_POST['Dr']) || $_POST['Dr'] == '' ||
  !isset($_POST['DH']) || $_POST['DH'] == '' ||
  !isset($_POST['sonota']) || $_POST['sonota'] == '' ||
  !isset($_POST['maker']) || $_POST['maker'] == '' ||
  !isset($_POST['kikan']) || $_POST['kikan'] == '' ||
  !isset($_POST['problem']) || $_POST['problem'] == '' ||
  !isset($_POST['point']) || $_POST['point'] == ''
) {
  exit('ParamError');
}

// SQL作成&実行
$sql = 'INSERT INTO
todo_table(id, name, interest, Dr, DH, sonota, maker, kikan, problem, point, created_at, updated_at)
VALUES(NULL, :name, :interest, :Dr, :DH, :sonota, :maker, :kikan, :plobrem, :point, sysdate(), sysdate())';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':interest', $interest, PDO::PARAM_STR);
$stmt->bindValue(':Dr', $Dr, PDO::PARAM_STR);
$stmt->bindValue(':DH', $DH, PDO::PARAM_STR);
$stmt->bindValue(':sonota', $sonota, PDO::PARAM_STR);
$stmt->bindValue(':maker', $maker, PDO::PARAM_STR);
$stmt->bindValue(':kikan', $kikan, PDO::PARAM_STR);
$stmt->bindValue(':problem', $problem, PDO::PARAM_STR);
$stmt->bindValue(':point', $point, PDO::PARAM_STR);

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