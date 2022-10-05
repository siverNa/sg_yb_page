<?php
	require_once('../php/db_con.php');
	session_start();

	$per = 10;
	$start = 0;

	//게시글들을 내림차순으로 불러오기 위한 코드
	$sql2 = $connect->prepare("
		SELECT * FROM board ORDER BY num DESC limit $start, $per
	");
	$sql2->execute();
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
		<div class="container">
			<div class="main-title">
				<h3>SG YB 게시판 입니다.</h3>
				<a href="./BoardList.php" class="none-style">게시판으로 이동</a>
			</div>
			<div class="main-board">
				<ul>
					<?php while ($row = $sql2->fetch()) { 
						//해당 게시글의 댓글 수 카운트
						$b_num = $row['num'];
						$page_sql = $connect->prepare("
							SELECT COUNT(*) AS cnt FROM reply WHERE board_num=:b_num
						");
						$page_sql->bindParam(':b_num', $b_num);
						$page_sql->execute();
						$reply_count = $page_sql->fetch();

						$out = strlen($row['title']) > 20 ? mb_substr($row['title'], 0, 20, "UTF-8")."..." : $row['title'];
					?>
						<li class="list-border">
							<a href="viewBoard.php?num=<?=$row['num']?>" class="border-content"><?php echo $out; ?></a>
							<span>추천 : <?php echo $row['liked']; ?> | 댓글 : <?php echo $reply_count['cnt']; ?></span>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</body>
</html>