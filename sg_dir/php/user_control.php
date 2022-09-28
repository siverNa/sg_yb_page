<?php
	require_once('./db_con.php');
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

	switch($_GET['mode'])
	{
		case 'deactivate' :
			$user_id = $_GET['user_id'];
			$reason = $_GET['reason'];

			$sql = $connect->prepare("
				UPDATE member SET status='deactivate', reason=:reason WHERE user_id=:user_id
			");
			$sql->bindParam(':reason', $reason);
			$sql->bindParam(':user_id', $user_id);
			$sql->execute();

			echo "<script>alert('비활성화 작업이 완료되었습니다.');";
			echo "window.location.replace('../html/adminControl.php');</script>";
			break;
		case 'active' :
			$user_id = $_GET['user_id'];

			$sql = $connect->prepare("
				UPDATE member SET status='active', reason=NULL WHERE user_id=:user_id
			");
			$sql->bindParam(':user_id', $user_id);
			$sql->execute();

			echo "<script>alert('활성화 작업이 완료되었습니다.');";
			echo "window.location.replace('../html/adminControl.php');</script>";
			break;
	}
?>