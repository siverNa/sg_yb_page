<?php
	require_once('../php/db_con.php');
	session_start();

	//이 아래의 코드는 페이징을 위한 게시글 갯수 카운팅 및 보여줄 게시글 갯수 설정
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
	
	$sql1 = $connect->prepare("SELECT * FROM board");
	$sql1->execute();
	$total_page = $sql1->rowCount();
	
	$per = 5;
	$start = ($page - 1) * $per + 1;
	$start -= 1;

	//게시글들을 내림차순으로 불러오기 위한 코드
	$sql2 = $connect->prepare("
		SELECT * FROM board ORDER BY num DESC limit $start, $per
	");
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
				<a href="./memberModify.php">정보수정</a>
				</div>';
			}
		?>
	</header>
	<table>
		<thead>
			<tr>
				<th width=70>번호</th>
				<th width=300>제목</th>
				<th width=120>작성자</th>
				<th width=120>작성일</th>
				<th width=70>조회수</th>
				<th width=70>추천</th>
				<th width=70>댓글</th>
			</tr>
		</thead>
		<?php while ($row = $sql2->fetch()) { 
			//해당 게시글의 댓글 수 카운트
			$b_num = $row['num'];
			$page_sql = $connect->prepare("
				SELECT COUNT(*) AS cnt FROM reply WHERE board_num=:b_num
			");
			$page_sql->bindParam(':b_num', $b_num);
			$page_sql->execute();
			$reply_count = $page_sql->fetch();
		?>
			<tbody>
				<tr>
					<td><?php echo $row['num']; ?></td>
					<td><a href="viewBoard.php?num=<?=$row['num']?>"><?php echo $row['title']; ?></a></td>
					<td><?php echo $row['user_id']; ?></td>
					<td><?php echo $row['written']; ?></td>
					<td><?php echo $row['hit']; ?></td>
					<td><?php echo $row['liked']; ?></td>
					<td><?php echo $reply_count['cnt']; ?></td>
				</tr>
			</tbody>
		<?php } ?>
	</table>
	<!-- 페이징 코드 부분 -->
	<div class=bottom>
		<?php
			if($page > 1){
				echo "<a href=\"BoardList.php?page=1\">[<<] </a>";
			}

			if($page > 1){
				$prev = $page - 1;
				echo "<a href=\"BoardList.php?page=$prev\">[<]</a>";
			}

			$total_page = ceil($total_page / $per);
			$page_num = 1;
		
			while ($page_num <= $total_page)
			{
				if ($page == $page_num)
					echo "<a style=\"color:hotpink;\" href=\"BoardList.php?page=$page_num\">$page_num </a>";
				else
					echo "<a href=\"BoardList.php?page=$page_num\">$page_num</a>";
				$page_num++;
			}

			if($page < $total_page){
				$next = $page + 1;
				echo "<a href=\"BoardList.php?page=$next\">[>]</a>";
			}

			if($page < $total_page){
				echo "<a href=\"BoardList.php?page=$total_page\">[>>]</a>";
			}
		?>
	</div>
	<!-- 페이징 코드 끝 -->
	<div><a href="./writeBoard.php">글쓰기</a></div>
	<form action="./searchBoard.php" method="get">
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