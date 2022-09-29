<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name = "viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="../css/style.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<title>main page</title>
	</head>
	<body>
		<nav class="nav-container">
		<div style="width: 100px;"></div>
		<img src="../img/kakao.png" alt="logo" style="width: 30px;">
		<div class="nav-item">SG YB page</div>
			<?php if (!isset($_SESSION['user_id'])) { ?>
					<div style="flex-grow: 1;"></div>
					<button type='button' class='btn btn-secondary' onclick="location.href='./signup.html'">회원가입(signup)</button>
					<div style="padding: 20px;"></div>
					<button type='button' class='btn btn-secondary' onclick="location.href='./login.html'">로그인</button>
			<?php } else { ?>
					<div class="helloUser"><?php echo $_SESSION['user_id']; ?>님 환영합니다.</div>
					<div style="flex-grow: 1;"></div>
					<button type='button' class='btn btn-secondary' onclick="location.href='../php/signup_process.php?mode=logout'">로그아웃</button>
					<div style="padding: 20px;"></div>
					<button type='button' class='btn btn-secondary' onclick="location.href='./memberModify.php'">정보 수정</button>
			<?php } ?>
		</nav>
		<p>이 페이지는 main page 입니다</p>
		<p><a href="./BoardList.php">게시판으로 이동</a></p>
	</body>
</html>