<?php
	session_start();
	
	if ($_SESSION['role'] != 'ADMIN')
	{
		echo "
			<script>
				window.alert('잘못된 접근입니다.');
				history.back(1);
			</script>
		";
	}
?>
<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name = "viewport" content="width=device-width, initial-scale=1.0">
		<title>main page</title>
	</head>
	<body>
		<header>
			<?php
				if (!isset($_SESSION['user_id']))
				{
					echo '<p><a href="./signup.html">회원가입(signup)</a>';
					echo '<a href="./login.html">로그인(signin)</a></p>';
				}
				else
				{
					echo '<div class="helloUser">'.'관리자 '.$_SESSION['user_id'].'님 환영합니다.</div>';
					echo '<div class="outAndUpdate"><a href="../php/signup_process.php?mode=logout">로그아웃 </a> | 
					<a href="./memberModify.php">정보수정</a> | <a href="./adminControl.php">사용자 관리</a>
					</div>';
				}
			?>
		</header>
		<p>이 페이지는 관리자의 main page 입니다</p>
		<p><a href="./BoardList.php">게시판으로 이동</a></p>
	</body>
</html>