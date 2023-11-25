<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'lemurecomsmetic' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'xVADbkz6ZDh84loOnWNytURrADTk6LFOGutY6CTyvdj09BUelGvrlUKfAyjW0O5E' );
define( 'SECURE_AUTH_KEY',  'XsFOagrufbebjcNLljvwP6HwsfWvwP9g8tIfu8hsV2qYeUuXyCHC5ESx4luEUSUl' );
define( 'LOGGED_IN_KEY',    'P4CIYy87wU8xH91tbmgQxwWcjpWfMgAF8IhfMIcajpawPsixShefoQCs6nv8iWNI' );
define( 'NONCE_KEY',        'zkbkbgqTA2qu2bgHeMOFEtPsw2OGfSmau23atylpGmOMKXBDsx0Z5BAX0F4rX70A' );
define( 'AUTH_SALT',        'iCKXoYJFF9JskexVhclvLqIIIDH8Hu0FQkO7bovD3js3FrUJ3N6IKmzscXBLBgvs' );
define( 'SECURE_AUTH_SALT', 'eSnZZy7H4boZgAdT24dyLB97U5f2SjVvFyM8gSNBqpfJ6wtxf0TiThG6JPSPnMa7' );
define( 'LOGGED_IN_SALT',   'dNGboq0ayxQJKol7FJbl5xJcrMn6Xy4OkJba9euNOd4yl9MAkd1QRWaqKAnswJB1' );
define( 'NONCE_SALT',       'oG3MbbgTmPF2QvT42QsleIRrYjbDL1astPMkndlIda1O7klm3m2djLNTfVBQM1mF' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
