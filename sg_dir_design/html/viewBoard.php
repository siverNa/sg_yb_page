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

	/* 댓글 수를 가져오는 코드 */
	$b_num = $row['num'];
	$page_sql = $connect->prepare("
		SELECT COUNT(*) AS cnt FROM reply WHERE board_num=:b_num
	");
	$page_sql->bindParam(':b_num', $b_num);
	$page_sql->execute();
	$reply_count = $page_sql->fetch();

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
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/board.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
		<!-- 제목 부분 -->
		<div class="view_title"><?= $row['title'] ?></div>
		<div>
			<!-- 제목 밑 작성자 정보 표시 -->
			<div class="view_info">
				<div class="view_member"><?= $row['user_id'] ?></div>
				<div class="view_attr">
					<span class="s_head">추천수 </span>
					<span class="s_body" style="color: #2356FF;"><?=$row['liked']  ?></span> |
					<span class="s_head">댓글 </span>
					<span class="s_body"><?=$reply_count['cnt'] ?></span> |
					<span class="s_head">조회수 </span>
					<span class="s_body"><?=$row['hit']; ?></span> |
					<span class="s_head">작성일 </span>
					<span class="s_body"><?= $row['written'] ?></span>
				</div>
			</div><!-- 제목 밑 작성자 정보 표시 끝 -->
		</div>
		<div class="viewContent">
			<?php
				if (!$row['file'])
				{}
				else
					echo "<img class='img' src='../file/upload/$row[file]'></img></br>";
			?>
			<?= nl2br($row['content']) ?>
			<div class="liked"> 추천 <?=$row['liked']; ?>
				<?php if ($_SESSION['user_id'] != $row['user_id']) { ?>
					<div class="mine">
						<button class="like" id="likeUp" type="button" onclick="window.location.href='../php/like_ok.php?num=<?=$num ?>'">추천하기</button>
						<button class="like" id="likeDown" type="button" onclick="window.location.href='../php/unlike_ok.php?num=<?=$num ?>'">취소</button>
					</div>
				<?php } ?>
			</div>
			<div class="file">이미지 목록 | <a href="../file/upload/<?=$row['file']; ?>" download><?=$row['file']; ?></a></div>
			<div class="underMenu">
				<?php
				if ($row['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] == "ADMIN")
				{
				?>
					<!-- <div> -->
						<a class="modify" href="./updateBoard.php?num=<?= $row['num'] ?>">수정</a>
						<a class="delete" href="#" onclick="confirm_delete('정말로 삭제하시겠습니까?')">삭제</a>
					<!-- </div> -->
				<?php	
					}
					else
					{} 
				?>
				<a class="goList" href="./BoardList.php">목록으로</a>
			</div>
		</div>
		<!-- 댓글 불러오기 -->
		<div class="reply_view">
			<div class="reply_title">댓글</div>
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
					<div class="reply_top">
						<b><?=$r_row['user_id'];?></b>
						<div class="reply_right">
							<?php echo $r_row['date']; ?>
							<?php if ($r_row['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] == "ADMIN") { ?>
								<span class="sep"></span>
								<div class="rep_me">
									<span type="button" id="reply_edit_bt" onclick="edit_show_hide('<?=$r_row['idx'];?>');">수정</span>
									<span class="sep"></span>
									<span type="button" id="reply_delete_bt" onclick="delete_show_hide('<?=$r_row['idx'];?>');">삭제</span>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="dap_to"><?php echo nl2br("$r_row[content]"); ?></div>
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