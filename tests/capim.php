<?php

function createDynamic_image()
{
    $md5 = md5(rand(0, 999));
    $pass = substr($md5, 10, 5);
    $width = 500;
    $height = 100;
    $image = imagecreate($width, $height);
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $grey = imagecolorallocate($image, 150, 150, 150);
    imagefill($image, 0, 0, $black);
    imagestring($image, 4, rand(1, 30), rand(1, 4), $pass, $white);
    imagerectangle($image, 0, 0, $width - 1, $height - 1, $grey);
    imageline($image, 0, $height / 2, $width, $height / 2, $grey);
    imageline($image, $width / 2, 0, $width / 2, $height, $grey);
    header("Content-Type: image/jpeg");
    imagejpeg($image);
    imagedestroy($image);
}

createDynamic_image();
