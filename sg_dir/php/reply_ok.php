<?php
	require_once('./db_con.php');
	session_start();

	$board_num = $_GET['board_num'];
	$user_id = $_SESSION['user_id'];
	if ($board_num && $user_id && $_POST['content'])
	{
		$sql = "
			INSERT INTO reply(board_num, user_id, content, date)
			VALUES('$board_num', '$user_id', '".$_POST['content']."', now())
		";
		$result = mysqli_query($connect, $sql);
		if ($result)
		{
			echo "<script>alert('댓글이 작성되었습니다.'); 
			location.href='../html/viewBoard.php?num=$board_num';</script>";
		}
		else
		{
			echo "<script>alert('댓글 작성에 실패했습니다.'); 
			history.back();</script>";
		}
	}
	else
	{
		echo "<script>alert('댓글 작성에 실패했습니다.'); 
		history.back();</script>";
	}
?>