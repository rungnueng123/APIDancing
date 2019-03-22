<?php

include("db.php");

//$coinPackID = $_POST['coinPackID'];
$coinPackID = 1;

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

$orderRef = time();
$baht = sprintf("%011s", $realBaht);
$currency = "764";
$amount = $baht;

$ValueListData = [];
$ValueListData[0]['merchant_id'] = '1000';
$ValueListData[0]['orderRef'] = $orderRef;
$ValueListData[0]['currCode'] = $currency;
$ValueListData[0]['successUrl'] = 'https://danceschool.matchbox-station.com/MDancingPHP/Success.php';
$ValueListData[0]['failUrl'] = 'https://danceschool.matchbox-station.com/MDancingPHP/Fail.php';
$ValueListData[0]['cancelUrl'] = 'https://danceschool.matchbox-station.com/MDancingPHP/Cancel.php';
$ValueListData[0]['payType'] = 'N';
$ValueListData[0]['lang'] = 'E';
$ValueListData[0]['remark'] = '-';
$ValueListData[0]['amount'] = $amount;
if(!empty($ValueListData)){
	$msg = 'Success';
}
//print_pre($ValueListData);
//echo json_encode(array('msg' => $msg, 'data' => $ValueListData));


echo 'Payment information:';
echo '<html>
	<body>
	<form id="payFormCcard" name="payFormCcard" method="post" action="https://ipay.bangkokbank.com/b2c/eng/payment/payForm.jsp">
		<input type="hidden" name="merchant_id" value="1000"/>
		<input type="hidden" name="orderRef" value="' . $orderRef . '"/>
		<input type="hidden" name="currCode" value="' . $currency . '"/>
		<input type="hidden" name="successUrl" value="https://danceschool.matchbox-station.com/MDancingPHP/Success.php"/>
		<input type="hidden" name="failUrl" value="https://danceschool.matchbox-station.com/MDancingPHP/Fail.php"/>
		<input type="hidden" name="cancelUrl" value="https://danceschool.matchbox-station.com/MDancingPHP/Cancel.php"/>
		<input type="hidden" name="payType" value="N"/>
		<input type="hidden" name="lang" value="E"/>
		<input type="hidden" name="remark" value="-"/>
		<input type="hidden" name="orderRef" value="' . $orderRef . '"/>
		<input type="hidden" name="amount" value="' . $amount . '"/>

		<input type="submit" name="submit" value="Confirm" />

	</form>
	<script type="text/javascript">
		document.forms.payFormCcard.submit();
	</script>
	</body>
	</html>';
?>