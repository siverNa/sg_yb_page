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

	if ($_SESSION['user_id'] != $row['user_id'])
	{
		$hit_sql = $connect->prepare("
			UPDATE board SET hit=hit+1 WHERE num=:num
		");
		$hit_sql->bindParam(':num', $num);
		$hit_sql->execute();
	}
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
		function edit_show_hide(id) {
			var con = document.getElementById("reply_edit_" + id);
			if(con.style.display=='none'){
				con.style.display = 'block';
			}
			else
				con.style.display = 'none';
		}
		function delete_show_hide(id) {
			var con = document.getElementById("reply_delete_" + id);
			if(con.style.display=='none'){
				con.style.display = 'block';
			}
			else
				con.style.display = 'none';
		}
	</script>
	<title>게시글 목록</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
	<header>
		<ul>
			<a href="./main.php"><li>메인으로 돌아가기</li></a>
			<a href="./BoardList.php"><li>게시판으로 이동</li></a>
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
				<a href="./memberModify.php">정보수정</a>
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
		<div class="viewContent">
			<?php
				if (!$row['file'])
				{}
				else
					echo "<img src='../file/upload/$row[file]'></img></br>";
			?>
			<?= nl2br($row['content']) ?>
		</div>
		<p><div class="liked"> 추천 <?=$row['liked']; ?></div></p>
			<?php if ($_SESSION['user_id'] != $row['user_id']) { ?>
				<div class=mine>
					<button class=like type="button" onclick="window.location.href='../php/like_ok.php?num=<?=$num ?>'">추천하기</button>
					<button class=like type="button" onclick="window.location.href='../php/unlike_ok.php?num=<?=$num ?>'">취소</button>
				</div>
			<?php } ?>
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
		<!-- 댓글 불러오기 -->
		<div class="reply_view">
			<h3>댓글</h3>
			<?php
				$r_sql = $connect->prepare("
					SELECT * FROM reply
					WHERE board_num=:num
					ORDER BY idx
				");
				$r_sql->bindParam(':num', $num);
				$r_sql->execute();
				while ($r_row = $r_sql->fetch()) {
			?>
				<div class="reply_log">
					<div><b><?=$r_row['user_id'];?></b></div>
					<div class="dap_to"><?php echo nl2br("$r_row[content]"); ?></div>
					<div class="dap_to rep_me"><?php echo $r_row['date']; ?></div>
					<?php if ($r_row['user_id'] == $_SESSION['user_id']) { ?>
					<div class="rep_me">
						<button type="button" id="reply_edit_bt" onclick="edit_show_hide('<?=$r_row['idx'];?>');">수정</button>
						<button type="button" id="reply_delete_bt" onclick="delete_show_hide('<?=$r_row['idx'];?>');">삭제</button>
					</div>
					<?php } ?>
					<!-- 댓글 수정 폼 -->
					<div id="reply_edit_<?=$r_row['idx']; ?>" style="display:none">
						<form action="../php/reply_modify_ok.php" method="post">
							<input type="hidden" name="user_id" value="<?=$r_row['user_id']; ?>">
							<input type="hidden" name="board_num" value="<?=$num; ?>">
							<input type="hidden" name="reply_num" value="<?=$r_row['idx']; ?>">
							<textarea name="content" cols="30" rows="10"><?php echo $r_row['content']; ?></textarea>
							<p>
								<input type="submit" value="수정하기">
								<button type="button" onclick="edit_show_hide('<?=$r_row['idx'];?>');">취소</button>	
							</p>
						</form>
					</div>
					<!-- 댓글 삭제 폼 -->
					<div id="reply_delete_<?=$r_row['idx']; ?>" style="display:none">
						<form action="../php/reply_delete_ok.php" method="post">
							<input type="hidden" name="reply_num" value="<?=$r_row['idx']; ?>">
							<input type="hidden" name="board_num" value="<?=$num; ?>">
							<p>
								<input type="submit" value="삭제하기">
								<button type="button" onclick="delete_show_hide('<?=$r_row['idx'];?>');">취소</button>	
							</p>
						</form>
					</div>
				</div>
			<?php } ?>
			
			<!-- 댓글 작성 -->
			<div class="reply_write">
				<form action="../php/reply_ok.php?board_num=<?=$row['num']; ?>" method="post">
					<?php if ($_SESSION['user_id']) { ?>
						<div style="margin-top:10px; ">
							<textarea name="content" id="reply_content"></textarea>
							<button id="reply_button" class="rp_bt">댓글</button>
						</div>
					<?php } ?>
				</form>
			</div>
		</div><!-- 댓글 불러오기 끝 -->
	</section>
</body>
</html>