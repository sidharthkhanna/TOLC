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
define('DB_NAME', 'anu_data');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'a%f*iD w7O+~t ]oZG7X[X?Il/G>J+j%cU P{J+4Xp<J.*#:_ KX$fA%j&A:}DT=');
define('SECURE_AUTH_KEY',  ']gW`yabFS6-mLK[7N&8v|fkAC!H.k7=?8N!zK;2Yo4BCmXxl_Rc <s=TIUl#>^vY');
define('LOGGED_IN_KEY',    'S_FW!K1+i-J||rR-InI5jgz5-I%5{I}vKsffNf<}_}d6_,oz4CUsRHH1zn$Z<boM');
define('NONCE_KEY',        'lDP,p^ZY~]`5NdgpM(YD0C-JWD%l$D#Ez24<}@&RX_u_:EqAqI]4H-w(Dk3(A23j');
define('AUTH_SALT',        'rR(kE}(YpI*hxZwJbtU/-D4GEmw-J}?3OA# qT^`9nJ&?NL1F<MVr;x[bWb|?T=;');
define('SECURE_AUTH_SALT', 'eYu@+[-] !D)TU6?f3$a3`.zI1)U#.REe9`(lr.~I6){ ~Mseq<c|ZxsA()w1P-w');
define('LOGGED_IN_SALT',   'i+$^cl|@|0kp7RQUu|78P[HW!*Ztwb6@+;0mFC1Y@ntX&%p$*]mtR_iq^#T STYP');
define('NONCE_SALT',       '%A-M1j y1{|?@+b(GjT1b@cX>OtEt|o4S*enoJC-ow.SYMxPEKCvYXOw7 adXx|)');

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
