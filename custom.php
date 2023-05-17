<?php

/**
 * Background images for Roundcube login page, one for each month
 *
 * @author Osik <me@osik.de>, Petr Fojt <bluelama@liwe.cz>
 * @license GNU GPLv3+
 * @version 2.0.0
 */

header("Content-type: text/css; charset: UTF-8");

function GetRandomImage($dir) {
  $imgs_arr = array();

  if (file_exists($dir) && is_dir($dir) ) {
    $dir_arr = scandir($dir);
    $arr_files = array_diff($dir_arr, array('.','..') );

    foreach ($arr_files as $file) {
      $file_path = $dir."/".$file;
      $ext = pathinfo($file_path, PATHINFO_EXTENSION);
      if ($ext=="jpg" || $ext=="png" || $ext=="JPG" || $ext=="PNG" || $ext=="JPEG") {
        array_push($imgs_arr, $file);
      }
    }
    $count_img_index = count($imgs_arr) - 1;
    $random_img = $imgs_arr[rand( 0, $count_img_index )];
  }
  return $random_img;
}

$img = GetRandomImage('img')

echo "
#layout-content {
  background-color: transparent !important;
}

body
{
  background-image: url('img/$img');
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
}
";
