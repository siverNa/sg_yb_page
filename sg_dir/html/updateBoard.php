<?php
	require_once('../php/db_con.php');
	session_start();

	$num = $_GET['num'];
	// $sql = "
	// 	SELECT * FROM board WHERE num='$num'
	// ";
	// $result = mysqli_query($connect, $sql);
	// $row = mysqli_fetch_array($result);
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
	<title>수정하기</title>
</head>
<body>
	<header>
		<ul>
			<a href="./main.php"><li>메인으로 돌아가기</li></a>
			<a href="./Board_list.php"><li>게시판으로 이동</li></a>
		</ul>
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