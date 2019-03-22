<?php

include("db.php");

//$coinPackID = $_POST['coinPackID'];
$coinPackID = $_GET['coinPackID'];

$selectNamePackSql = mysqli_query($con, "SELECT namepack, baht FROM coinpack WHERE id = '" . $coinPackID . "'");
//$sql = mysqli_query($con, "SELECT eventStart FROM student_class_home WHERE DATE(eventStart) >= '" . $date . "'");

$DataList = [];
$newArr = [];
$namepack = '';
$realBaht = '';
$msg = "Fail";

while ($arr = mysqli_fetch_array($selectNamePackSql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($newArr, $DataList);
}

foreach ($newArr as $key => $val) {
	$namepack = $val['namepack'];
	$realBaht = $val['baht'];
}

$bathArr = explode(".",$realBaht);
//print_pre($bathArr);

$realBaht1 = $bathArr[0].$bathArr[1];
$baht = sprintf("%012s", $realBaht1);
//echo $baht;

$merchant_id = "JT04";        //Get MerchantID when opening account with 2C2P
$secret_key = "QnmrnH6QE23N";    //Get SecretKey from 2C2P PGW Dashboard

//Transaction information
$payment_description = $namepack;
$order_id = time();
$currency = "764";
$amount = $baht;

//Request information
$version = "7.2";
$payment_url = "https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment";
$result_url_1 = "$host_url/result_url_2c2p.php";

//Construct signature string
$params = $version . $merchant_id . $payment_description . $order_id . $currency . $amount . $result_url_1;
$hash_value = hash_hmac('sha1', $params, $secret_key, false);    //Compute hash value

//$ValueListData = [];
//$ValueListData[0]['paymentUrl'] = $payment_url;
//$ValueListData[0]['version'] = $version;
//$ValueListData[0]['merchantId'] = $merchant_id;
//$ValueListData[0]['currency'] = $currency;
//$ValueListData[0]['resultUrl1'] = $result_url_1;
//$ValueListData[0]['hashValue'] = $hash_value;
//$ValueListData[0]['paymentDesc'] = $payment_description;
//$ValueListData[0]['orderId'] = $order_id;
//$ValueListData[0]['amount'] = $amount;
//if(!empty($ValueListData)){
//	$msg = 'Success';
//}
////print_pre($ValueListData);
//echo json_encode(array('msg' => $msg, 'data' => $ValueListData));

echo 'Payment information:';
echo '<html>
	<body>
	<form id="myform" method="post" action="'.$payment_url.'">
		<input type="hidden" name="version" value="'.$version.'"/>
		<input type="hidden" name="merchant_id" value="'.$merchant_id.'"/>
		<input type="hidden" name="currency" value="'.$currency.'"/>
		<input type="hidden" name="result_url_1" value="'.$result_url_1.'"/>
		<input type="hidden" name="hash_value" value="'.$hash_value.'"/>
		PRODUCT INFO : <input type="text" name="payment_description" value="'.$payment_description.'"  readonly/><br/>
		ORDER NO : <input type="text" name="order_id" value="'.$order_id.'"  readonly/><br/>
		AMOUNT: <input type="text" name="amount" value="'.$amount.'" readonly/><br/>
	</form>
	<script type="text/javascript">
		document.getElementById("myform").submit();
	</script>
	</body>
	</html>';
?>


































