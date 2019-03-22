<?php

include("db.php");

//$branchName = 'สยาม';
$branchName = $_POST['branchName'];
//echo $branchName;
$sql = mysqli_query($con, "SELECT b.Branch, b.Addr1 as Address, b.ZIP, b.img_map as imgUrl FROM branch b WHERE b.Active = 1 AND b.Branch = '" . $branchName . "'");

$DataList = [];
$BranchListData = [];
$msg = 'Something wrong happened! Please try again!';

while ($arr = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
	foreach ($arr as $key => $val) {
		$DataList[$key] = $val;
	}
	array_push($BranchListData, $DataList);
}

if(!empty($BranchListData)){
	$msg = 'success';
}
echo json_encode(array('msg' => $msg, 'branch' => $BranchListData));