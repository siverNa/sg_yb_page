<?php 
	require_once('../php/db_con.php');
	session_start();
	
	if ($_SESSION['role'] != 'ADMIN')
	{
		echo "
			<script>
				window.alert('잘못된 접근입니다.');
				history.back(1);
			</script>
		";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
	<title>사용자 관리</title>
</head>
<body>
	
</body>
</html>