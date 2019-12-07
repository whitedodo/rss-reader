<?php
/*
  <JasperRSSReader>  Copyright (C) 2018  Jaspers
  
	File: reader.php
	Author: Jaspers
	Created by 2018-07-14
	Goal: RSS Reader
	Description:
*/
?>
<?php
	require('function.php');
?>
<?php
	$rssFn = new RSSFunction();
  $start = $rssFn->getExecutionTime();  // 수행시간 측정(시작)
  
  header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<title>Jasper - RSS Reader</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<script>
  function showRSS(str) {
    if (str.length==0) {
      document.getElementById("rssOutput").innerHTML="";
      return;
    }
    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById("rssOutput").innerHTML=this.responseText;
      }
    }
    xmlhttp.open("GET","getrss.php?q="+str,true);
    xmlhttp.send();
  }
</script>
<link rel="stylesheet" type="text/css" href="./css/mystyle.css">
<script src="./script/myscripts.js"></script>
</head>
<body>

<h3>RSS Reader - My Jasper</h3>
<hr>

<form>
  <select onchange="showRSS(this.value)">
    <option value="">Select an RSS-feed(RSS-피드를 선택하세요):</option>
    <option value="Rabbit2Me-boardStory">Rabbit2Me(Board/Story) - 트래픽/미지원</option>
    <option value="yyman">도도의 초록누리</option>
    <option value="gokit-s20101215">gokit-S20101215</option>
    <option value="KoreaGov-All">Korea - Policy briefing News(정책브리핑)</option>
    <option value="KoreaGov-Moe">Korea - MoE News(교육부)</option>
    <option value="KoreaGov-MSIT">Korea - MSIT News(과학기술정보통신부)</option>
    <option value="KoreaGov-Mnd">Korea - MND News(국방부)</option>
    <option value="KoreaGov-Moel">Korea - Moel News(고용노동부)</option>
    <option value="KoreaGov-Mw">Korea - Mw News(보건복지부)</option>
    <option value="KoreaGov-Mogef">Korea - Mogef News(여성가족부)</option>
    <option value="Zdnet-News">Zdnet News(Zdnet 뉴스)</option>
    <option value="ACM-CommunicationNetworking">ACM-Subject "Communication Networking"-(ACM 주제:커뮤니케이션 네트워킹)</option>
    <option value="Daum-Sisa">Daum News Sisa(다음 뉴스) 시사</option>
    <option value="Daum-Society">Daum News Society(다음 뉴스) 사회</option>
    <option value="Daum-Economic">Daum News Economic(다음 뉴스) 경제</option>
  </select>
</form>

<!-- 출력(Output) -->
<br>

<div id="rssOutput"></div>

<!-- 하단(Footer) -->
<?php
  $rssFn->getCopyright();
?>

<!-- 수행시간(Execution time) -->
<?php

  $end = $rssFn->getExecutionTime();
  $time = $end - $start;
  echo "\t\t\t<br>";
  echo "\t\t\t<span class=\"time_font\">";
  echo "수행시간(Execution Time):";
  echo $time . "초(Sec)</span>";
  
  echo "\t\t\t<br>";
  echo "\t\t\t<span class=\"time_font\">";
  echo "시작시간(Start Time):";
  echo $start . "초(Sec)</span>";
  echo "\t\t\t<br>";
  echo "\t\t\t<span class=\"time_font\">";
  echo "종료시간(End Time):";
  echo $end . "초(Sec)</span>";
?>

</body>
</html> 