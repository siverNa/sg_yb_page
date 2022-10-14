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
				$mediaBaseUrl = 'localhost/sg_yb_page/sg_dir_design/file/upload/';
				$mediaRoot = '../file/upload/';

				//$type = $_POST['type'];
				//게시글 정보 변수들
				$user_id = $_POST['user_id'];
				$title = $_POST['title'];
				$content = $_POST['content'];
				//파일 정보 변수들
				// if ($_FILES['file']['name'])
				// {
				// 	$error = $_FILES['file']['error'];
				// 	$tmp_file = $_FILES['file']['tmp_name'];
				// 	$file_name = $_FILES['file']['name'];
				// 	$iconv_file_name = iconv("UTF-8", "EUC-KR", $_FILES['file']['name']);
				// 	$file_size = $_FILES['file']['size'];
				// 	$file_type = $_FILES['file']['type'];
				// 	$dir = "../file/upload/".$iconv_file_name;

				// 	$imgFullName = strtolower($_FILES['file']['name']);
				// 	$imgNameSlice = explode('.', $imgFullName);
				// 	$imgName = $imgNameSlice[0];//파일명
				// 	$imgType = $imgNameSlice[1];//확장자
				// 	//파일 확장자 관리 배열
				// 	$image_can_type = array('jpg', 'jpeg', 'gif', 'png');
				// 	if (array_search($imgType, $image_can_type) === false)
				// 		errPwMsg('jpg, jpeg, gif, png 확장자만 가능합니다.');

				// 	if ($error != UPLOAD_ERR_OK)
				// 	{
				// 		switch ($error)
				// 		{
				// 			case UPLOAD_ERR_INI_SIZE :
				// 				break;
				// 			case UPLOAD_ERR_FORM_SIZE :
				// 				echo "<script>alert('파일이 너무 큽니다.');";
				// 				echo "window.history.back()</script>";
				// 				exit;
				// 				break;
				// 		}
				// 	}
				// 	else
				// 		move_uploaded_file($tmp_file, $dir);
				// }

				if (isset($_FILES['files'])) {

					$files = $_FILES['files'];
					$countfiles = count($files['name']);
				
					for ($i = 0; $i < $countfiles; $i++) {
				
						$filename = $files['name'][$i];
						// 확장자 가져오기. 보통 사용할때는 크게 문제 없어보임.
						// 혹시 확장자가 빈문자열이면 php.ini 업로드 용량 제한 확인해볼 것.
						$extension = explode('/', $files['type'][$i])[1];
						$filePath = $filename . '.' . $extension;
				
						// 해당 코드는 해킹 위험이 있습니다.
						// 관련 블로그 글( https://mytory.net/archives/3011 )
						// 파일 업로드 성공했다면
						if (move_uploaded_file($files['tmp_name'][$i], $mediaRoot . $filePath)) {
							// 기존 경로값을 서버의 파일 경로로 변경.
							$content = str_replace($filename, $mediaBaseUrl . $filePath, $content);
				
							// 파일 업로드 실패했다면
						} else {
							// 에러는 번호로 나옴. 구글 검색해볼 것.
							$error = $files['error'][0];
							die();
						}
					}
				}

				$sql = $connect->prepare("
					INSERT INTO board(user_id, title, content, written, hit, liked, file)
					VALUES (:user_id, :title, :content, now(), 0, 0, :file_name);
				");
				$sql->bindParam(':user_id', $user_id);
				$sql->bindParam(':title', $title);
				$sql->bindParam(':content', $content);
				$sql->bindParam(':file_name', $filePath);
				$sql->execute();
				
				//성공적으로 insert 되었다면, 해당 게시물 num값을 클라이언트에 보내줌
				$id = $connect->lastInsertId();
				echo $id;
			}
			break;
		case 'update' : 
			$num = $_POST['num'];
			$update_title = $_POST['title'];
			$update_content = $_POST['content'];

			$sql = $connect->prepare("
				UPDATE board SET title=:update_title, content=:update_content WHERE num=:num
			");
			$sql->bindParam(':update_title', $update_title);
			$sql->bindParam(':update_content', $update_content);
			$sql->bindParam(':num', $num);
			$sql->execute();

			echo "<script>alert('게시글이 수정되었습니다');";
			echo "window.location.replace('../html/BoardList.php');</script>";
			break;
		
		case 'delete' : 
			$num = $_GET['num'];

			$sql = $connect->prepare("
				SELECT file FROM board WHERE num=:num
			");
			$sql->bindParam(':num', $num);
			$sql->execute();
			$row = $sql->fetch();
			if ($row['file'] != NULL)
			{
				if (!unlink("../file/upload/".$row['file']))
				{
					echo "파일 삭제하는 데 문제가 생겼습니다. 관리자에게 문의하십시오.";
					exit;
				}
			}

			$del_sql = $connect->prepare("
				DELETE FROM board WHERE num=:num
			");
			$del_sql->bindParam(':num', $num);
			$del_sql->execute();

			echo "<script>alert('게시글이 삭제되었습니다');";
			echo "window.location.replace('../html/BoardList.php');</script>";
			break;
	}
?>