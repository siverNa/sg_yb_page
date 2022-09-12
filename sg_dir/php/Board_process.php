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
				if ($_FILES['file']['name'])
				{
					$error = $_FILES['file']['error'];
					$tmp_file = $_FILES['file']['tmp_name'];
					$file_name = $_FILES['file']['name'];
					$iconv_file_name = iconv("UTF-8", "EUC-KR", $_FILES['file']['name']);
					$file_size = $_FILES['file']['size'];
					$file_type = $_FILES['file']['type'];
					$dir = "../file/upload/".$iconv_file_name;

					$imgFullName = strtolower($_FILES['file']['name']);
					$imgNameSlice = explode('.', $imgFullName);
					$imgName = $imgNameSlice[0];//파일명
					$imgType = $imgNameSlice[1];//확장자
					//파일 확장자 관리 배열
					$image_can_type = array('jpg', 'jpeg', 'gif', 'png');
					if (array_search($imgType, $image_can_type) === false)
						errPwMsg('jpg, jpeg, gif, png 확장자만 가능합니다.');

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
				}
				// $sql = "
				// 	INSERT INTO board(user_id, title, content, written, hit, liked, file)
				// 	VALUES ('$user_id', '$title', '$content', now(), 0, 0, '$file_name');
				// ";
				// $result = mysqli_query($connect, $sql);
				// if ($result)
				// {
				// 	echo "<script>alert('게시글이 작성되었습니다');";
				// 	echo "window.location.replace('../html/BoardList.php');</script>";
				// }
				// else
				// 	echo mysqli_error($connect);
				$sql = $connect->prepare("
					INSERT INTO board(user_id, title, content, written, hit, liked, file)
					VALUES (:user_id, :title, :content, now(), 0, 0, :file_name);
				");
				$sql->bindParam(':user_id', $user_id);
				$sql->bindParam(':title', $title);
				$sql->bindParam(':content', $content);
				$sql->bindParam(':file_name', $file_name);
				$sql->execute();
				
				echo "<script>alert('게시글이 작성되었습니다');";
				echo "window.location.replace('../html/BoardList.php');</script>";
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
				SELECT file FROM board WHERE num='$num'
			";
			$result = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($result);
			if ($row['file'] != NULL)
			{
				if (!unlink("../file/upload/".$row['file']))
				{
					echo "파일 삭제하는 데 문제가 생겼습니다. 관리자에게 문의하십시오.";
					exit;
				}
			}

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