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
				//$type = $_POST['type'];
				$user_id = $_POST['user_id'];
				$title = $_POST['title'];
				$content = $_POST['content'];

				$sql = "
					INSERT INTO board(user_id, title, content, written, hit, liked)
					VALUES ('$user_id', '$title', '$content', now(), 0, 0);
				";
				$result = mysqli_query($connect, $sql);
				if ($result)
				{
					echo "<script>alert('게시글이 작성되었습니다');";
					echo "window.location.replace('../html/BoardList.php');</script>";
				}
				else
					echo mysqli_error($connect);
			}
			break;
		case 'update' : 
			$num = $_POST['num'];
			$update_title = $_POST['title'];
			$update_content = $_POST['content'];

			$sql = "
				UPDATE board SET title='$update_title', content='$update_content' WHERE num='$num'
			";
			$result = mysqli_query($connect, $sql);
			if ($result)
			{
				echo "<script>alert('게시글이 수정되었습니다');";
				echo "window.location.replace('../html/BoardList.php');</script>";
			}
			else
				echo mysqli_error($connect);

			break;
	}
?>