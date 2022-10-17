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
				$mediaBaseUrl = 'http://localhost/sg_yb_page/sg_dir_design/file/upload/';
				$mediaRoot = '../file/upload/';

				//$type = $_POST['type'];
				//게시글 정보 변수들
				$user_id = $_POST['user_id'];
				$title = $_POST['title'];
				$content = $_POST['content'];
				$fileNameArray = array();

				if (isset($_FILES['files'])) {

					$files = $_FILES['files'];
					$countfiles = count($files['name']);
				
					for ($i = 0; $i < $countfiles; $i++) {
				
						$filename = $files['name'][$i];
						// 확장자 가져오기. 보통 사용할때는 크게 문제 없어보임.
						// 혹시 확장자가 빈문자열이면 php.ini 업로드 용량 제한 확인해볼 것.
						$extension = explode('/', $files['type'][$i])[1];
						$filePath = $filename . '.' . $extension;
				
						// 관련 블로그 글( https://mytory.net/archives/3011 )
						// 파일 업로드 성공했다면
						if (move_uploaded_file($files['tmp_name'][$i], $mediaRoot . $filePath)) {
							// 기존 경로값을 서버의 파일 경로로 변경.
							$content = str_replace($filename, $mediaBaseUrl . $filePath, $content);
							array_push($fileNameArray, $filePath);
							$arrayString = implode(',', $fileNameArray);
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
				$sql->bindParam(':file_name', $arrayString);
				$sql->execute();
				
				//성공적으로 insert 되었다면, 해당 게시물 num값을 클라이언트에 보내줌
				$id = $connect->lastInsertId();
				echo $id;
			}
			break;
		case 'update' : 
			if (!$_SESSION['user_id'])
				errPwMsg("로그인을 먼저 해주십시오.");
			else if ($_SESSION['user_id'] != $_POST['user_id'])
				errPwMsg("작성자가 아닙니다.");
			else
			{
				$mediaBaseUrl = 'http://localhost/sg_yb_page/sg_dir_design/file/upload/';
				$mediaRoot = '../file/upload/';

				$num = $_POST['num'];
				$update_title = $_POST['title'];
				$update_content = $_POST['content'];
				$fileNameArray = array();

				if (isset($_FILES['files'])) {

					$files = $_FILES['files'];
					$countfiles = count($files['name']);
				
					for ($i = 0; $i < $countfiles; $i++) {
				
						$filename = $files['name'][$i];
						// 확장자 가져오기. 보통 사용할때는 크게 문제 없어보임.
						// 혹시 확장자가 빈문자열이면 php.ini 업로드 용량 제한 확인해볼 것.
						$extension = explode('/', $files['type'][$i])[1];
						$filePath = $filename . '.' . $extension;
				
						// 관련 블로그 글( https://mytory.net/archives/3011 )
						// 파일 업로드 성공했다면
						if (move_uploaded_file($files['tmp_name'][$i], $mediaRoot . $filePath)) {
							// 기존 경로값을 서버의 파일 경로로 변경.
							$update_content = str_replace($filename, $mediaBaseUrl . $filePath, $update_content);
							array_push($fileNameArray, $filePath);
							$arrayString = implode(',', $fileNameArray);
							// 파일 업로드 실패했다면
						} else {
							// 에러는 번호로 나옴. 구글 검색해볼 것.
							$error = $files['error'][0];
							die();
						}
					}
				}
				$arrayStringRes = ',' . $arrayString;

				$sql = $connect->prepare("
					UPDATE board 
					SET title=:update_title, content=:update_content, file=CONCAT(file, :file_name)
					WHERE num=:num
				");
				$sql->bindParam(':update_title', $update_title);
				$sql->bindParam(':update_content', $update_content);
				$sql->bindParam(':file_name', $arrayStringRes);
				$sql->bindParam(':num', $num);
				$sql->execute();

				echo "<script>alert('게시글이 수정되었습니다');";
				echo "window.location.replace('../html/BoardList.php');</script>";
			}
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
				$fileArray = explode(',', $row['file']);
				for ($i = 0; $i < count($fileArray); $i++)
				{
					if (!unlink("../file/upload/".$fileArray[$i]))
					{
						echo "파일 삭제하는 데 문제가 생겼습니다. 관리자에게 문의하십시오.";
						exit;
					}
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