<?php
include("db.php");

//$userID = '27';
$userID = $_POST['userID'];
$DataList = [];
$newArr = [];
$stylePackList = [];
$msg = "fail";
//$userID = $_POST['userID'];
if (!empty($userID)) {

	$stylePackSql = mysqli_query($con, "SELECT sp.namepack as stylePack, cs.courseStyleName as styleName, uhs.times as haveTime, sp.packimg as imgUrl 
									FROM user_has_stylepack uhs 
									LEFT JOIN user u ON u.UserID = uhs.user_UserID 
									LEFT JOIN stylepack sp ON sp.id = uhs.stylepack_id 
									LEFT JOIN coursestyle cs ON cs.courseStyleID = uhs.coursestyle_courseStyleID
									WHERE uhs.user_UserID = '" . $userID . "' AND uhs.times <> 0");

	if (!empty($stylePackSql)) {
		while ($arr = mysqli_fetch_array($stylePackSql, MYSQLI_ASSOC)) {
			foreach ($arr as $key => $val) {
				$DataList[$key] = $val;
			}
			array_push($stylePackList, $DataList);
		}
	}

}
if (!empty($stylePackList)) {
	$msg = "success";
}
echo json_encode(array('msg' => $msg, 'styleData' => $stylePackList));


?>