<?php
	require_once('./db_con.php');
	session_start();

	switch ($_GET['mode'])
	{
		case 'write' :
			if (!$_SESSION['user_id'])
				errPwMsg("로그인을 먼저 해주십시오.");
			else
			{
				$type = $_POST['type'];
				$user_id = $_POST['user_id'];
				$title = $_POST['title'];
				$content = $_POST['content'];

				$sql = "
					INSERT INTO board(type, user_id, title, content, written, hit, liked)
					VALUES ('$id', '$user_id', '$title', '$content', now(), 0, 0);
				";
				$result = mysqli_query($connect, $sql);
				if ($result)
				{
					echo "<script>alert('게시글이 작성되었습니다');";
					echo "window.location.replace('board.php');</script>";
				}
				else
					echo mysqli_error($connect);
			}
		break;
	}
?>