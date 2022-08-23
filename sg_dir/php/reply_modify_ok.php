<?php
	require_once('../php/db_con.php');
	session_start();

	$reply_num = $_POST['reply_num'];
	$board_num = $_POST['board_num'];

	$sql1 = "
		SELECT * FROM reply WHERE idx='$reply_num'
	";
	$reply_res = mysqli_fetch_array(mysqli_query($connect, $sql1));

	$sql2 = "
		SELECT * FROM board WHERE num='$board_num'
	";
	$board_res = mysqli_fetch_array(mysqli_query($connect, $sql2));

	$sql3 = "
		UPDATE reply SET content='".$_POST['content']."' WHERE idx='$reply_num';
	";

	echo "
		<script type='text/javascript'>alert('수정되었습니다.'); 
		location.replace('../html/viewBoard.php?num=$board_num');</script>
	";
?>