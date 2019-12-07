<?php
/*
  <JasperRSSReader>  Copyright (C) 2018  Jaspers
  
	File: getrss.php
	Author: Jaspers
	Created by 2018-07-14
	Goal: RSS Reader
	Description:
	2018-07-14 / Jasper / getRss($q) - RSS 실행
	2018-07-14 / Jasper / getLink($q) - RSS URL 변환
	2018-07-14 / Jasper / reader($xml) - RSS 출력
  2018-07-14 / Jasper / readerInfo($ret) - RSS 정보 출력
  2018-07-14 / Jasper / readerData($ret) - RSS Item 데이터 출력
  2018-07-14 / Jasper / get_url_fsockopen($url) - HTTP / HTTPS 지원
  2018-07-14 / Jasper / getFromUrl($url, $method = 'GET') - HTTPS 지원
*/
?>
<?php
  header('Content-Type: text/html; charset=UTF-8');
	require('function.php');
	require('simple_html_dom.php');
?>
<?php

$q = $_GET["q"];  // Parameter URL 입력, $_GET["q"]
getRss($q);

// 출력
function getRss($q){

  $xml = getLink($q);
  reader($xml);
}

function getLink($q){

  $xml = "";
  
  // Feed URL 선택
  if ($q == "yyman" ){
    $xml = ("http://yyman.tistory.com/rss");
  } elseif($q == "Rabbit2Me-boardStory") {
    //$xml = ("http://192.168.0.12/board/rss_paper.php?name=story");
    //$xml = ("http://rabbit2me.dothome.co.kr/board/rss_paper.php?name=story");
  } elseif ( $q == "gokit-s20101215") {
    $xml = ("http://gokit.kumoh.ac.kr/~s20101215/textcube/rabbitblog/rss");
  } elseif ( $q == "KoreaGov-All") {
    $xml = ("http://www.korea.kr/rss/policy_all.xml");
  } elseif ( $q == "KoreaGov-Moe") {
    $xml = ("http://www.korea.kr/rss/dept_moe.xml");
  } elseif ( $q == "KoreaGov-Mnd") {
    $xml = ("http://www.korea.kr/rss/dept_mnd.xml");
  } elseif ( $q == "KoreaGov-MSIT") {
    $xml = ("http://www.msit.go.kr/cms/www/m_con/news/report/rss.xml");
  } elseif ( $q == "KoreaGov-Moel") {
    $xml = ("http://www.korea.kr/rss/dept_moel.xml");
  } elseif ( $q == "KoreaGov-Mw") {
    $xml = ("http://www.korea.kr/rss/dept_mw.xml");
  } elseif ( $q == "KoreaGov-Mogef") {
    $xml = ("http://www.korea.kr/rss/dept_mogef.xml");
  } elseif ( $q == "Zdnet-News") {
    $xml = ("https://www.zdnet.com/news/rss.xml");
  } elseif ( $q == "ACM-CommunicationNetworking") {
    $xml = ("https://cacm.acm.org/browse-by-subject/communications-networking.rss");
  } elseif ( $q == "Daum-Sisa") {
    $xml = ("http://media.daum.net/syndication/empathy.rss");
  } elseif ( $q == "Daum-Society") {
    $xml = ("http://media.daum.net/syndication/society.rss");
  } elseif ( $q == "Daum-Economic") {
    $xml = ("http://media.daum.net/syndication/economic.rss");
  }
  
  return $xml;

}
function reader($xml){
  
  $rssFn = new RSSFunction();
  
  if ( !empty($xml) ){
    $html = $rssFn->get_url_fsockopen($xml); 
  	$html = str_get_html($html);
  
    $ret = $html->find("channel");
    
    readerInfo($ret);
    readerData($ret);
  
  }
  
}

function readerInfo($ret){
  
  $index = 0;
  
  $startPos = -1;
  $endPos = -1;
  
  $channel_title = $channel_desc = "";
  $target1 = $target2 = "";
  
  // 제목 찾기
  foreach($ret as $val1){
  
    if ( $index == 0 ){
    
      $target1 = "le>";
      $target2 = "</ti";
      foreach($val1->find('title') as $val2){
      
        $startPos = strpos($val2, $target1, 0 ) + 3;
        $endPos = strpos($val2, $target2, $startPos);
        
        $adjStartPos = 3;
        $adjEndPos = 0;
        
        //echo substr($val2, $startPos, ($endPos - $adjEndPos) - $startPos);
        $channel_title = substr($val2, $startPos, ($endPos - $adjEndPos) - $startPos);
        $channel_title = str_replace("<![CDATA[", "", $channel_title);
        $channel_title = str_replace("]]>", "", $channel_title);
        $index++;
        break;
      }
      
      $target1 = "<description>";
      $target2 = "</description>";
      foreach($val1->find('description') as $val2){
      
        $startPos = strpos($val2, $target1, 0 );
        $endPos = strpos($val2, $target2, $startPos);
        
        $adjStartPos = 13;
        $adjEndPos = 13;
        
        $channel_desc = substr($val2, $startPos + $adjStartPos, ($endPos - $adjEndPos) - $startPos);
        $channel_desc = str_replace("<![CDATA[", "", $channel_desc);
        $channel_desc = str_replace("]]>", "", $channel_desc);
        
        $index++;
        break;
      } 
    }
    else{
      break;
    }
    
  } // Title 출력

  echo ("<p><b> $channel_title </b>" );
  echo ("<br>");
  echo ($channel_desc . "</p>\n");
}

