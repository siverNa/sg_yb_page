<?php	
	require_once('../php/db_con.php');
	session_start();
	if (!$_SESSION['user_id'])
		errPwMsg("로그인 후 작성할 수 있습니다.");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/board.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	
	<!-- include libraries(jQuery, bootstrap) -->
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
	
	<script src="../js/summernote/summernote-lite.js"></script>
	<script src="../js/summernote/lang/summernote-ko-KR.js"></script>

	<link rel="stylesheet" href="../css/summernote/summernote-lite.css">

	<script>
		$(document).ready(function() {
			//여기 아래 부분
			$('#summernote').summernote({
				height: 300,                 // 에디터 높이
				minHeight: null,             // 최소 높이
				maxHeight: null,             // 최대 높이
				focus: true,                  // 에디터 로딩후 포커스를 맞출지 여부
				lang: "ko-KR",					// 한글 설정
				placeholder: '최대 2048자까지 쓸 수 있습니다',	//placeholder 설정	
				callbacks: {
					// 파일 업로드시 동작하는 코드
					// onImageUpload 이지만 비디오 드랍도 동작함.
					onImageUpload: function(files) {
						setFiles(files);
					},
					// 클립보드에 있는(윈도우 + 쉬프트 + s) 한 경우에 에디터에서 붙여넣기(컨트롤+v) 하는 경우
					// 섬머노트 기본 이미지 붙여넣기 기능을 막는 코드.
					// 없으면 이미지 2장씩 들어간다. ( 하나는 setFiles(file 형태) 로 하나는 base64(string 형태) 로 )
					onPaste: function(e)
					{
						const clipboardData = e.originalEvent.clipboardData;
						if (clipboardData && clipboardData.items && clipboardData.items.length) {
							const item = clipboardData.items[0];
							//indexOf(searchValue[, fromIndex]) 호출한 String 객체에서 주어진 값과 일치하는 첫 번째 인덱스를 반환.
							//일치하는 값이 있으면 인덱스 리턴, 없으면 -1 리턴
							// 붙여넣는게 파일이고, 이미지이면
							if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
								// 이벤트 막음
								e.preventDefault();
							}
						}
					}
		   		},
			});
		});

		function summit() {
			const button = event.srcElement;
			button.disabled = true;

			// user_id, content를 가지고와서 formdata 로 전송
			const user_id = document.getElementById('user_id').value;
			const title = document.getElementById('title').value;
			let content = $('#summernote').summernote('code');

			const formData = new FormData;

			// 에디터 내부에 img, iframe 태그가 남아있는지 확인.
			const summernoteWriteArea = document.getElementsByClassName("note-editable")[0];
			const srcArray = [];
			// getElementsByTagName 가 반환하는 형태는 HTMLCollection 인데 실제 배열이 없어서 forEach() 가 없음..
			// 그래서 Array.from 로 array 로 만들어줌.
			const iframeTags = Array.from(summernoteWriteArea.getElementsByTagName('iframe'));
			const imgsTags = Array.from(summernoteWriteArea.getElementsByTagName('img'));

			// 람다 사용함. ( 공부해보면 좋을 것.. )
			iframeTags.forEach(iframe => {
				srcArray.push(iframe.src);
			});
			imgsTags.forEach(img => {
				srcArray.push(img.src);
			});

			const filesArrayLenght = filesArray.length;
			for (let i = 0; i < filesArrayLenght; i++) {
				const itrFile = filesArray[i];

				// 에디터 안에 주소가 쓰이고 있으면
				if (srcArray.includes(itrFile.name)) {

					console.log(itrFile.name);

					// 이유는 모르겠는데 서버에서 받는 파일 이름은 스키마나 baseUrl값이 없어져있었다.
					// 그래서 여기서 문자열을 변환해주도록 만들었다.
					const pathSplitArray = itrFile.name.split('/');
					content = content.replace(itrFile.name, pathSplitArray[pathSplitArray.length - 1]);

					// 왼쪽부터 (서버에서 받을때 사용할 파일 배열키, 파일)
					// 서버에서 항상 배열로 받을려면 키 뒤에 '[]' 필요.
					formData.append('files[]', itrFile);
				}
				// 이제 url 객체는 필요없으니까 메모리 해제
				URL.revokeObjectURL(itrFile.name);
			}

			formData.append("user_id", user_id);
			formData.append("title", title);
			formData.append("content", content);
			console.log(content);

			const httpRequest = new XMLHttpRequest();
			httpRequest.onreadystatechange = () => {
				if (httpRequest.readyState === XMLHttpRequest.DONE) {
					if (httpRequest.status === 200) {
						console.log(httpRequest.response);
						location.href = "./viewBoard.php?num=" + httpRequest.response;
					} else {
						alert("게시물 등록중 오류가 발생했습니다.");
						button.disabled = false;
					}
				}
			}
			httpRequest.open('post', '../php/Board_process.php?mode=write', true);
			httpRequest.send(formData);

		}
		
		// filesArray 는 서버로 전송하기 전에 임시로 uri들을 들고 있는 배열이다.
		const filesArray = [];

		// 드래그앤 드랍시 동작하는 코드
		function setFiles(files) {
			const filesLenght = files.length;
			for (let i = 0; i < filesLenght; i++) {
				const file = files[i];

				if (file.type.match('image.*')) {
					// 임시 url 생성하는 부분
					const url = URL.createObjectURL(file);
					file.name = url;
					// filesArray 이름을 방금 받은 url 로 담아둔다. (나중에 서버로 파일 보낼때 필요)
					filesArray.push(new File([file], url, {
						type: file.type
					}));

					// 에디터에 이미지 붙여넣기.
					const imgElement = document.createElement("img");
					imgElement.src = url;
					const summernoteWriteArea = document.getElementsByClassName("note-editable")[0];
					summernoteWriteArea.appendChild(imgElement);


				} else if (file.type.match('video.*')) {
					// 임시 url 생성하는 부분
					const url = URL.createObjectURL(file);
					console.log(file.type);
					filesArray.push(new File([file], url, {
						type: file.type
					}));

					const videoIframe = document.createElement("iframe");
					videoIframe.src = url;
					videoIframe.width = "640px";
					videoIframe.height = "480px";
					videoIframe.frameBorder = "0";
					videoIframe.className = "note-video-clip";

					// 에디터에 영상 붙여넣기 note-editable 에 붙여넣으면 됌.
					const summernoteWriteArea = document.getElementsByClassName("note-editable")[0];
					summernoteWriteArea.appendChild(videoIframe);

					// 비디오나 이미지가 아니면
				} else {
					alert('지원하지 않는 파일 타입입니다.');
				}
			}
		}
	</script>
	
	<title>게시글 작성</title>
