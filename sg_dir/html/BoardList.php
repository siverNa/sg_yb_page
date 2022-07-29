<?php
	require_once('../php/db_con.php');
	session_start();

	$sql = "
		SELECT * FROM board ORDER BY num DESC
	";
	$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
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
				<a href="member/update.php">정보수정</a>
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
	</table>
	<div><a href="./writeBoard.php">글쓰기</a></div>
</body>
</html>