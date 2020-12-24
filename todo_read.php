<?php



// DB接続情報
$dbn = 'mysql:dbname=gsacf_d07_30;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}
// ↑「dbError:...」が表示されたらdb接続でエラーが発生していることがわかる

// SELECT文でデータ参照をおこなう
$sql = 'SELECT * FROM todo_table';

// $statusにSQLの実行結果が入る（取得したデータではない点に注意）
$stmt = $pdo->prepare($sql);

// SQLを実行する
$status = $stmt->execute();

// もし、SQLの実行結果（＝$status）が失敗した時はエラー出力する
if ($status == false) {
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
} else {

  // fetchAllで全部のデータを取得する
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // $outputに取得したデータをまとめて格納、配列にする
  $output = "";
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["deadline"]}</td>";
    $output .= "<td>{$record["todo"]}</td>";
    $output .= "</tr>";
  }
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型todoリスト（一覧画面）</title>
</head>

<body>
  <fieldset>
    <legend>DB連携型todoリスト（一覧画面）</legend>
    <a href="todo_input.php">入力画面</a>
    <table>
      <thead>
        <tr>
          <th>deadline</th>
          <th>todo</th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>