</head>
<body>
	<header>
		<nav class="nav-container">
		<div style="width: 100px;"></div>
		<img src="../img/kakao.png" alt="logo" style="width: 30px;">
		<div class="nav-item"><a href="./main.php" style="text-decoration: none; color: white">SG YB page</a></div>
			<?php if (!isset($_SESSION['user_id'])) { ?>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./signup.html'">회원가입(signup)</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./login.html'">로그인</button>
			<?php } else if ($_SESSION['role'] == "USER") { ?>
				<div class="helloUser"><?php echo $_SESSION['user_id']; ?>님 환영합니다.</div>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='../php/signup_process.php?mode=logout'">로그아웃</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./memberModify.php'">정보 수정</button>
			<?php } else if ($_SESSION['role'] == "ADMIN") { ?>
				<div class="helloUser"><?php echo "관리자 ".$_SESSION['user_id']; ?>님 환영합니다.</div>
				<div style="flex-grow: 1;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='../php/signup_process.php?mode=logout'">로그아웃</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./memberModify.php'">정보 수정</button>
				<div style="padding: 20px;"></div>
				<button type='button' class='btn btn-secondary' onclick="location.href='./adminControl.php'">사용자 관리</button>
			<?php } ?>
		</nav>
	</header>
	<div class="write_head">글쓰기</div>
	<!-- <form action="../php/board_process.php?mode=write" method="post" enctype="multipart/form-data">
		<input type="hidden" name="type" value="board">
		<input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
		<div class="title_group">
			<div class="title_group_front">
				<span class="title_group_text">제목</span>
			</div>
			<input class="title_input" type="text" name="title" required>
		</div>
		<p><input type="file" name="file" id="input_file"></p>
		summernote 에디터
		<textarea id="summernote" name="content"></textarea>
		<div class="write_button_wrapper">
			<input class="write_edit_button" type="submit" value="작성">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="write_edit_button" type="button" value="취소" onclick="history.back(1)">
		</div>
	</form> -->
	<div>
		<input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id'] ?>">
		<div class="title_group">
			<div class="title_group_front">
				<span class="title_group_text">제목</span>
			</div>
			<input class="title_input" id="title" type="text" name="title" required>
		</div>
		<!-- summernote 에디터 -->
		<textarea id="summernote" name="content"></textarea>
		<div class="write_button_wrapper">
			<button class="write_edit_button" onclick="summit()">작성</button>
			<button class="write_edit_button" onclick="history.back(1)">취소</button>
		</div>
	</div>
</body>
</html>