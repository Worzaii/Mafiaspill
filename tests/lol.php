<?php

error_reporting(E_ALL);
/**
 * generates a image with chars instead of pixels
 *
 * @param string $url Filepath or url
 * @param string $chars The chars which should replace the pixels
 * @param int $shrpns Sharpness (2 = every second pixel, 1 = every pixel ... )
 * @param int $size
 * @param int $weight font-weight/size
 * @return GdImage|bool
 * @author Nicolas 'KeksNicoh' Heimann <www.salamipla.net>
 * @date 02nov08
 */
function d(string $i): void
{
    error_log(__FILE__ . ": " . $i);
}

function letterify(
    string $url,
    string $chars = 'default',
    int $shrpns = 1,
    int $size = 4,
    int $weight = 2
): GdImage|bool {
    list($w, $h) = getimagesize($url);
    d("w: $w, h: $h");
    $resource = imagecreatefromstring(file_get_contents($url));
    $img = imagecreatetruecolor($w * $size, $h * $size);
    $char = 0;
    for ($y = 0; $y < $h; $y += $shrpns) {
        for ($x = 0; $x < $w; $x += $shrpns) {
            imagestring(
                $img,
                $weight,
                $x * $size,
                $y * $size,
                $chars[$char],
                imagecolorat($resource, $x, $y)
            );
            if ((strlen($chars) - 1) == $char) {
                $char = 0;
            } else {
                $char++;
            }
        }
    }
    return $img;
}

//$url = 'https://upload.wikimedia.org/wikipedia/commons/b/be/Manga_Icon.png';
$url = "C:\\Users\\Baret\\Dropbox\\Fuzzy\\Werzy\\NSFW\\Werzaire commission NSFW.png";

$text = 'Werzy really loves doing lewd stuff, rawr!';

Header('Content-Type: image/png');
imagepng(letterify($url, $text, 4, 3));
