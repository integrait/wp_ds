<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

  // ** MySQL settings - You can get this info from your web host ** //
  /** The name of the database for WordPress */
  define('DB_NAME', 'drugstoc_db_v1');

  /** MySQL database username */
  define('DB_USER', 'drugstoc_integra');

  /** MySQL database password */
  define('DB_PASSWORD', 'INTEGRA.1');

  /** MySQL hostname */
  define('DB_HOST', 'localhost');

  /** Database Charset to use in creating database tables. */
  define('DB_CHARSET', 'utf8');

  /** The Database Collate type. Don't change this if in doubt. */
  define('DB_COLLATE', '');



/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'm+C#dMm=k=spPQ#)S7x$Ov.F>6^|}gt@zz58kXVMpcdE-+W0F|37+d-%)2$hW07V');
define('SECURE_AUTH_KEY',  'QH+ijH+aejlMN>~8k $z8/yb3xDUE5WF>Kqv38X=nx;*MV~GU>3iD|zUeKX=v@}$');
define('LOGGED_IN_KEY',    '-?)S3 O;^@vgA 94=^gTJ8Cq*|JA0zOL(g6PIY2R=B8gatBn,+/|@bgLohv;Vg=;');
define('NONCE_KEY',        '>q m-+^Ae9(6;+j|Qp~8U&|^-S9TGq}jh^Z-4X@U&NB-Jkon-Yx^BSe-IFaM}<FN');
define('AUTH_SALT',        'tk=^LI^5<=~2f#3{WwzJ,Tn]-V/)56zRGw 1M<FE(@3B?y|%rXI}%/B+5aZI@3tG');
define('SECURE_AUTH_SALT', 'ANCYwc7Ch34#po&j3`A2He,6wKd|2csar0O/|d|9MA29|#y1*.K_EL[GSwQJLJ@b');
define('LOGGED_IN_SALT',   't0V+;]vsiU(VV[kU0j,mH$rLe87Fq]DQK<@-!Y%T[9/G5WHZL-9c]% $ZuAF@0V?');
define('NONCE_SALT',       'L;pL,0.v<-NvLo|yyAQbOi@g-i7>E;Hcke5[Im$5oNWDz$!C9,x}H*Tt^@-<Y8tN');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');


/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');