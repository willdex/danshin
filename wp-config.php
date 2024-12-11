<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\xampp\htdocs\wordpress\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'e;6vZ@vTFWE&=yZHF%C:~WKO%LRGxf^pdA1VCX[*96_>uz=!t0CL;6/#E=oH=m7J' );
define( 'SECURE_AUTH_KEY',  '4>=fop#Ml,?4btB?Y`@`c(jBw)}J~EI&IH`%IH:Y`Mwg;YIql*KX,ad7S-bf41-i' );
define( 'LOGGED_IN_KEY',    'd-dtC=D]bwNMQqrTvu*KudDUHhu@T1]zu[cSCf ;ti7*X#99F{u9-B+Ao}5lT7Oa' );
define( 'NONCE_KEY',        'hbti&z/=r9`p[#5Z60Av?b12>2XcD9~XPoVTKv}a,vq&W;Ht6!7RHY?3_i~MkA8^' );
define( 'AUTH_SALT',        'H9oBHTz(lT{pjf8m0r?hTWW}rNyiXu2o#,]aS|h&pS8C/Z>C$yl7?^?(|1%@:`*8' );
define( 'SECURE_AUTH_SALT', 'tT=M9c=jp~%I7C2kePsJG#yrh x/pm0(=;I+ak7G6A(O*pIQ/7`XyN^8JNEokV>{' );
define( 'LOGGED_IN_SALT',   'YIHI&fFi:w%JhW;[I}g1zbqPF%QO)rM|8Vp3$ccOGjS09CQ(OO$Fv}vxb^j|:[tM' );
define( 'NONCE_SALT',       'S6*tW7vw;|8(&4yy%t/C=s{Xn[}=Z-).UA)04tBc7n(+DFs$p);[`Em}^Nur6fA3' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
