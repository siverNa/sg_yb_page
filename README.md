# sg_yb_page
sg 와 yb의 게시판 만들기 협업 프로젝트입니다.

This is a collaboration project to create a bulletin board between SG and yb.

# 사이트

- Notion 페이지 : https://viridian-attempt-8cf.notion.site/8971f1c3b4ee4e82b62052f5c059346a
- 게시판 주소 : http://sgybpage.dothome.co.kr/main.php

# 역할
- sg(siverNa) : 백엔드(Back-end), 프론트엔드(Front-end) 보조
- yb(youngsubyun) : 프론트엔드(Front-end)
  
# 스택
1. 사용한 언어
   - sg :
      <img src="https://img.shields.io/badge/PHP-777BB4?style=flat&logo=PHP&logoColor=white"> 
      (PHP 7.4, DotHome 호스팅 기준), 
      <img src="https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=HTML5&logoColor=white"> , 
      <img src="https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=CSS3&logoColor=white">, 
      <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=JavaScript&logoColor=white">, 
      <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=MySQL&logoColor=white">
   - yb : <img src="https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=HTML5&logoColor=white"> , 
      <img src="https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=CSS3&logoColor=white">
  
2. 사용한 도구
	- <img src="https://img.shields.io/badge/Visual%20Studio%20Code-007ACC?style=flat&logo=Visual%20Studio%20Code&logoColor=white"> 등
 
3. 사용한 협업 툴
	- <img src="https://img.shields.io/badge/GitHub-181717?style=flat&logo=GitHub&logoColor=white">, 
      <a href="https://viridian-attempt-8cf.notion.site/8971f1c3b4ee4e82b62052f5c059346a"><img src="https://img.shields.io/badge/Notion-000000?style=flat&logo=Notion&logoColor=white"></a> 등

4. 사용한 오픈소스
	- Summernote : https://summernote.org/

# 시연 영상
## 회원가입
1. 사용가능한 id인 경우
   <img src="https://user-images.githubusercontent.com/69504543/197808033-85c0600a-8dbb-4786-ad7e-f0b701b0087c.gif">

2. 사용불가능한 id인 경우
   <img src="https://user-images.githubusercontent.com/69504543/197808358-a8f3bf31-a6bd-4568-bdb3-8fb71ed85f93.gif">

3. 회원가입 성공
   <img src="https://user-images.githubusercontent.com/69504543/197808567-3dcecb04-5579-4e9d-953d-05066341e211.gif">

## 로그인
1. 로그인 실패(id 미입력, 존재하지않는 id, 비밀번호 틀림 등)
   <img src="https://user-images.githubusercontent.com/69504543/198267351-9693354f-44fe-4b4b-af10-549458266696.gif">

2. 로그인 성공
   <img src="https://user-images.githubusercontent.com/69504543/198267325-8f0ff19b-e115-419c-86be-8767db966cf8.gif">

## 게시판 생성, 수정, 검색, 삭제
1. 게시글 생성
   <img src="https://user-images.githubusercontent.com/69504543/198268211-508367dd-d343-4e37-8beb-7e8fa8c1fe5e.gif">

2. 게시글 수정
   <img src="https://user-images.githubusercontent.com/69504543/198268207-43c8b0fd-6ecb-4e46-a5f4-34b80d62b2a1.gif">

3. 게시글 검색
   <img src="https://user-images.githubusercontent.com/69504543/198268217-ee28a1e7-4490-4418-a603-9ef1075aca8f.gif">

4. 게시글 삭제
   <img src="https://user-images.githubusercontent.com/69504543/198268215-70fc9f0f-c956-4901-a026-062daee506d3.gif">

## 추천 및 취소
1. 추천 및 취소 불가  
   (이미 추천한 경우, 추천하지 않았는데 취소하는 경우, 자신에게 추천하려는 경우)
   <img src="https://user-images.githubusercontent.com/69504543/198277561-480490cd-f6d2-46e2-80fd-d9f4940ac79b.gif">

2. 추천 및 취소
   <img src="https://user-images.githubusercontent.com/69504543/198277564-4d1c68aa-6835-4565-ad12-29dc8390d774.gif">

## 댓글 생성, 수정, 삭제
1. 댓글 생성
   <img src="https://user-images.githubusercontent.com/69504543/198269250-dec608b4-e17b-466e-8364-2d681b530101.gif">

2. 댓글 수정
   <img src="https://user-images.githubusercontent.com/69504543/198269241-5c7918c2-1657-4d57-96c0-046aacdbfae6.gif">

3. 댓글 삭제
   <img src="https://user-images.githubusercontent.com/69504543/198269235-a7bbea89-3251-48c5-ae38-d7eb3fbcfd15.gif">

## 사용자 정보 수정
(비밀번호 변경만 구현되어 있습니다.)

1. 정보 수정 실패  
   (비밀번호 미입력, 비밀번호 일치하지않음, 이전 비밀번호와 동일)
   <img src="https://user-images.githubusercontent.com/69504543/198269630-1c052ccc-dae0-428e-a730-943d818261e8.gif">

2. 정보 수정 성공
   <img src="https://user-images.githubusercontent.com/69504543/198269622-d25644ff-a5fc-4857-b72a-7939196ec4d3.gif">

## 관리자 권한
1. 유저 비활성화(관리자 시점)
   <img src="https://user-images.githubusercontent.com/69504543/198271543-a3156c7c-c3c6-4065-b9a8-af2faf0cc590.gif">

2. 유저 비활성화(사용자 시점)
   <img src="https://user-images.githubusercontent.com/69504543/198271546-c8fe1e1c-a817-4f31-93f0-9a2ce3b120db.gif">

3. 유저 활성화
   <img src="https://user-images.githubusercontent.com/69504543/198271551-dd79482a-8149-4084-9da2-ade623947e71.gif">

4. 게시글 삭제
   <img src="https://user-images.githubusercontent.com/69504543/198271538-077fa2e6-7a29-45cd-a188-28206d9e5e1c.gif">

5. 댓글 삭제
   <img src="https://user-images.githubusercontent.com/69504543/198271542-83044d4c-7291-4089-8302-a0fdd9cfcdad.gif">