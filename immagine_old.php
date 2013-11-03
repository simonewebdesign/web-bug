<?php

function d($str) { //debug function. needs a file called debug.txt in same directory.
  file_put_contents('debug.txt', $str . "\r\n", FILE_APPEND);
}


$fullPath = $_SERVER['REQUEST_URI'];
d("fullpath=".$fullPath);

$filename = basename($fullPath);
d("filename=".$filename);
//$fullPath = dirname(__FILE__);
//var_dump($fullPath);

//var_dump( stream_resolve_include_path(basename($fullPath)) );

//$fullPath = stream_resolve_include_path(basename($_SERVER['REQUEST_URI']));

$data  = "IP: " . $_SERVER["REMOTE_ADDR"] . "; ";
$data .= "User Agent: " . $_SERVER["HTTP_USER_AGENT"] . "\r\n";
//d('data='.$data);
 
file_put_contents('data.txt', $data, FILE_APPEND);

// File Exists?
//var_dump( file_exists($filename) );
d('file_exists='.file_exists($filename));

if( headers_sent() )
  die('Headers Sent');

if ( file_exists($filename) ){
 
  // Parse Info / Get Extension
  $fsize = filesize($filename);
  d('fsize:'.$fsize);
  $path_parts = pathinfo($fullPath); // to get extension
  $ext = strtolower($path_parts["extension"]); 
  d('ext:'.$ext);
  
  // Determine Content Type
  switch ($ext) {
  //  case "pdf": $ctype="application/pdf"; break;
  //  case "exe": $ctype="application/octet-stream"; break;
  //  case "zip": $ctype="application/zip"; break;
  //  case "doc": $ctype="application/msword"; break;
  //  case "xls": $ctype="application/vnd.ms-excel"; break;
  //  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
    case "gif": $ctype="image/gif"; break;
    case "png": $ctype="image/png"; break;
    case "jpeg":
    case "jpg": $ctype="image/jpg"; break;
    default: $ctype="application/force-download";
  }

  if( headers_sent() )
    die('Headers Sent');  
  
//  header("Pragma: public"); // required
//  header("Expires: 0");
//  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//  header("Cache-Control: private",false); // required for certain browsers
  header("Content-Type: $ctype");
//  header("Content-Disposition: attachment; filename=\"" . $filename ."\";" ); // forces download
  header("Content-Transfer-Encoding: binary");
//  header("Content-Length: " . $fsize);
  ob_clean();
  flush();
  readfile($filename);

} else {
  header('HTTP/1.1 404 Not Found');
  die('<h1>404 - File Not Found</h1>');
}