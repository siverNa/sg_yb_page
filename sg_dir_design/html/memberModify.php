<?php
	session_start();
	require_once('../php/db_con.php');

	$sql = $connect->prepare("
		SELECT * FROM member WHERE user_id=:user_id
	");
	$sql->bindParam(":user_id", $_SESSION['user_id']);
	$sql->execute();
	$row = $sql->fetch();
?>
<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name = "viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" href="../css/signup.css" type="text/css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<title>회원정보 수정</title>
	</head>
	<body>
		<header>
			<nav class="nav-container">
			<div style="width: 100px;"></div>
			<img src="../img/logoCat.png" alt="logo" style="width: 30px;">
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
			<div class="mainSection">
				<div style="font-size: 30px;">회원정보 수정</div>
				<form class="signupbox" action="../php/signup_process.php?mode=update" method="post">
					<input type="hidden" name="user_id" value="<?= $row['user_id']; ?>">
					<table class="updateTable">
						<tr class="id-box">
							<td class="idtxt">아이디</td>
							<td class="id"
							style="width:200px;height:30px;font-size:20px;"><?= $row['user_id']; ?></td>
						</tr>
						<tr class="pwd-box">
							<td class="pwdtxt">현재 비밀번호</td>
							<td class="pwd"
							style="width:200px;height:30px;font-size:20px;"><input type="password" name="prevPw"></td>
						</tr>
						<tr>
							<td class="pwdtxt">새 비밀번호</td>
							<td class="pwd"
							style="width:200px;height:30px;font-size:20px;"><input type="password" name="newPw1"></td>
						</tr>
						<tr>
							<td class="pwdtxt">새 비밀번호 확인</td>
							<td class="pwd"
							style="width:200px;height:30px;font-size:20px;"><input type="password" name="newPw2"></td>
						</tr>
				</table>
					<div class="updateButtons">
						<input class="nav-btn" type="submit" value="수정하기">
						<input class="nav-btn" type="button" value="취소" onclick="history.back()">
					</div>
				</form>
			</div>
		</section>
	</body>
</html>