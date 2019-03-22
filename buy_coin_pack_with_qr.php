<?php
include("db.php");

//$userID = 27;
//$coinPackID = 1;
//$coin = 100;
//$baht = 2000.00;

$userID = $_POST['userID'];
$coinPackID = $_POST['coinPackID'];
$coin = $_POST['coin'];
$baht = $_POST['baht'];
$date = date('Y-m-d H:i:s');
$amountAddCointxn = $baht * -1;
$coinAmt = '';
$msg = "Something wrong happened! Please try again!";

$addCoinTxnSql = "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $date . "','" . $amountAddCointxn . "','" . $coin . "','Q','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')";

if ($con->query($addCoinTxnSql)) {
	$userSelectCoinSql = mysqli_query($con, "SELECT CoinAmt FROM user WHERE UserID = '" . $userID . "'");
	while ($arr = mysqli_fetch_array($userSelectCoinSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$coinAmt = $val;
		}
	}

	$coinUpdate = $coinAmt + $coin;

	$userUpdateCoinSql = "
   UPDATE user
   SET CoinAmt = '" . $coinUpdate . "'
   WHERE UserID = '" . $userID . "'
   ";


	if ($con->query($userUpdateCoinSql)) {
		$msg = 'success';
	}
}
$output['message'] = $msg;
echo json_encode($output);







//echo $userID.'/'.$coinPackID.'/'.$coin.'/'.$baht;