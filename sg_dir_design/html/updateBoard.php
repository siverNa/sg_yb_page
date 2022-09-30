<?php
	require_once('../php/db_con.php');
	session_start();

	$num = $_GET['num'];
	$sql = $connect->prepare("
		SELECT * FROM board WHERE num=:num
	");
	$sql->bindParam(':num', $num);
	$sql->execute();
	$row = $sql->fetch();

	if ($row['user_id'] != $_SESSION['user_id'])
		errPwMsg("수정 권한이 없습니다.")
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title>수정하기</title>
</head>
<body>
	<header>
		<nav class="nav-container">
		<div style="width: 100px;"></div>
		<img src="../img/kakao.png" alt="logo" style="width: 30px;">
		<div class="nav-item"><a href="./main.php" style="text-decoration: none; color: white">SG YB page</a></div>
			<?php if (!isset($_SESSION['user_id'])) { ?>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./signup.html'">회원가입(signup)</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./login.html'">로그인</button>
			<?php } else if ($_SESSION['role'] == "USER") { ?>
				<div class="helloUser"><?php echo $_SESSION['user_id']; ?>님 환영합니다.</div>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='../php/signup_process.php?mode=logout'">로그아웃</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./memberModify.php'">정보 수정</button>
			<?php } else if ($_SESSION['role'] == "ADMIN") { ?>
				<div class="helloUser"><?php echo "관리자 ".$_SESSION['user_id']; ?>님 환영합니다.</div>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='../php/signup_process.php?mode=logout'">로그아웃</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./memberModify.php'">정보 수정</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./adminControl.php'">사용자 관리</button>
			<?php } ?>
		</nav>
	</header>
	<section>
		<form action="../php/board_process.php?mode=update" method="post">
			<!--<input type="hidden" name="type" value="board">-->
			<input type="hidden" name="num" value="<?= $row['num'] ?>">
			<input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
			<p><input type="text" name="title" value="<?= $row['title'] ?>" required></p>
			<textarea name="content" cols="100" rows="50" required><?= $row['content'] ?></textarea>
			<?php 
				if(!$row['file'])
				{} 
				else { ?>
					<?= $row['file'] ?><br>
			<?php } ?>
			<input type="file" name="image" value="<?= $row['file'] ?>">
			<div>
				<input type="submit" value="수정하기">&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" value="취소" onclick="history.back(1)">
			</div>
		</form>
	</section>
</body>
</html>