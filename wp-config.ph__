<?php

// Use these settings on the local server
if ( file_exists( dirname( __FILE__ ) . '/wp-config-local.php' ) ) {
  include( dirname( __FILE__ ) . '/wp-config-local.php' );

// Otherwise use the below settings (on live server)
} else {


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
  define('DB_NAME', 'drugstoc_drugst');

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

  /**#@+
   * Authentication Unique Keys and Salts.
   *
   * Change these to different unique phrases!
   * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
   * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
   *
   * @since 2.6.0
   */
  define('AUTH_KEY',         '{_&^:j7V<-iVp3XA :S=z?!zrp@.l_MB[UJ>okuE8=t|b2p_m-AcP_y!<fwWKdeW');
  define('SECURE_AUTH_KEY',  '`,7TUz:;H9f%-6ruzLc]c1~fsKIN~5j-%Qe9yz{yXvq^6ML=CL)3Iff4J9@vV,I&');
  define('LOGGED_IN_KEY',    'jZ.<|xWE%-@)H%cX(d)[?fzVnG,*SV1|V=NofI&v #vl/B[T)B+U!.|0p{a,/l5}');
  define('NONCE_KEY',        ']8k6pBYmWor@U/EU+[`J+kE}TlMoIuQB|hX.L@8ycyj+j2>.YS9`#<`?3XGUD2Bv');
  define('AUTH_SALT',        '$+ Y9$Wvw0V/c:4=0#~lqX1$7gp$*4+x,,I=_8ZzwmDh19hZ4N#PlQ}&&U%w> ve');
  define('SECURE_AUTH_SALT', 'IY[+[*qz#7^!0Y(nM}gip(kxcks6qoxI~>-OfL&<~SY0wE;$P{Bty]#kz^EcT9$)');
  define('LOGGED_IN_SALT',   'TY=T! xBXU8xspoGYpKN?LDLH&Lf%lS,aVMFv?3=iK`F4rouRDr(++Qh%5LhWI$6');
  define('NONCE_SALT',       'S$Li{b<75>@7ws}MWwMaE*p@FfsmO3cZ%||$@}^`K(c$)T9/;E_&)*2$:.qo??3}');

  /**#@-*/

  /**
   * WordPress Database Table prefix.
   *
   * You can have multiple installations in one database if you give each a unique
   * prefix. Only numbers, letters, and underscores please!
   */
  $table_prefix  = 'wp_';

}

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
