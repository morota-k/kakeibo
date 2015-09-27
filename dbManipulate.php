<?PHP

function dbConnect() {
	$con = mysql_connect('127.0.0.1', 'root', '');
    if (!$con) {
        exit('データベースに接続できませんでした。');
    }
    return $con;
}

function dbSet($con) {

    $result = mysql_select_db('kakeibo', $con);
    if (!$result) {
        exit('データベースを選択できませんでした。');
    }

    $result = mysql_query('SET NAMES utf8', $con);
    if (!$result) {
        exit('文字コードを指定できませんでした。');
    }
    return $result;
}

function dbClose($con) {
	$con = mysql_close($con);
    if (!$con) {
        exit('データベースとの接続を閉じられませんでした。');
    }
}

?>