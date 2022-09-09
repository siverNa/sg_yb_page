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
				echo '<div class="helloUser">'.$_SESSION['user_id'].'님 환영합니다.</div>';
				echo '<div class="outAndUpdate"><a href="../php/signup_process.php?mode=logout">로그아웃 </a> | 
				<a href="member/update.php">정보수정</a>
				</div>';
			}
		?>
		</header>
		<section>
			<div class="mainSection">
				<div class="updateTitle">회원정보 수정</div>
				<form action="../php/signup_process.php?mode=update" method="post">
					<input type="hidden" name="user_id" value="<?= $row['user_id']; ?>">
					<table class="updateTable">
						<tr>
							<td>아이디</td>
							<td><?= $row['user_id']; ?></td>
						</tr>
						<tr>
							<td>현재 비밀번호</td>
							<td><input type="password" name="prevPw"></td>
						</tr>
						<tr>
							<td>새 비밀번호</td>
							<td><input type="password" name="newPw1"></td>
						</tr>
						<tr>
							<td>새 비밀번호 확인</td>
							<td><input type="password" name="newPw2"></td>
						</tr>
					</table>
					<div class="updateButtons">
						<input type="submit" value="수정하기">
						<input type="button" value="취소" onclick="history.back()">
					</div>
				</form>
			</div>
		</section>
	</body>
</html>