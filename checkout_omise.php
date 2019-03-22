<?php

include("db.php");

require_once dirname(__FILE__) . '/omise-php/lib/Omise.php';
define('OMISE_API_VERSION', $_POST['$api_version_omise']);
define('OMISE_PUBLIC_KEY', $_POST['public_key_omise']);
define('OMISE_SECRET_KEY', $_POST['secret_key_omise']);

$omiseToken = $_POST["omiseToken"];

$bath = $_POST['bath'];
$userID = $_POST['userID'];
$coinPackID = $_POST['coinPackID'];
$coin = $_POST['coin'];
$realBaht = $_POST['realBaht'];
$namePack = $_POST["namepack"];
date_default_timezone_set('Asia/Bangkok');
$date = date('Y-m-d H:i:s');

$eventID = $_POST['eventID'];
$eventName = $_POST['eventName'];
$msg = 'fail';

if (!empty($coinPackID)) {

	$chargeCreate = OmiseCharge::create(array(
		'amount' => $bath,
		'currency' => 'thb',
		'card' => $omiseToken,
		'description' => $namePack
	));


//$charges = OmiseCharge::retrieve($chargeCreate['id']);

//$balance = OmiseBalance::retrieve();
//$balance->reload();
//$transactions = OmiseTransaction::retrieve($charge['transaction']);
//$events = OmiseEvent::retrieve();

	$status = '';
	if ($chargeCreate['status'] == 'successful') {
		$status = 'Success';
	} else {
		$status = 'Fail';
	}

	$charge = (array)$chargeCreate;

	$myJson = json_encode(array('status' => $status, 'data' => json_encode($charge)));
//echo $myJson;

	$Amount = $realBaht * -1;
	$coinTxnInsertSql = "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $date . "','" . $Amount . "','" . $coin . "','B','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')";

	if ($con->query($coinTxnInsertSql)) {
		$TxnID = mysqli_insert_id($con);
		$paymentTxnInsertSql = "INSERT INTO paymenttxn (user_UserID, jsondata, cointxn_TxnID, CreatedDate, UpdatedDate)
                    VALUES ('" . $userID . "','" . $myJson . "','" . $TxnID . "','" . $date . "','" . $date . "')";
	}

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


	if ($con->query($paymentTxnInsertSql) && $con->query($userUpdateCoinSql)) {
		$msg = 'success';
	}

	if ($msg == 'success') {
		echo '<html>
			<head>
			</head>
			<body>
			<div id="main-wrapper">
				<div class="page-container">
					<div class="portlet light">
						<div class="portlet-body" style="margin-top: 300px">
							<div class="row">
								<div class="col-md-12" align="center">
									<img src="image/ic_success.png" alt="" width="400"/>
								</div>
							</div>
							<div class="row" style="margin-top: 15%">
								<div class="col-md-12 kanitMedium" align="center">
									<span style="font-weight:bold;font-size: 70px">ชำระเงินสำเร็จ!</span>
								</div>
								<br>
								<div class="col-md-12 kanitRegular" align="center">
									<p style="font-size: 50px;color: #6C7B8A">ขั้นตอนการชำระเงินสำเร็จ</span>
								</div>
							</div>			
						</div>
					</div>
				</div>
			</div>
				<style>
				@font-face{
					font-family: kanitRegular;
					src: url("font/kanit_regular.ttf");
				}
				@font-face{
					font-family: kanitMedium;
					src: url("font/kanit_medium.ttf");
				}
				.kanitRegular{
					font-family: kanitRegular;
				}
				.kanitMedium{
					font-family: kanitMedium;
				}
				</style>
			</body>
			</html>';
	}else{
		echo '<html>
			<head>
			</head>
			<body>
			<div id="main-wrapper">
				<div class="page-container">
					<div class="portlet light">
						<div class="portlet-body" style="margin-top: 300px">
							<div class="row">
								<div class="col-md-12" align="center">
									<img src="image/ic_fail.png" alt="" width="400"/>
								</div>
							</div>
							<div class="row" style="margin-top: 15%">
								<div class="col-md-12 kanitMedium" align="center">
									<span style="font-weight:bold;font-size: 70px">ชำระเงินไม่สำเร็จ!</span>
								</div>
								<br>
								<div class="col-md-12 kanitRegular" align="center">
									<p style="font-size: 50px;color: #6C7B8A">เกิดข้อผิดพลาด! กรุณาลองใหม่อีกครั้ง!</span>
								</div>
							</div>			
						</div>
					</div>
				</div>
			</div>
				<style>
				@font-face{
					font-family: kanitRegular;
					src: url("font/kanit_regular.ttf");
				}
				@font-face{
					font-family: kanitMedium;
					src: url("font/kanit_medium.ttf");
				}
				.kanitRegular{
					font-family: kanitRegular;
				}
				.kanitMedium{
					font-family: kanitMedium;
				}
				</style>
			</body>
			</html>';
	}

}


