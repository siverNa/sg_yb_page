<?php
	require_once('./db_con.php');
	session_start();

	$num = $_GET['num'];
	$user_id = $_SESSION['user_id'];

	$sql_already = "
		SELECT * FROM like_manager WHERE like_post_num='$num' and like_user='$user_id'
	";
	$res_already = mysqli_fetch_array(mysqli_query($connect, $sql_already));
	if (!$res_already)
	{
		echo "<script>alert('추천하지 않은 게시글입니다!');";
		echo "window.history.back()</script>";
		exit;
	}

	$sql = "
		UPDATE board SET liked=liked-1 WHERE num=$num;
		DELETE FROM like_manager WHERE like_post_num='$num' and like_user='$user_id';
	";
	$res = mysqli_multi_query($connect, $sql);
	echo "<script>alert('추천을 취소했습니다!');";
	echo "window.history.back()</script>";
?>