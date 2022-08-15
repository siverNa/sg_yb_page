<?php
	require_once('../php/db_con.php');
	session_start();

	$num = $_GET['num'];
	$sql = "
		SELECT * FROM board WHERE num='$num'
	";
	$result = mysqli_query($connect, $sql);
	$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script>
		function confirm_delete(text)
		{
			const selValue = confirm(text);
			if (selValue == true)
				location.href = "../php/board_process.php?mode=delete&num=<?= $row['num'] ?>";
			else if (selValue == false)
				history.back(1);
		}
	</script>
	<title>게시글 목록</title>
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
		<div><?= $row['title'] ?></div>
		<div>
			<div><?= $row['user_id'] ?></div>
			<div><?= $row['written'] ?></div>
			<p><div class="hit"> 조회수 <?=$row['hit']; ?></div></p>

		</div>
		<div>
			<?= $row['content'] ?>
		</div>
		<p><div class="liked"> 추천 <?=$row['liked']; ?></div></p>
		<p class="file"><a href="../file/upload/<?=$row['file']; ?>" download><?=$row['file']; ?></a></p>
		<div><a href="./BoardList.php">목록으로</a></div>
		<?php
			if ($row['user_id'] != $_SESSION['user_id'])
			{}
			else
			{
		?>
			<div>
				<a href="./updateBoard.php?num=<?= $row['num'] ?>">수정</a>
				<a href="#" onclick="confirm_delete('정말로 삭제하시겠습니까?')">삭제</a>
			</div>
		<?php } ?>
	</section>
</body>
</html>