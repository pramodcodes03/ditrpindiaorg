<?php
if(!isset($_SESSION)) session_start();

$chars = '0123456789';
$code =  substr( str_shuffle( $chars ), 0, 5 );

//$code=rand(1000,9999);
$_SESSION["code"]=$code;
$im = imagecreatetruecolor(70, 44);
$bg = imagecolorallocate($im, 01, 11, 01);
$fg = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 50, 0, $bg);
imagestring($im, 10, 10, 10,  $code, $fg);
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>