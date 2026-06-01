<?php

/**
 * Background images for Roundcube login and logout pages
 * Supports monthly, random, or fixed background images
 *
 * @author Osik <me@osik.de>, Petr Fojt <bluelama@liwe.cz>
 * @license GNU GPLv3+
 * @version 3.0.0
 */

class rc_login_background extends rcube_plugin {

  public $task = 'login|logout';

  public function init() {
    $this->add_hook('login_header', array($this, 'add_background'));
    $this->add_hook('logout_header', array($this, 'add_background'));
  }

  public function add_background($args) {
    // Konfiguration auslesen
    $use_monthly = $this->rc->config->get('rc_login_background_monthly', false);
    $use_random = $this->rc->config->get('rc_login_background_random', false);
    $fixed_image = $this->rc->config->get('rc_login_background_image', '');
    $background_color = $this->rc->config->get('rc_login_background_color', '#ffffff');

    // Bildauswahl-Logik
    if ($use_monthly) {
      $img = $this->home . 'assets/images/' . date('m') . ".jpg";
    } elseif ($use_random) {
      $img = $this->GetRandomImage($this->home . 'assets/images/');
    } else {
      $img = $fixed_image ?: $this->home . 'assets/fallback.svg';
    }

    // URL für das Bild generieren
    $background_image_url = $this->url([
      '_action' => 'plugin.rc_login_background.background',
      '_file'   => $img
    ]);

    // CSS für Login und Logout
    $style = '
      <style>
        body.login-screen,
        body.logout-screen {
          background: ' . $background_color . ' url("' . $background_image_url . '") no-repeat center center fixed;
          background-size: cover;
        }
      </style>
    ';

    $args['content'] .= $style;
    return $args;
  }

  /**
   * Liefert ein zufälliges Bild aus dem Ordner
   */
  private function GetRandomImage($dir) {
    $imgs_arr = array();
    $fallback = $this->home . 'assets/fallback.svg';

    if (file_exists($dir) && is_dir($dir)) {
      $dir_arr = scandir($dir);
      $arr_files = array_diff($dir_arr, array('.', '..'));

      foreach ($arr_files as $file) {
        $file_path = $dir . $file;
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        if (in_array($ext, array('jpg', 'png', 'jpeg'))) {
          array_push($imgs_arr, $file);
        }
      }
      if (!empty($imgs_arr)) {
        return $imgs_arr[array_rand($imgs_arr)];
      }
    }
    return $fallback;
  }

  /**
   * Liefert das Hintergrundbild aus
   */
  public function background()
  {
    $file = rcube_utils::get_input_value('_file', rcube_utils::INPUT_GPC);
    $image_path = $this->home . 'assets/images/' . ($file ?: $this->home . 'assets/fallback.svg');

    if (file_exists($image_path)) {
      $ext = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
      switch ($ext) {
        case 'jpg':
        case 'jpeg':
          header('Content-Type: image/jpeg');
          break;
        case 'png':
          header('Content-Type: image/png');
          break;
        default:
          header('Content-Type: image/jpeg');
      }
      readfile($image_path);
      exit;
    }

    // Fallback
    header('Content-Type: image/jpeg');
    readfile($this->home . 'assets/fallback.svg');
    exit;
  }
}
