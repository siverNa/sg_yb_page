<?php
	session_start();

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
		case 'login' :
			$user_id = $_POST['user_id'];
			$user_password = $_POST['user_password'];
			
			$sql = "
				SELECT * FROM member WHERE user_id = '$user_id';
			";
			$res = mysqli_fetch_array(mysqli_query($connect, $sql));

			if (!$user_id)
			{	
				errPwMsg("아이디를 입력해주세요.");
				echo "<script>window.location.replace('../html/login.html');</script>";
			}
			else if (!$user_password)
			{
				errPwMsg("비밀번호를 입력해주세요.");
				echo "<script>window.location.replace('../html/login.html');</script>";
			}
			else if (!isset($res['user_id']))
			{
				errPwMsg("존재하지 않는 아이디입니다.");
				echo "<script>window.location.replace('../html/login.html');</script>";
			}
			else if (password_verify($user_password, $res['user_password']))
			{
				errPwMsg("비밀번호가 일치하지 않습니다.");
				echo "<script>window.location.replace('../html/login.html');</script>";
			}

			if (!isset($res))
			{
				errPwMsg("로그인 중 에러가 발생했습니다. 관리자에게 문의하십시오.");
				echo "<script>window.location.replace('../html/login.html');</script>";
			}
			else
			{
				$_SESSION['user_id'] = $res['user_id'];

				echo "<script>alert('로그인에 성공했습니다!');<script>";
				header('Location: ../html/main.php');
			}
			
		break;
		case 'logout' :
			session_start();
			session_unset();
			session_destroy();
			header('Location: ../html/main.php');
		break;
	}

	mysqli_close($connect);
?>