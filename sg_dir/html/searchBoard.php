<?php
	require_once('../php/db_con.php');
	session_start();

	$category = $_GET['category'];
	$search = $_GET['search'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	/*
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
	*/
	
	//검색결과 갯수 확인
	if ($date1 && $date2)
	{
		$sql = "
			SELECT * FROM board
			WHERE $category LIKE '%$search%' AND DATE(written)
			BETWEEN '$date1' AND '$date2'
		";
	} else {
		$sql = "
			SELECT * FROM board 
			WHERE $category LIKE '%$search%'";
	}
	$col_result = mysqli_query($connect, $sql);
	$col_count = mysqli_num_rows($col_result);

	/*
	$per = 20;
	$start = ($page - 1) * $per;
	*/

	//기간이 설정되었을 경우, $category 컬럼 안에 $search 가 포함된 결과를 찾는데
	//$date1 ~ $date2 사이의 결과를 내림차순으로 출력
	if ($date1 && $date2)
	{
		$sql = "
			SELECT * FROM board
			WHERE $category LIKE '%$search%' AND DATE(written)
			BETWEEN '$date1' AND '$date2'
			ORDER BY num DESC
		";
	}
	else
	{
		$sql = "
			SELECT * FROM board
			WHERE $category LIKE '%$search%'
			ORDER BY num DESC
		";
	}

	$result = mysqli_query($connect, $sql);
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
			else if (opt_val == 'written')
				info = "작성자를 입력해주세요."
			
			document.getElementById("search_box").placeholder = info;
		}
	</script>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>검색 결과</title>
</head>
<body>
	<header>
		<ul>
			<a href="./main.php"><li>메인으로 돌아가기</li></a>
			<a href="./Boardlist.php"><li>게시판으로 이동</li></a>
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
	<div class="head">검색결과 | <?= $category ?> | <?= $search ?></div>
	<?php
		if ($date1 && $date2)
		{
	?>
		<span class="from_to"><?= $date1 ?> ~ <?= $date2 ?></span>
	<?php 
		} 
	?>
	<table>
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
		<?php while ($row = mysqli_fetch_array($result)) { ?>
			<tbody>
				<tr>
					<td><?php echo $row['num']; ?></td>
					<td><a href="viewBoard.php?num=<?=$row['num']?>"><?php echo $row['title']; ?></a></td>
					<td><?php echo $row['user_id']; ?></td>
					<td><?php echo $row['written']; ?></td>
					<td><?php echo $row['hit']; ?></td>
					<td><?php echo $row['liked']; ?></td>
				</tr>
			</tbody>
		<?php } ?>
		<?php if (!$col_count) { ?>
			<tbody>
				<tr><p>검색 결과 없음</p></tr>
			</tbody>
		<?php } ?>
	</table>
	<div><a href="./writeBoard.php">글쓰기</a></div>
	<form action="./searchBoard.php" method="get">
		<select name="category" id="search_opt" onchange="info()">
			<option value="title">제목</option>
			<option value="content">내용</option>
			<option value="written">작성자</option>
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