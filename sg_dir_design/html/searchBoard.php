<?php
	require_once('../php/db_con.php');
	session_start();

	$category = $_GET['category'];
	$search = $_GET['search'];
	$keyword = "%$search%";//pdo에서 like를 사용하기위한 방법
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
	
	//검색결과 갯수 확인
	if ($date1 && $date2)
	{
		$sql = "
			SELECT COUNT(*) FROM board
			WHERE $category LIKE '%$search%' AND DATE(written)
			BETWEEN '$date1' AND '$date2'
		";
	} else {
		$sql = "
			SELECT COUNT(*) FROM board 
			WHERE $category LIKE '%$search%'
		";
	}
	$col_count = $connect->query($sql)->fetchColumn();

	$per = 5;
	$start = ($page - 1) * $per + 1;
	$start -= 1;

	//기간이 설정되었을 경우, $category 컬럼 안에 $search 가 포함된 결과를 찾는데
	//$date1 ~ $date2 사이의 결과를 내림차순으로 출력
	if ($date1 && $date2)
	{
		$sql2 = $connect->prepare("
			SELECT * FROM board
			WHERE $category LIKE :keyword AND DATE(written)
			BETWEEN '$date1' AND '$date2'
			ORDER BY num DESC limit $start, $per
		");
		$sql2->bindParam(':keyword', $keyword);
	}
	else
	{
		$sql2 = $connect->prepare("
			SELECT * FROM board
			WHERE $category LIKE :keyword
			ORDER BY num DESC limit $start, $per
		");
		$sql2->bindParam(':keyword', $keyword);
	}

	$sql2->execute();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<!-- select 태그 option 값에 따라 placeholder 를 다르게하는 함수 -->
	<script>
		function info()
		{
			var opt = document.getElementById("search_opt");
			var opt_val = opt.options[opt.selectedIndex].value;//option의 value 값을 가져옴
			var info = "";

			if (opt_val == 'title')
				info = "제목을 입력해주세요."
			else if (opt_val == 'content')
				info = "내용을 입력해주세요."
			else if (opt_val == 'user_id')
				info = "작성자를 입력해주세요."
			
			document.getElementById("search_box").placeholder = info;
		}
	</script>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/board.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title>검색 결과</title>
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
	<div class="head">검색결과 | <?= $category ?> | <?= $search ?></div>
	<?php
		if ($date1 && $date2)
		{
	?>
		<span class="from_to"><?= $date1 ?> ~ <?= $date2 ?></span>
	<?php 
		} 
	?>
	<table class="tboard">
		<thead>
			<tr>
				<th width=70>번호</th>
				<th width=300>제목</th>
				<th width=120>작성자</th>
				<th width=120>작성일</th>
				<th width=70>조회수</th>
				<th width=70>추천</th>
			</tr>
		</thead>
			<tbody>
				<?php while ($row = $sql2->fetch()) { 
					$out = strlen($row['title']) > 20 ? mb_substr($row['title'], 0, 20, "UTF-8")."..." : $row['title'];	
				?>
				<tr>
					<td><?php echo $row['num']; ?></td>
					<td><a href="viewBoard.php?num=<?=$row['num']?>" style="text-decoration: none; color: black"><?php echo $out; ?></a></td>
					<td><?php echo $row['user_id']; ?></td>
					<td><?php echo $row['written']; ?></td>
					<td><?php echo $row['hit']; ?></td>
					<td><?php echo $row['liked']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		<?php if (!$col_count) { ?>
			<tbody>
				<tr>검색 결과 없음</tr>
			</tbody>
		<?php } ?>
	</table>
	<div class='btn btn-secondary'>
		<a style="text-decoration: none; color: white" href="./writeBoard.php">글쓰기</a>
	</div>
	<div class="bottom">
		<?php
			if($page > 1){
				echo "<a href=\"searchBoard.php?page=1&category=$category&search=$search&date1=$date1&date2=$date2\">[<<] </a>";
			}

			if($page > 1){
				$prev = $page - 1;
				echo "<a href=\"searchBoard.php?page=$prev&category=$category&search=$search&date1=$date1&date2=$date2\">[<]</a>";
			}

			$total_page = ceil($col_count / $per);
			$page_num = 1;
		
			while ($page_num <= $total_page)
			{
				if ($page == $page_num)
					echo "<a style=\"color:hotpink;\" href=\"searchBoard.php?page=$page_num&category=$category&search=$search&date1=$date1&date2=$date2\">$page_num </a>";
				else
					echo "<a href=\"searchBoard.php?page=$page_num&category=$category&search=$search&date1=$date1&date2=$date2\">$page_num</a>";
				$page_num++;
			}

			if($page < $total_page){
				$next = $page + 1;
				echo "<a href=\"searchBoard.php?page=$next&category=$category&search=$search&date1=$date1&date2=$date2\">[>]</a>";
			}

			if($page < $total_page){
				echo "<a href=\"searchBoard.php?page=$total_page&category=$category&search=$search&date1=$date1&date2=$date2\">[>>]</a>";
			}
		?>
	</div>
	<form class="bottom" action="./searchBoard.php" method="get">
		<select name="category" id="search_opt" onchange="info()">
			<option value="title">제목</option>
			<option value="content">내용</option>
			<option value="user_id">작성자</option>
		</select>
		<input class="textform" type="text" name="search" id="search_box" autocomplete="off" placeholder="제목을 입력해주세요." required>
		<input class="submit" type="submit" value="검색">
			<p>
				<input type="date" name="date1">
				~
				<input type="date" name="date2">
			</p>
	</form>
</body>
</html>