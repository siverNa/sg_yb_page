<?php
	require_once('./db_con.php');
	session_start();

	$num = $_GET['num'];
	$user_id = $_SESSION['user_id'];

	$sql_already = $connect->prepare("
		SELECT * FROM like_manager WHERE like_post_num=:num and like_user=:user_id
	");
	$sql_already->bindParam(':num', $num);
	$sql_already->bindParam(':user_id', $user_id);
	$sql_already->execute();
	$res_already = $sql_already->fetch();
	if (!$res_already)
	{
		echo "<script>alert('추천하지 않은 게시글입니다!');";
		echo "window.history.back()</script>";
		exit;
	}

	$u_sql = $connect->prepare("
		UPDATE board SET liked=liked-1 WHERE num=:num;
	");
	$u_sql->bindParam(':num', $num);
	$u_sql->execute();

	$d_sql = $connect->prepare("
		DELETE FROM like_manager WHERE like_post_num=:num and like_user=:user_id;
	");
	$d_sql->bindParam(':num', $num);
	$d_sql->bindParam(':user_id', $user_id);
	$d_sql->execute();

	echo "<script>alert('추천을 취소했습니다!');";
	echo "window.history.back()</script>";
?>