<?php
	require_once('./db_con.php');
	session_start();

	$num = $_GET['num'];
	$user_id = $_SESSION['user_id'];

	$sql_check = $connect->prepare("
		SELECT * FROM board WHERE num=:num
	");
	$sql_check->bindParam(':num', $num);
	$sql_check->execute();
	$res_check = $sql_check->fetch();
	if ($user_id == $res_check['user_id'])
	{
		echo "<script>alert('자신의 게시글입니다!');";
		echo "window.history.back()</script>";
		exit;
	}

	$sql_check2 = $connect->prepare("
		SELECT * FROM like_manager WHERE like_post_num=:num and like_user=:user_id
	");
	$sql_check2->bindParam(':num', $num);
	$sql_check2->bindParam(':user_id', $user_id);
	$sql_check2->execute();
	$res_check2 = $sql_check2->fetch();
	if ($res_check2)
	{
		echo "<script>alert('이미 추천한 게시글입니다!');";
		echo "window.history.back()</script>";
		exit;
	}

	$u_sql = $connect->prepare("
		UPDATE board SET liked=liked+1 WHERE num=:num;
	");
	$u_sql->bindParam(':num', $num);
	$u_sql->execute();

	$i_sql = $connect->prepare("
		INSERT INTO like_manager(like_post_num, like_user) VALUES (:num, :user_id);
	");
	$i_sql->bindParam(':num', $num);
	$i_sql->bindParam(':user_id', $user_id);
	$i_sql->execute();

	echo "<script>alert('추천했습니다!');";
	echo "window.history.back()</script>";
?>