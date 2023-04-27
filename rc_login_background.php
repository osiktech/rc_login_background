<?php

/**
 * Background images for Roundcube login page, one for each month
 *
 * @author Petr Fojt <bluelama@liwe.cz>
 * @license GNU GPLv3+
 * @version 1.0.0
 */

class rc_login_background extends rcube_plugin {
  function init() {
    $this->add_hook('startup', array($this, 'startup'));
  }

  function startup($args) {
    if ( $args['task'] == "login" or $args['task'] == "logout" ):
      $this->add_hook('render_page', array($this, 'render_page'));
    endif;
  }

  function render_page($p) {
    $this->include_stylesheet('custom.php');
  }
}