if (!empty($eventID)) {
	$chargeCreate = OmiseCharge::create(array(
		'amount' => $bath,
		'currency' => 'thb',
		'card' => $omiseToken,
		'description' => $eventName
	));

	$status = '';
	if ($chargeCreate['status'] == 'successful') {
		$status = 'Success';
	} else {
		$status = 'Fail';
	}

	$charge = (array)$chargeCreate;

	$myJson = json_encode(array('status' => $status, 'data' => json_encode($charge)));
//echo $myJson;

	$Amount = $realBaht * -1;
	$coinTxnInsertSql = "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                    VALUES ('" . $userID . "','" . $date . "','" . $Amount . "','" . $coin . "','B','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')";

	if ($con->query($coinTxnInsertSql)) {
		$TxnID = mysqli_insert_id($con);
		$paymentTxnInsertSql = "INSERT INTO paymenttxn (user_UserID, jsondata, cointxn_TxnID, CreatedDate, UpdatedDate)
                    VALUES ('" . $userID . "','" . $myJson . "','" . $TxnID . "','" . $date . "','" . $date . "')";
		if ($con->query($paymentTxnInsertSql)) {
			$checkUserSql = mysqli_query($con, "SELECT RegisID
											FROM registration WHERE user_UserID = '" . $userID . "'");
			$DataList = [];
			$checkUserListData = [];
			$newArr = [];
			while ($arr = mysqli_fetch_array($checkUserSql, MYSQLI_ASSOC)) {
				foreach ($arr as $key => $val) {
//		echo $key . ' ' . $val;
					$DataList[$key] = $val;
				}
				array_push($newArr, $DataList);
			}
			foreach ($newArr as $val) {
				$checkUserListData = $val;
			}
			if (empty($checkUserListData)) {
				$addRegisClassSql = "INSERT INTO registration (CoinAmt, Times, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, user_UserID)
                    VALUES (0,0,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $userID . "')";
				if ($con->query($addRegisClassSql)) {
					$regisID = mysqli_insert_id($con);
					$addStudentSchedSql = "INSERT INTO studentsched (eventID, payType, coinAmt, times, Active, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, registration_RegisID)
                    VALUES ('" . $eventID . "',1,'" . $coin . "',0,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $regisID . "')";
					if ($con->query($addStudentSchedSql)) {
						$sumCoinAmtSql = mysqli_query($con, "SELECT SUM(coinAmt) as sumCoin FROM studentsched WHERE registration_RegisID = '" . $regisID .
							"'");
						$DataList1 = [];
						$newArr1 = [];
						$sumCoinListData = [];
						while ($arr = mysqli_fetch_array($sumCoinAmtSql, MYSQLI_ASSOC)) {
							foreach ($arr as $key => $val) {
								$DataList1[$key] = $val;
							}
							array_push($newArr1, $DataList1);
						}
						foreach ($newArr1 as $val) {
							$sumCoinListData = $val;
						}
						if (!empty($sumCoinListData)) {
							$updateRegisSql = "UPDATE registration SET CoinAmt = '" . $sumCoinListData['sumCoin'] . "' WHERE RegisID = '" . $regisID . "'";
							if ($con->query($updateRegisSql)) {
								$addRegisClassSql = mysqli_query($con, "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                   							 VALUES ('" . $userID . "','" . $date . "','" . $realBaht . "','" . $coin . "','S','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')");
								$msg = 'success';
							}
						}
					}
				}
			} else {
				$addStudentSchedSql = "INSERT INTO studentsched (eventID, payType, coinAmt, times, Active, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, registration_RegisID)
                    VALUES ('" . $eventID . "',1,'" . $coin . "',0,1,'" . $date . "','" . $userID . "','" . $date . "','" . $userID . "','" . $checkUserListData['RegisID'] . "')";
				if ($con->query($addStudentSchedSql)) {
					$sumCoinAmtSql = mysqli_query($con, "SELECT SUM(coinAmt) as sumCoin FROM studentsched WHERE registration_RegisID = '" . $checkUserListData['RegisID'] .
						"'");
					$DataList1 = [];
					$newArr1 = [];
					$sumCoinListData = [];
					while ($arr = mysqli_fetch_array($sumCoinAmtSql, MYSQLI_ASSOC)) {
						foreach ($arr as $key => $val) {
							$DataList1[$key] = $val;
						}
						array_push($newArr1, $DataList1);
					}
					foreach ($newArr1 as $val) {
						$sumCoinListData = $val;
					}
					if (!empty($sumCoinListData)) {
						$updateRegisSql = "UPDATE registration SET CoinAmt = '" . $sumCoinListData['sumCoin'] . "' WHERE RegisID = '" . $checkUserListData['RegisID'] . "'";
						if ($con->query($updateRegisSql)) {
							$addRegisClassSql = mysqli_query($con, "INSERT INTO cointxn (UserID, TxnDate, Amount, CoinAmt, BuySell, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy)
                   							 VALUES ('" . $userID . "','" . $date . "','" . $realBaht . "','" . $coin . "','S','" . $date . "','" . $userID . "','" . $date . "','" . $userID . "')");
							$msg = 'success';
						}
					}
				}
			}
		}
	}

	if ($msg == 'success') {
		echo '<html>
			<head>
			</head>
			<body>
			<div id="main-wrapper">
				<div class="page-container">
					<div class="portlet light">
						<div class="portlet-body" style="margin-top: 300px">
							<div class="row">
								<div class="col-md-12" align="center">
									<img src="image/ic_success.png" alt="" width="400"/>
								</div>
							</div>
							<div class="row" style="margin-top: 15%">
								<div class="col-md-12 kanitMedium" align="center">
									<span style="font-weight:bold;font-size: 70px">ชำระเงินสำเร็จ!</span>
								</div>
								<br>
								<div class="col-md-12 kanitRegular" align="center">
									<p style="font-size: 50px;color: #6C7B8A">ขั้นตอนการชำระเงินและลงทะเบียนสำเร็จ<br>กรุณามาเรียนตามวันและเวลาที่ลงทะเบียน</span>
								</div>
							</div>			
						</div>
					</div>
				</div>
			</div>
				<style>
				@font-face{
					font-family: kanitRegular;
					src: url("font/kanit_regular.ttf");
				}
				@font-face{
					font-family: kanitMedium;
					src: url("font/kanit_medium.ttf");
				}
				.kanitRegular{
					font-family: kanitRegular;
				}
				.kanitMedium{
					font-family: kanitMedium;
				}
				</style>
			</body>
			</html>';
	}else{
		echo '<html>
			<head>
			</head>
			<body>
			<div id="main-wrapper">
				<div class="page-container">
					<div class="portlet light">
						<div class="portlet-body" style="margin-top: 300px">
							<div class="row">
								<div class="col-md-12" align="center">
									<img src="image/ic_fail.png" alt="" width="400"/>
								</div>
							</div>
							<div class="row" style="margin-top: 15%">
								<div class="col-md-12 kanitMedium" align="center">
									<span style="font-weight:bold;font-size: 70px">ชำระเงินไม่สำเร็จ!</span>
								</div>
								<br>
								<div class="col-md-12 kanitRegular" align="center">
									<p style="font-size: 50px;color: #6C7B8A">เกิดข้อผิดพลาด! กรุณาลองใหม่อีกครั้ง!</span>
								</div>
							</div>			
						</div>
					</div>
				</div>
			</div>
				<style>
				@font-face{
					font-family: kanitRegular;
					src: url("font/kanit_regular.ttf");
				}
				@font-face{
					font-family: kanitMedium;
					src: url("font/kanit_medium.ttf");
				}
				.kanitRegular{
					font-family: kanitRegular;
				}
				.kanitMedium{
					font-family: kanitMedium;
				}
				</style>
			</body>
			</html>';
	}
}

//echo $msg;

//print('<pre>');
//print_r($chargeCreate);
//print('</pre>');