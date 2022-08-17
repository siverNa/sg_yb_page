<?php
	require_once('./db_con.php');
	session_start();

	$num = $_GET['num'];
	$user_id = $_SESSION['user_id'];

	$sql_check = "
		SELECT * FROM board WHERE num=$num
	";
	$res_check = mysqli_fetch_array(mysqli_query($connect, $sql_check));
	if ($user_id == $res_check['user_id'])
	{
		echo "<script>alert('자신의 게시글입니다!');";
		echo "window.history.back()</script>";
		exit;
	}

	$sql_check2 = "
		SELECT * FROM like_manager WHERE like_post_num='$num' and like_user='$user_id'
	";
	$res_check2 = mysqli_fetch_array(mysqli_query($connect, $sql_check2));
	if ($res_check2)
	{
		echo "<script>alert('이미 추천한 게시글입니다!');";
		echo "window.history.back()</script>";
		exit;
	}

	$sql = "
		UPDATE board SET liked=liked+1 WHERE num=$num;
		INSERT INTO like_manager(like_post_num, like_user) VALUES ('$num', '$user_id');
	";
	$res = mysqli_multi_query($connect, $sql);
	echo "<script>alert('추천했습니다!');";
	echo "window.history.back()</script>";
?>