// readerData($ret)
function readerData($ret){
  
  $index = 0;
  
  $startPos = -1;
  $endPos = -1;
  
  $item_title = $var;
  $item_link = $var;
  $item_desc = $var;
  $item_pubDate = $var;
  
  $target1 = $target2 = "";
    
  echo "\t\t<table class=\"tg\" style=\"width:100%;\">\n";
  echo "\t\t\t";
  echo "<tr>\n";
  echo "\t\t\t\t";
  echo "<th class=\"tg-q7k0\" style=\"width:20%;\">\n";
  echo "<b>";
  echo "제목(Title)\n";
  echo "</b>";
  echo "</th>";
  echo "<th class=\"tg-q7k0\">\n";
  echo "<b>";
  echo "내용(Description)\n";
  echo "</b>";
  echo "</th>\n";
  echo "<th class=\"tg-q7k0\" style=\"width:18%;\">\n";
  echo "<b>";
  echo "작성일자(pubDate)\n";
  echo "</b>";
  echo "</th>\n";
  echo "\t\t\t";
  echo "</tr>\n";

  // 출력
  foreach($ret as $val1){
  
    $item_title = $item_link = $item_desc = $item_pubDate = "";

    $target1 = "<title>";
    $target2 = "</title>";

    $target3 = "<link>";
    $target4 = "</link>";
    
    $target5 = "<description>";
    $target6 = "</description>";
    
    $target7 = "<pubdate>";
    $target8 = "</pubdate>";
    
    $target9 = "<content:encoded>";
    $target10 = "</content:encoded>";
    
    foreach($val1->find('item') as $val2){
      
      // title
      $startPos = strpos($val2, $target1, 0);
      $endPos = strpos($val2, $target2, $startPos);
            
      $adjStartPos = 7;
      $adjEndPos = 7;
      
      //echo substr($val2, $startPos, ($endPos - $adjEndPos) - $startPos);
      $item_title = substr($val2, $startPos + $adjStartPos, ($endPos - $adjEndPos) - $startPos);
      $item_title = str_replace("<![CDATA[", "", $item_title);
      $item_title = str_replace("]]>", "", $item_title);
      
      // link
      $startPos = strpos($val2, $target3, 0 );
      $endPos = strpos($val2, $target4, $startPos);
      $adjStartPos = 6;
      $adjEndPos = 6;
      
      $item_link = substr($val2, $startPos + $adjStartPos, ($endPos - $adjEndPos) - $startPos);
      $item_link = str_replace("<![CDATA[", "", $item_link);
      $item_link = str_replace("]]", "", $item_link);
      $item_link = str_replace("]", "", $item_link);
      $item_link = str_replace(">", "", $item_link);
      
      // description
      $startPos = strpos($val2, $target5, 0);
      $endPos = strpos($val2, $target6, $startPos);
      $adjStartPos = 14;
      $adjEndPos = 0;
      
      $item_desc = substr($val2, $startPos + adjStartPos, ($endPos - $adjEndPos) - $startPos);
      $item_desc = str_replace($target5, "", $item_desc);
      $item_desc = str_replace("]]>", "", $item_desc);
      $item_desc = str_replace("&lt;", "<", $item_desc);
      $item_desc = str_replace("&gt;", ">", $item_desc);
      
      // description(content_enc)
      if ( empty($item_desc) ){
        
        $startPos = strpos($val2, $target9, 0);
        $endPos = strpos($val2, $target10, $startPos);
        $adjStartPos = 17;
        $adjEndPos = 0;
        
        $item_desc = substr($val2, $startPos + adjStartPos, ($endPos - $adjEndPos) - $startPos);
        $item_desc = str_replace($target9, "", $item_desc);
        $item_desc = str_replace("<![CDATA[", "", $item_desc);
        $item_desc = str_replace("]]>", "", $item_desc);
        $item_desc = str_replace("&lt;", "<", $item_desc);
        $item_desc = str_replace("&gt;", ">", $item_desc);
      }
      
      // pubDate
      $startPos = strpos($val2, $target7, 0);
      $endPos = strpos($val2, $target8, $startPos);
      
      $adjStartPos = 9;
      $adjEndPos = 9;
      
      $item_pubDate = substr($val2, $startPos + $adjStartPos, ($endPos - $adjEndPos) - $startPos);    
      $item_pubDate = str_replace("<![CDATA[", "", $item_pubDate);
      $item_pubDate = str_replace("]]>", "", $item_pubDate);
      
//      echo $item_title . "/" . $item_link . "/" . $item_desc . "/" . $item_pubDate . "<br>\n";
      
      // 출력
      echo "<tr>\n";
      echo "\t\t\t\t";
      echo "<td class=\"tg-2oxn\" style=\"width:20%;\">\n";
      echo "<!-- item_link -->";
      echo "<a href=\"" . $item_link  . "\">";
      echo "<!-- item_title -->";
      echo $item_title;
      echo "</a>";
      echo "</td>\n";
      echo "<td class=\"tg-2oxn\">\n";
      echo "<!-- item_description -->";
      echo $item_desc;
      echo "</td>\n";
      echo "<td class=\"tg-2oxn\">\n";
      echo "<!-- pubDate -->";
      echo $item_pubDate;
      echo "</td>\n";
      echo "\t\t\t";
      echo "</tr>\n";
      
    }
    
  }

  echo "\t\t\t";
  echo "</tr>\n";
  echo "\t\t";
  echo "</table>\n";
  
}

?>