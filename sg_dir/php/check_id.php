<?php
	require_once('./db_con.php');
	$userid = $_GET['userid'];
	// echo "입력된 user_id : " . $userid;
	if (!$userid)
	{
		echo "
			<p>아이디를 입력해주십시오.</p>
			<center><input type=button value=창닫기 onclick='self.close()'></center>
		";
	}
	else
	{
		$sql = "
			SELECT * FROM member WHERE user_id='$userid';
		";
		$result = mysqli_query($connect, $sql);
		$count = mysqli_num_rows($result);
		if ($count < 1)
		{
			echo "
				<p>사용할 수 있는 아이디입니다.</p>
				<center><input type=button value=창닫기 onclick='self.close()'></center>
			";
		}
		else
		{
			echo "
				<p>이미 존재하는 아이디입니다.</p>
				<center><input type=button value=창닫기 onclick='self.close()'></center>
			";
		}
	}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>중복 확인</title>
</head>
</html>