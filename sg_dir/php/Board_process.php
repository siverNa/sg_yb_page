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
				//게시글 정보 변수들
				$user_id = $_POST['user_id'];
				$title = $_POST['title'];
				$content = $_POST['content'];
				//파일 정보 변수들
				$error = $_FILES['file']['error'];
				$tmp_file = $_FILES['file']['tmp_name'];
				$file_name = $_FILES['file']['name'];
				$file_size = $_FILES['file']['size'];
				$file_type = $_FILES['file']['type'];
				$dir = "../file/upload/".$file_name;

				if ($error != UPLOAD_ERR_OK)
				{
					switch ($error)
					{
						case UPLOAD_ERR_INI_SIZE :
							break;
						case UPLOAD_ERR_FORM_SIZE :
							echo "<script>alert('파일이 너무 큽니다.');";
							echo "window.history.back()</script>";
							exit;
							break;
					}
				}
				else
					move_uploaded_file($tmp_file, $dir);

				$sql = "
					INSERT INTO board(user_id, title, content, written, hit, liked, file)
					VALUES ('$user_id', '$title', '$content', now(), 0, 0, '$file_name');
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
		
		case 'delete' : 
			$num = $_GET['num'];
			$sql = "
				DELETE FROM board WHERE num='$num'
			";
			$result = mysqli_query($connect, $sql);
			if ($result)
			{
				echo "<script>alert('게시글이 삭제되었습니다');";
				echo "window.location.replace('../html/BoardList.php');</script>";
			}
			else
				echo mysqli_error($connect);
			break;
	}
?>