<?php
/*  
  <JasperRSSReader>  Copyright (C) 2018  Jaspers
  
	File: function.php
	Author: Jaspers
	Created by 2018-07-14
	Description:
*/


header("Content-Type: text/html; charset=UTF-8");
header('X-Frame-Options: DENY');  // 'X-Frame-Options'

class RSSFunction{
  
  // 수행시간 측정
  public function getExecutionTime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }
  
  public function getCopyright(){
  
    echo "\t\t\t<div id=\"ft_information\">\n";
    echo "\t\t\t\t<p>Copyright &copy; Jasper(rabbit.white at daum dot net)</p>\n";
    echo "\t\t\t\t<p>E-mail : rabbit.white at daum dot net</p>\n";
    echo "\t\t\t\t<p>Powered By ";
    echo "<span style=\"font-weight:bold;\">dothome.co.kr</span><br></p>\n";
    echo "</div>";  
  }
  
  public function get_url_fsockopen($url) 
  { 
  
    // http://
    if ( strpos($url, "https://", 0) == -1 ){
    
      $URL_parsed = parse_url($url); 
    
      $host = $URL_parsed["host"]; 
      $port = $URL_parsed["port"]; 
      if ($port==0) 
          $port = 80; 
    
      $path = $URL_parsed["path"]; 
      if ($URL_parsed["query"] != "") 
          $path .= "?".$URL_parsed["query"]; 
    
      $out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n"; 
    
      $fp = fsockopen($host, $port, $errno, $errstr, 30); 
      if (!$fp) { 
        echo "$errstr ($errno)<br>\n"; 
      } else { 
          fputs($fp, $out); 
          $body = false; 
          while (!feof($fp)) { 
              $s = fgets($fp, 128); 
              if ( $body ) 
                  $in .= $s; 
              if ( $s == "\r\n" ) 
                  $body = true; 
          } 
    
          fclose($fp); 
    
          return $in;        // string으로 받고싶을땐... 
      } 
    } 
    // https://
    else{
      
      $response = $this->getSSLFileContent($url);  
      return $response;
    }
  }

  public function getSSLFileContent($url, $method = 'GET')
  {   
    // Initialize
    $info   = parse_url($url);
    $req    = '';
    $data   = '';
    $line   = '';
    $agent  = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';
    $linebreak  = "\r\n";
    $headPassed = false;
 
    // Setting Protocol
    switch($info['scheme'] = strtoupper($info['scheme']))
    {
        case 'HTTP':
            $info['port']   = 80;
            break;
 
        case 'HTTPS':
            $info['ssl']    = 'ssl://';
            $info['port']   = 443;
            break;
 
        default:
            return false;
    }
 
    // Setting Path
    if(!$info['path'])
    {
        $info['path'] = '/';
    }
 
    // Setting Request Header
    switch($method = strtoupper($method))
    {
        case 'GET':
            if($info['query'])
            {
                $info['path'] .= '?' . $info['query'];
            }
 
            $req .= 'GET ' . $info['path'] . ' HTTP/1.1' . $linebreak;
            $req .= 'Host: ' . $info['host'] . $linebreak;
            $req .= 'User-Agent: ' . $agent . $linebreak;
            $req .= 'Referer: ' . $url . $linebreak;
            $req .= 'Connection: Close' . $linebreak . $linebreak;
            break;
 
        case 'POST':
            $req .= 'POST ' . $info['path'] . ' HTTP/1.1' . $linebreak;
            $req .= 'Host: ' . $info['host'] . $linebreak;
            $req .= 'User-Agent: ' . $agent . $linebreak; 
            $req .= 'Referer: ' . $url . $linebreak;
            $req .= 'Content-Type: application/x-www-form-urlencoded'.$linebreak; 
            $req .= 'Content-Length: '. strlen($info['query']) . $linebreak;
            $req .= 'Connection: Close' . $linebreak . $linebreak;
            $req .= $info['query']; 
            break;
    }
 
    // Socket Open
    $fsock  = @fsockopen($info['ssl'] . $info['host'], $info['port']);
    if ($fsock)
    {
        fwrite($fsock, $req);
        while(!feof($fsock))
        {
            $line = fgets($fsock, 128);
            if($line == "\r\n" && !$headPassed)
            {
                $headPassed = true;
                continue;
            }
            if($headPassed)
            {
                $data .= $line;
            }
        }
        fclose($fsock);
    }
 
    return $data;
  }
}

?>