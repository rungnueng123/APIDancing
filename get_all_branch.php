<?php

include("db.php");

$sql = mysqli_query($con, "SELECT Branch FROM branch WHERE Active = 1 ORDER BY BranchID ASC");

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