<?php
	require_once('../php/db_con.php');
	session_start();
	if (!$_SESSION['user_id'])
		errPwMsg("로그인 후 작성할 수 있습니다.");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>게시글 작성</title>
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
	<h2>글 작성</h2>
	<form action="../php/board_process.php?mode=write" method="post">
		<input type="hidden" name="id" value="board">
		<input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
		<p><input type="text" name="title" placeholder="제목" required></p>
		<textarea name="content" cols="500" rows="500" placeholder="본문" required></textarea>
		<input type="submit" value="글쓰기">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="취소" onclick="history.back(1)">
	</form>
</body>
</html>