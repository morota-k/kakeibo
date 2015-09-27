<?PHP
require_once 'dbManipulate.php';

// 家計簿を削除する
$id = $_GET['id'];

$con = dbConnect();
$result = dbSet($con);

$result = mysql_query("DELETE FROM kakeibo WHERE id = {$id}", $con);

dbClose($con);

header('location: http://localhost/kakeibo/displayKakeibo.php');
exit();

?>