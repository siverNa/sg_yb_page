<?php
	require_once('./db_con.php');

	switch($_GET['mode'])
	{
		case 'signup' :
			$user_id = $_POST['user_id'];
			$user_password = $_POST['user_password'];
			$user_password_confirm = $_POST['user_password_confirm'];

			$sql = "
				INSERT INTO member (user_id, user_password)
				VALUES ('$user_id', '$user_password');";
			
			$result = mysqli_query($connect, $sql);
			if ($result === false)
				echo mysqli_error($connect);
			
			header('Location: ../html/signup_ok.html');
		break;
	}

	mysqli_close($connect);
?>