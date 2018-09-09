<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lextopdollar_store');

/** MySQL database username */
define('DB_USER', 'ltdwp');

/** MySQL database password */
define('DB_PASSWORD', 'Fredfred1!');

/** MySQL hostname */
define('DB_HOST', 'lextopdollarcom.fatcowmysql.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']#y.T rr%j*UU}tX^F*#XLHOf&)g9LBB+S+S+OaXJ%T^h0:>~s3Kv}Bg.c}[-?*{');
define('SECURE_AUTH_KEY',  ': %N_^*}bxdTQFR4AS`x}B@o|fA?06H:gG]{cz)YQCIrfKR.]S4&!$4fa5>Rq2&L');
define('LOGGED_IN_KEY',    'jvb(,ep5m|*>0k9Y-3/]?(_a8-<}c^Zff?(tnTaj|&r,}=,/so,!gN4j,khe5UcE');
define('NONCE_KEY',        'mm[-bBb`1:T>g9EO[d1+J= %M74l>a^G+GRz~|+}z~00|!a5!aN$KxUr|PSh<?4h');
define('AUTH_SALT',        'H-pke~G6*hXA@i-l[C{ybYbt3|Tv$@Cp,F}Y:,u;Oux{K|-eOU7+?Uoq&fv-M Yk');
define('SECURE_AUTH_SALT', 'Ez6y<I(Kjux;4}6v M:p -pwxqR=+*JTU1j#zjNS{Dg*,<k5J}B_O[0Ot+l33i$-');
define('LOGGED_IN_SALT',   '[Cv^CbAX&9Kt*(+DD[`9/wosrr-quQHYf&/?S-qB%n47=bdJeoc9Z<8.W33H-tz7');
define('NONCE_SALT',       'F!- x,;|+>%McQWLTs:Ol?-9aVi8?-Xwjn;t9{PB~w>(S?cb,OZmT s97Oij<b*h');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


/** Memory Limit */
define('WP_MEMORY_LIMIT', '32M');

#define('WP_HOME','http://www.lextopdollar.com/');
#define('WP_SITEURL','http://www.lextopdollar.com/');
