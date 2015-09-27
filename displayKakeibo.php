<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset = "utf-8">
	<title>家計簿を確認する</title>

</head>
<body>
<h1>家計簿を確認する</h1>
<?PHP
require_once 'dbManipulate.php';

// 今月の合計を出力する
$currentMonth = '20'.date('y').'-'.date('m');

$con = dbConnect();
$result = dbSet($con);

$result = mysql_query("SELECT SUM(price) as sum from kakeibo WHERE date LIKE '{$currentMonth}%'", $con);
    while ($data = mysql_fetch_array($result)) {
    	if ($data['sum'] === null) {
    		print '今月はまだお金を使っていません';
    	} else {
            print '<p>今月の合計は'.$data['sum'].'円です</p>';
        }
    }

dbClose($con);

?>

<?php

// 家計簿一覧を取得する
$con = dbConnect();
$result = dbSet($con);
if (isset($_GET['pageNum'])) {
    $pageNum = $_GET['pageNum'];
} else {
    $pageNum = 0;
}

$qry = "SELECT id, substring(date, 1, 10) AS date, type, price FROM kakeibo WHERE 1 = 1";
if (isset($_GET['selectedDate']) && ($_GET['selectedDate']) !== '') {
	$selectedDate = $_GET['selectedDate'];
	$qry .= " AND date LIKE '{$selectedDate}%'";
}
if (isset($_GET['selectedType']) && ($_GET['selectedType']) !== '') {
	$selectedType = $_GET['selectedType'];
	$qry .= " AND type = '{$selectedType}'";
}
// 日付が新しい順に並び替え
$qry .= " ORDER BY date DESC";

$qry .= " LIMIT ". $pageNum * 5 .", 5";

$result = mysql_query($qry, $con);
if(mysql_num_rows($result) == 0) {
	print '<p>データが0件です</p>';
} else {
?>
<table border="1">
    <tr>
        <th>日付</th>
        <th>分類</th>
        <th>値段</th>
        <th></th>
    </tr>
<?php
if (isset($result)) {
    while ($data = mysql_fetch_array($result)) {
        echo '<tr><td>' . $data['date'] 
        . '</td><td>' . $data['type'] . '</td><td>' . $data['price'] . "</td><td>"
        . "<a href='http://localhost/kakeibo/deleteKakeibo.php?id={$data[0]}'>削除</a>" ."</td></tr>";
    }
}
print '</table>';
}

// 検索条件に該当する全データの件数取得
$qry = "SELECT COUNT(*) FROM kakeibo WHERE 1 = 1";
if (isset($_GET['selectedDate']) && ($_GET['selectedDate']) !== '') {
	$selectedDate = $_GET['selectedDate'];
	$qry .= " AND date LIKE '{$selectedDate}%'";
}
if (isset($_GET['selectedType']) && ($_GET['selectedType']) !== '') {
	$selectedType = $_GET['selectedType'];
	$qry .= " AND type = '{$selectedType}'";
}

if (!$result = mysql_query($qry)) {
	print "SQLエラー<br>";
	exit;
}

$row = mysql_fetch_array($result);
$cnt = $row[0];

dbClose($con);

// 現在のページを表示
$currentPage = ceil($cnt / 5);
$currentPage .= "ページ中";
$currentPage .= $pageNum + 1;
$currentPage .= "ページ目を表示<br>";
print $currentPage;

// 前の5件
if ($pageNum != 0) {
	$previousPage = "<a href =http://localhost/kakeibo/displayKakeibo.php";
	$previousPage .= "?selectedDate=";
	$previousPage .= $selectedDate;
	$previousPage .= "&selectedType=";
	$previousPage .= $selectedType;
	$previousPage .= "&pageNum=";
	$previousPage .= $pageNum - 1;
	$previousPage .= ">";
	print $previousPage;
	print "&lt 前の5件</a>";
}

// 次の5件
if (($pageNum + 1) * 5 < $cnt) {
	$nextPage = "<a href =http://localhost/kakeibo/displayKakeibo.php";
	$nextPage .= "?selectedDate=";
    $nextPage .= $selectedDate;
    $nextPage .= "&selectedType=";
    $nextPage .= $selectedType;
    $nextPage .= "&pageNum=";
	$nextPage .= $pageNum +1 ;
	$nextPage .= ">";
    print $nextPage;
	print "次の5件 &gt</a>";
}

// 今日の合計を出力する
$today = '20'.date('y').'-'.date('m').'-'.date('d');

$con = dbConnect();
$result = dbSet($con);

$result = mysql_query("SELECT SUM(price) as sum from kakeibo WHERE date LIKE '{$today}%'", $con);
    while ($data = mysql_fetch_array($result)) {
    	if ($data['sum'] === null) {
    		print '今日はまだお金を使っていません';
    	} else {
            print '<p>今日の合計は'.$data['sum'].'円です</p>';
        }
    }

dbClose($con);

?>

<!-- 絞り込み条件を指定 -->
<form action="http://localhost/kakeibo/displayKakeibo.php" method="GET">
<p>日付で絞り込む</p>
<input type="date" name="selectedDate">
<p>分類で絞り込む</p>
    <select name='selectedType'>
        <option value='' selected>選択してください</option>
        <option value='日用品'>日用品</option>
        <option value='食費'>食費</option>
        <option value='趣味・娯楽'>趣味・娯楽</option>
        <option value='衣服・美容'>衣服・美容</option>
        <option value='健康・医療'>健康・医療</option>
        <option value='交通費'>交通費</option>
        <option value='交際費'>交際費</option>
        <option value='その他'>その他</option>
    </select><br>
    <input type="submit" value="絞り込む">
</form>

<a href="menu.html">メニュー</a>

</body>
</html>