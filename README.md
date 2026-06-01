# rc_login_background

Background images for the Roundcube login and logout pages.

Fork of [BlueLama/rc-login-background](https://github.com/bluelama/rc-login-background).

## Installation

```bash
composer config repositories.rc_login_background vcs https://github.com/osiktech/rc_login_background.git
composer require "osiktech/rc_login_background:>=3.0.1"
```

Enable the plugin in Roundcube (`$config['plugins']`).

## Configuration

Copy `config.inc.php.dist` to your Roundcube **config** directory (recommended):

```bash
cp plugins/rc_login_background/config.inc.php.dist config/rc_login_background.inc.php
```

Or add the `$config[...]` lines from the dist file to `config/config.inc.php`.

Optional: use `plugins/rc_login_background/config.inc.php` instead. The file **must** start with `<?php` on the first line.

### Fix: "Failed to load config from .../plugins/rc_login_background/config.inc.php"

This usually means `plugins/rc_login_background/config.inc.php` exists but is invalid (often missing `<?php`).

**Option A** — add the opening tag as the first line of that file:

```php
<?php

$config['rc_login_background_color'] = '#ffffff';
// ...
```

**Option B** — remove the plugin-local file and use the config directory instead:

```bash
rm plugins/rc_login_background/config.inc.php
cp plugins/rc_login_background/config.inc.php.dist config/rc_login_background.inc.php
```

## Background images

Place images in `plugins/rc_login_background/assets/images/`:

| Mode | Setting | Files |
|------|---------|--------|
| Monthly | `rc_login_background_monthly = true` | `01.jpg` … `12.jpg` |
| Random | `rc_login_background_random = true` | any `.jpg` / `.jpeg` / `.png` |
| Fixed | `rc_login_background_image = 'name.jpg'` | filename in `assets/images/` |

If the chosen file is missing, `assets/fallback.svg` is used.
