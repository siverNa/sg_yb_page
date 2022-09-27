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

	$search = $_GET['search_user'];
	$keyword = "%$search%";//pdo에서 like를 사용하기위한 방법

	//멤버 수 페이징을 위한 작업
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;

	$sql = "
		SELECT COUNT(*) FROM member 
		WHERE user_id LIKE '%$search%'
	";
	$col_count = $connect->query($sql)->fetchColumn();

	$per = 10;//한 페이지당 보이게할 멤버 수
	$start = ($page - 1) * $per + 1;
	$start -= 1;

	$sql2 = $connect->prepare("
		SELECT * FROM member
		WHERE user_id LIKE :keyword
		ORDER BY id limit $start, $per
	");
	$sql2->bindParam(':keyword', $keyword);
	$sql2->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name = "viewport" content="width=device-width, initial-scale=1.0">
	<title>사용자 관리</title>
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
				echo '<div class="helloUser">'.'관리자 '.$_SESSION['user_id'].'님 환영합니다.</div>';
				echo '<div class="outAndUpdate"><a href="../php/signup_process.php?mode=logout">로그아웃 </a> | 
				<a href="./memberModify.php">정보수정</a> | <a href="./adminControl.php">사용자 관리</a>
				</div>';
			}
		?>
	</header>
	<div class="head">user 검색결과 | <?= $search ?></div>
	<table>
		<thead>
			<tr>
				<th width=100>User Id</th>
				<th width=70>관리</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while ($row = $sql2->fetch()) {
			?>
				<tr>
					<td><?= $row['user_id']; ?></td>
					<!-- <td><button type="button" onclick="del_post(<?= $row['id'] ?>)">삭제</button></td> -->
				</tr>
			<?php } ?>
			<?php if (!$col_count) { ?>
				<tr><p>검색 결과 없음</p></tr>
		<?php } ?>
		</tbody>
	</table>
	<!-- 페이징 코드 부분 -->
	<div class=bottom>
		<?php
			if($page > 1){
				echo "<a href=\"adminSearch.php?page=1&search=$search\">[<<] </a>";
			}

			if($page > 1){
				$prev = $page - 1;
				echo "<a href=\"adminSearch.php?page=$prev&search=$search\">[<]</a>";
			}

			$total_member = ceil($col_count / $per);
			$page_num = 1;
		
			while ($page_num <= $total_member)
			{
				if ($page == $page_num)
					echo "<a style=\"color:hotpink;\" href=\"adminSearch.php?page=$page_num&search=$search\">$page_num </a>";
				else
					echo "<a href=\"adminSearch.php?page=$page_num&search=$search\">$page_num</a>";
				$page_num++;
			}

			if($page < $total_member){
				$next = $page + 1;
				echo "<a href=\"adminSearch.php?page=$next&search=$search\">[>]</a>";
			}

			if($page < $total_member){
				echo "<a href=\"adminSearch.php?page=$total_member&search=$search\">[>>]</a>";
			}
		?>
	</div>
	<!-- 페이징 코드 끝 -->
	<form action="./adminSearch.php" method="get">
		<input class="textform" type="text" name="search_user" id="search_box" autocomplete="off" placeholder="유저명을 입력해주세요." required>
		<input class="submit" type="submit" value="검색">
	</form>
</body>
</html>