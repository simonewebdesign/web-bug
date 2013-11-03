<?php

$fullPath = $_SERVER['REQUEST_URI'];
$filename = basename($fullPath);

$data = "Time: " . date(DATE_RFC822) . ';';
$data .= "IP: " . $_SERVER["REMOTE_ADDR"] . "; ";
$data .= "User Agent: " . $_SERVER["HTTP_USER_AGENT"] . "\r\n";

file_put_contents('data.txt', $data, FILE_APPEND);

if( headers_sent() ) {
  die('Headers already sent.');
}
// File Exists?
//var_dump( file_exists($filename) );
if ( file_exists($filename) ){
 
  // Parse Info / Get Extension
  $fsize = filesize($filename);
  $path_parts = pathinfo($fullPath); // to get extension
  $ext = strtolower($path_parts["extension"]); 
  
  // Determine Content Type
  switch ($ext) {
    case "gif": $ctype="image/gif"; break;
    case "png": $ctype="image/png"; break;
    case "jpeg":
    case "jpg": $ctype="image/jpg"; break;
    default: $ctype="application/force-download";
  }

  header("Content-Type: $ctype");
//  header("Content-Transfer-Encoding: binary"); // remember to upload images in binary mode!
  
  ob_clean();
  flush();
  readfile($filename);

} else {
  header('HTTP/1.1 404 Not Found');
  die('<h1>404 - File Not Found</h1>');
}