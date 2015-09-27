<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset = "utf-8">
	<title>登録完了♫</title>
</head>
<body>

<?php
require_once 'dbManipulate.php';

$type = $_GET['type'];
$price = $_GET['price'];

$con = dbConnect();
$result = dbSet($con);

$result = mysql_query("INSERT INTO kakeibo (type, price) VALUES ('{$type}', {$price})", $con);

dbClose($con);

print '登録が完了したよ<br>';

?>

<a href='CreateKakeibo.html'>続けて家計簿を登録する</a><br>
<a href='http://localhost/kakeibo/displayKakeibo.php'>家計簿を確認する</a><br>
<a href="menu.html">メニュー</a>

</body>
</html>