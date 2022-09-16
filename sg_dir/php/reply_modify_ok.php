<?php
	require_once('./db_con.php');
	session_start();

	$reply_num = $_POST['reply_num'];
	$board_num = $_POST['board_num'];

	$sql3 = $connect->prepare("
		UPDATE reply SET content=:content WHERE idx=:reply_num
	");
	$sql3->bindParam(':content', $_POST['content']);
	$sql3->bindParam(':reply_num', $reply_num);
	$sql3->execute();
	$update_res = $sql3->rowCount();

	if ($update_res)
	{
		echo "
			<script type='text/javascript'>alert('수정되었습니다.'); 
			location.replace('../html/viewBoard.php?num=$board_num');</script>
		";
	}
	else
	{
		echo "
			<script type='text/javascript'>alert('수정에 실패했습니다. 관리자에게 문의해주세요.'); 
			location.replace('../html/viewBoard.php?num=$board_num');</script>
		";
	}
?>