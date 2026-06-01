<?php

/**
 * Background images for Roundcube login and logout pages
 * Supports monthly, random, or fixed background images
 *
 * @author Osik <me@osik.de>, Petr Fojt <bluelama@liwe.cz>
 * @license GNU GPLv3+
 * @version 3.0.1
 */

class rc_login_background extends rcube_plugin {

  public $task = 'login|logout';

  public function init() {
    $this->load_config();
    $this->add_hook('login_header', [$this, 'add_background']);
    $this->add_hook('logout_header', [$this, 'add_background']);
  }

  public function add_background($args) {
    $rcube = rcube::get_instance();
    $use_monthly = $rcube->config->get('rc_login_background_monthly', false);
    $use_random = $rcube->config->get('rc_login_background_random', false);
    $fixed_image = $rcube->config->get('rc_login_background_image', '');
    $background_color = $this->css_color($rcube->config->get('rc_login_background_color', '#ffffff'));

    $relative_path = $this->resolve_background_path($use_monthly, $use_random, $fixed_image);
    $background_image_url = $this->url($relative_path);

    $style = '
      <style>
        body.login-screen,
        body.logout-screen {
          background: ' . $background_color . ' url("' . htmlspecialchars($background_image_url, ENT_QUOTES, 'UTF-8') . '") no-repeat center center fixed;
          background-size: cover;
        }
      </style>
    ';

    $args['content'] .= $style;
    return $args;
  }

  /**
   * Resolves plugin-relative asset path for the background image.
   */
  private function resolve_background_path($use_monthly, $use_random, $fixed_image) {
    if ($use_monthly) {
      $relative = 'assets/images/' . date('m') . '.jpg';
    } elseif ($use_random) {
      $filename = $this->get_random_image($this->home . 'assets/images/');
      $relative = $filename ? 'assets/images/' . $filename : 'assets/fallback.svg';
    } elseif ($fixed_image !== '') {
      $relative = 'assets/images/' . basename($fixed_image);
    } else {
      return 'assets/fallback.svg';
    }

    if (is_file($this->home . $relative)) {
      return $relative;
    }

    return 'assets/fallback.svg';
  }

  /**
   * Returns a random image filename from the given directory, or null if none found.
   */
  private function get_random_image($dir) {
    if (!is_dir($dir)) {
      return null;
    }

    $images = [];
    foreach (scandir($dir) as $file) {
      if ($file === '.' || $file === '..') {
        continue;
      }
      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
        $images[] = $file;
      }
    }

    if (empty($images)) {
      return null;
    }

    return $images[array_rand($images)];
  }

  /**
   * Restricts background color to safe hex values for inline CSS.
   */
  private function css_color($color) {
    $color = trim((string) $color);
    if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color)) {
      return $color;
    }

    return '#ffffff';
  }
}
