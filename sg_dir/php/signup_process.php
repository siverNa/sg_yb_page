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
				/*
					password_hash():단방향 알고리즘(복호화 불가)
					로그인시, password_verify() 로 비교해 일치하는 지 확인
				*/
				$encrypt_password = password_hash($user_password, PASSWORD_DEFAULT);

				$sql = "
				INSERT INTO member (user_id, user_password)
				VALUES ('$user_id', '$encrypt_password');";
			
				$result = mysqli_query($connect, $sql);
				if ($result === false)
					echo mysqli_error($connect);
				
				header('Location: ../html/signup_ok.html');
			}
		break;
	}

	mysqli_close($connect);
?>