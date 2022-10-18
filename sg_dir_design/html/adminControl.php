<?php 
	require_once('../php/db_con.php');
	session_start();
	
	if ($_SESSION['role'] != 'ADMIN')
	{
		echo "
			<script>
				window.alert('잘못된 접근입니다.');
				history.back(1);
			</script>
		";
	}

	//멤버 수 페이징을 위한 작업
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;

	$sql1 = $connect->prepare("SELECT * FROM member");
	$sql1->execute();
	$total_member = $sql1->rowCount();

	$per = 10;//한 페이지당 보이게할 멤버 수
	$start = ($page - 1) * $per + 1;
	$start -= 1;

	$sql2 = $connect->prepare("
		SELECT * FROM member limit $start, $per
	");
	$sql2->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<script>
		function deactivate_user(user_id)
		{
			var reason = prompt('사유를 입력해주세요(최대 50자)');
			if (reason)
				location.href = `../php/user_control.php?mode=deactivate&user_id=${user_id}&reason=${reason}`;
			else
			{
				alert('취소하였습니다.');
				history.back(1);
			}
		}
		function activate_user(user_id)
		{
			if (confirm('활성화하시겠습니까?'))
				location.href = `../php/user_control.php?mode=active&user_id=${user_id}`;
			else
			{
				alert('취소하였습니다.');
				history.back(1);
			}
		}
	</script>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/board.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title>사용자 관리</title>
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
			<?php } else { ?>
				<div class="helloUser"><?php echo "관리자 ".$_SESSION['user_id']; ?>님 환영합니다.</div>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='../php/signup_process.php?mode=logout'">로그아웃</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./memberModify.php'">정보 수정</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./adminControl.php'">관리자 페이지</button>
			<?php } ?>
		</nav>
	</header>
	<div class="board_title"><a href="./adminControl.php">관리자 페이지</a></div>
	<table class="tboard" style="text-align: center;">
		<thead>
			<tr>
				<th width=100>User Id</th>
				<th width=70>상태</th>
				<th width=70>관리</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while ($row = $sql2->fetch()) {
			?>
				<tr>
					<td><?= $row['user_id']; ?></td>
					<td>
						<?php
							if ($row['status'] == "active")
								echo "<div style='color:green'>".$row['status']."</div>";
							else if ($row['status'] == "deactivate")
								echo "<div style='color:red'>".$row['status']."</div>";
						?>
					</td>
					<td>
						<?php if ($row['status'] == "active" && $row['role'] == "USER") {?>
							<button type="button" onclick="deactivate_user('<?= $row['user_id'] ?>')">비활성화</button>
						<?php } else if ($row['status'] == "deactivate") { ?>
							<button type="button" onclick="activate_user('<?= $row['user_id'] ?>')">활성화</button>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<!-- 페이징 코드 부분 -->
	<div class=bottom>
		<?php
			if($page > 1){
				echo "<a href=\"adminControl.php?page=1\">[<<] </a>";
			}

			if($page > 1){
				$prev = $page - 1;
				echo "<a href=\"adminControl.php?page=$prev\">[<]</a>";
			}

			$total_member = ceil($total_member / $per);
			$page_num = 1;
		
			while ($page_num <= $total_member)
			{
				if ($page == $page_num)
					echo "<a style=\"color:hotpink;\" href=\"adminControl.php?page=$page_num\">$page_num </a>";
				else
					echo "<a href=\"adminControl.php?page=$page_num\">$page_num</a>";
				$page_num++;
			}

			if($page < $total_member){
				$next = $page + 1;
				echo "<a href=\"adminControl.php?page=$next\">[>]</a>";
			}

			if($page < $total_member){
				echo "<a href=\"adminControl.php?page=$total_member\">[>>]</a>";
			}
		?>
	</div>
	<!-- 페이징 코드 끝 -->
	<form class="bottom" action="./adminSearch.php" method="get">
		<input class="textform" type="text" name="search_user" id="search_box" autocomplete="off" placeholder="유저명을 입력해주세요." required>
		<input class="submit" type="submit" value="검색">
	</form>
</body>
</html>