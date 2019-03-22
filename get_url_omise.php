<?php

include("db.php");
include("welcome.php");
$userID = $_GET['userID'];
$coinPackID = $_GET['coinPackID']; // ซื้อ coin อย่างเดียว
$eventID = $_GET['eventID']; // ซื้อ coin เท่ากับราคา class
$public_key_omise = $_GET['public_key_omise'];
$secret_key_omise = $_GET['secret_key_omise'];
$api_version_omise = $_GET['api_version_omise'];
$username = '';
$class = '';

if (!empty($coinPackID)) {
	$userSql = mysqli_query($con, "SELECT User FROM user WHERE UserID = '" . $userID . "'");
	while ($arr = mysqli_fetch_array($userSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$username = $val;
		}
	}

//echo $username;

	$selectNamePackSql = mysqli_query($con, "SELECT namepack, baht , coin FROM coinpack WHERE id = '" . $coinPackID . "'");
//$sql = mysqli_query($con, "SELECT eventStart FROM student_class_home WHERE DATE(eventStart) >= '" . $date . "'");

	$DataList = [];
	$newArr = [];
	$namepack = '';
	$realBaht = '';
	$coin = '';

	while ($arr = mysqli_fetch_array($selectNamePackSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$DataList[$key] = $val;
		}
		array_push($newArr, $DataList);
	}

	foreach ($newArr as $key => $val) {
		$namepack = $val['namepack'];
		$realBaht = $val['baht'];
		$coin = $val['coin'];
	}

	$bathArr = explode(".", $realBaht);
//print_pre($bathArr);

	$realBaht1 = $bathArr[0] . $bathArr[1];
	$baht = sprintf("%012s", $realBaht1);


	echo '<html>
	<head>
	</head>
	<body>
	<div id="main-wrapper">
		<div class="page-container">
				<div class="portlet-body ">
					<div class="portlet light">
							<div class="form-body">
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>ACCOUNT</label>
											</div>
											<div class="col-6" style="float: right">
												<label>' . $username . '</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>PRODUCT</label>
											</div>
											<div class="col-6" style="float: right">
												' . $namepack . '
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>COIN</label>
											</div>
											<div class="col-6" style="float: right">
												<label>' . $coin . '</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>AMOUNT</label>
											</div>
											<div class="col-6" style="float: right">
												<label>' . $realBaht . '</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
							
							
								
								<div class="form-group" style="margin-top: 25px">
									<form name="checkoutForm" method="POST" action="checkout_omise.php">
										  <script type="text/javascript" src="https://cdn.omise.co/omise.js"
										  data-key="pkey_test_5ex0mvopz0piutpxdzw"
										  data-image="https://danceschool.matchbox-station.com/imgBanner/bg.png"
										  data-frame-label="BeatsBox"
										  data-button-label="Pay with Omise"
										  data-submit-label="Checkout"
										  data-location="no"
										  data-amount="' . $baht . '"
										  data-currency="thb"
										  >
										  </script>
										  <input type="hidden" name="bath" value="' . $baht . '"/>
										  <input type="hidden" name="public_key_omise" value="' . $public_key_omise . '"/>
										  <input type="hidden" name="secret_key_omise" value="' . $secret_key_omise . '"/>
										  <input type="hidden" name="api_version_omise" value="' . $api_version_omise . '"/>
										  <input type="hidden" name="userID" value="' . $userID . '"/>
										  <input type="hidden" name="coinPackID" value="' . $coinPackID . '"/>
										  <input type="hidden" name="namepack" value="' . $namepack . '"/>
										  <input type="hidden" name="coin" value="' . $coin . '"/>
										  <input type="hidden" name="realBaht" value="' . $realBaht . '"/>
									</form>
								</div>
							</div>
					</div>
				</div>
		</div>
	</div>
	<style>
	.omise-checkout-button {
	    padding: 5px 10px;
	    border: 1px solid #CCC;
	    background-color: #40A3FF;
	    color: white;
	    border-radius: 4px;
	}
	@font-face{
		font-family: kanitMedium;
		src: url("font/kanit_medium.ttf");
	}
	.kanitMedium{
		font-family: kanitMedium;
	}
	.borderBottom{
		border-bottom: 1px solid #6C7B8A;
		font-family: kanitMedium;
	}
	</style>
	</body>
	</html>';
} else if (!empty($eventID)) {

	$userSql = mysqli_query($con, "SELECT User FROM user WHERE UserID = '" . $userID . "'");
	while ($arr = mysqli_fetch_array($userSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$username = $val;
		}
	}

	$eventSql = mysqli_query($con, "SELECT eventTitle, coin FROM student_course_class_activity WHERE eventID = '" . $eventID . "'");
	$DataList = [];
	$newArr = [];
	$eventName = '';
	$coin = '';

	while ($arr = mysqli_fetch_array($eventSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$DataList[$key] = $val;
		}
		array_push($newArr, $DataList);
	}

	foreach ($newArr as $key => $val) {
		$coin = $val['coin'];
		$eventName = $val['eventTitle'];
	}

	$bathPerCoin = '';
	$realBaht = '';
	$bathCoinSql = mysqli_query($con, "SELECT value FROM syssetting WHERE id = '1'");
	while ($arr = mysqli_fetch_array($bathCoinSql, MYSQLI_ASSOC)) {
		foreach ($arr as $key => $val) {
			$bathPerCoin = $val;
		}
	}

	$realBaht = $bathPerCoin * $coin;
	$bathArr = explode(".", $realBaht);
//print_pre($bathArr);

	if (empty($bathArr[1])) {
		$realBaht1 = $bathArr[0] . '00';
	} else if (strlen($bathArr[1]) == 1) {
		$realBaht1 = $bathArr[0] . $bathArr[1] . '0';
	} else if (strlen($bathArr[1]) == 2) {
		$realBaht1 = $bathArr[0] . $bathArr[1];
	} else {
		$realBaht1 = $bathArr[0] . substr($bathArr[1], 0, 2);
	}
	$baht = sprintf("%012s", $realBaht1);
//	echo $realBaht.'/'.$realBaht1.'/'.$baht.'/'.$bathArr[1].'/';


	echo '<html>
	<head>
	</head>
	<body>
	<div id="main-wrapper">
		<div class="page-container">
			<div class="portlet-body">
					<div class="portlet light">
							<div class="form-body">
							<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>ACCOUNT</label>
											</div>
											<div class="col-6" style="float: right">
												<label>' . $username . '</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>CLASS</label>
											</div>
											<div class="col-6" style="float: right">
												' . $eventName . '
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>COIN</label>
											</div>
											<div class="col-6" style="float: right">
												<label>' . $coin . '</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								<div class="row" style="padding-left: 5px;padding-bottom: 5px;color: #6C7B8A">
									<div class="col-md-12">
										<div class="form-group kanitMedium">
											<div class="col-6" style="float: left">
												<label>AMOUNT</label>
											</div>
											<div class="col-6" style="float: right">
												<label>' . $realBaht . '</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row borderBottom"></div>
								
								
								<div class="form-group" style="margin-top: 25px">
									<form name="checkoutForm" method="POST" action="checkout_omise.php">
										  <script type="text/javascript" src="https://cdn.omise.co/omise.js"
										  data-key="pkey_test_5ex0mvopz0piutpxdzw"
										  data-image="https://danceschool.matchbox-station.com/imgBanner/bg.png"
										  data-frame-label="BeatsBox"
										  data-button-label="Pay with Omise"
										  data-submit-label="Checkout"
										  data-location="no"
										  data-amount="' . $baht . '"
										  data-currency="thb"
										  >
										  </script>
										  <input type="hidden" name="bath" value="' . $baht . '"/>
										  <input type="hidden" name="public_key_omise" value="' . $public_key_omise . '"/>
										  <input type="hidden" name="secret_key_omise" value="' . $secret_key_omise . '"/>
										  <input type="hidden" name="api_version_omise" value="' . $api_version_omise . '"/>
										  <input type="hidden" name="userID" value="' . $userID . '"/>
										  <input type="hidden" name="eventID" value="' . $eventID . '"/>
										  <input type="hidden" name="eventName" value="' . $eventName . '"/>
										  <input type="hidden" name="coin" value="' . $coin . '"/>
										  <input type="hidden" name="realBaht" value="' . $realBaht . '"/>
									</form>
								</div>
							</div>
					</div>
			</div>
		</div>
	</div>
	<style>
	.omise-checkout-button {
	    padding: 5px 10px;
	    border: 1px solid #CCC;
	    background-color: #40A3FF;
	    color: white;
	    border-radius: 4px;
	}
	@font-face{
		font-family: kanitMedium;
		src: url("font/kanit_medium.ttf");
	}
	.kanitMedium{
		font-family: kanitMedium;
	}
	.borderBottom{
		border-bottom: 1px solid #6C7B8A;
		font-family: kanitMedium;
	}
	</style>
	</body>
	</html>';
}