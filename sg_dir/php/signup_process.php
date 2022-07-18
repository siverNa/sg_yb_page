<?php
	function errPwMsg($msg) {
		echo "
			<script>
				window.alert('$msg');
				history.back(1);
			</script>
		";
		exit;
	}

	require_once('./db_con.php');

	switch($_GET['mode'])
	{
		case 'signup' :
			$user_id = $_POST['decide_id'];
			$user_password = $_POST['user_password'];
			$user_password_confirm = $_POST['user_password_confirm'];
			
			//비밀번호가 동일한지 확인
			if ($user_password != $user_password_confirm)
				errPwMsg("비밀번호가 일치하지 않습니다.");
			else
			{
				$sql = "
				INSERT INTO member (user_id, user_password)
				VALUES ('$user_id', '$user_password');";
			
				$result = mysqli_query($connect, $sql);
				if ($result === false)
					echo mysqli_error($connect);
				
				header('Location: ../html/signup_ok.html');
			}
		break;
	}

	mysqli_close($connect);
?>