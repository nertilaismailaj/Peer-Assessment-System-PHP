<?php

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
  }
  
session_start();
if(isset($_SESSION['custom_captcha'])){
    #check for session otherwise remove if one is there
    unset($_SESSION['custom_captcha']);
}

# read background image
$image = ImageCreateFromPng ("images/captcha100x40.png");

# randomise colour for text
$red = rand(80,130);
$green = rand(80,130);
$blue = 320 - $red - $green;
$textColour = ImageColorAllocate($image, $red, $green, $blue);

# randomise text
$letters="abcdefghijklmnopqrstuvwxyz";
$numbers="0123456789";
$combined=$letters.$numbers;
$combined= str_shuffle($combined);
$random_text= substr($combined,0,5);
$_SESSION['custom_captcha']=$random_text;

# Edit and output the image
$x = rand(3,18);
$y = rand(3,18);
ImageString($image, 5, $x, $y,  $random_text, $textColour);
$bigImage = imagecreatetruecolor(200, 80);
imagecopyresized ($bigImage, $image, 0, 0, 0, 0, 200, 80, 100, 40);
header("Content-Type: image/jpeg");
Imagejpeg($bigImage, NULL, 8);
ImageDestroy($image);
ImageDestroy($bigImage);
