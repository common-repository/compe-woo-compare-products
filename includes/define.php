<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WOO_PRODUCT_COMPARE_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "compe-woo-compare-products" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_PRODUCT_COMPARE_ADMIN', VI_WOO_PRODUCT_COMPARE_DIR . "admin" . DIRECTORY_SEPARATOR );

define( 'VI_WOO_PRODUCT_COMPARE_FRONTEND', VI_WOO_PRODUCT_COMPARE_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_PRODUCT_COMPARE_LANGUAGES', VI_WOO_PRODUCT_COMPARE_DIR . "languages" . DIRECTORY_SEPARATOR );

define( 'VI_WOO_PRODUCT_COMPARE_INCLUDES', VI_WOO_PRODUCT_COMPARE_DIR . "includes" . DIRECTORY_SEPARATOR );

//define( 'VI_WOO_PRODUCT_COMPARE_TEMPLATES', VI_WOO_PRODUCT_COMPARE_DIR . "templates" . DIRECTORY_SEPARATOR );

$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );

define( 'VI_WOO_PRODUCT_COMPARE_CSS', $plugin_url . "/css/" );
define( 'VI_WOO_PRODUCT_COMPARE_CSS_DIR', VI_WOO_PRODUCT_COMPARE_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_PRODUCT_COMPARE_JS', $plugin_url . "/js/" );
define( 'VI_WOO_PRODUCT_COMPARE_JS_DIR', VI_WOO_PRODUCT_COMPARE_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_PRODUCT_COMPARE_IMAGES', $plugin_url . "/images/" );

/*Include functions file*/
if ( is_file( VI_WOO_PRODUCT_COMPARE_INCLUDES . "functions.php" ) ) {
	require_once VI_WOO_PRODUCT_COMPARE_INCLUDES . "functions.php";
}
if ( is_file( VI_WOO_PRODUCT_COMPARE_INCLUDES . "data.php" ) ) {
	require_once VI_WOO_PRODUCT_COMPARE_INCLUDES . "data.php";
}
if ( is_file( VI_WOO_PRODUCT_COMPARE_INCLUDES . "support.php" ) ) {
	require_once VI_WOO_PRODUCT_COMPARE_INCLUDES . "support.php";
}
if ( is_file( VI_WOO_PRODUCT_COMPARE_INCLUDES . "custom-controls.php" ) ) {
	require_once VI_WOO_PRODUCT_COMPARE_INCLUDES . "custom-controls.php";
}

vi_include_folder( VI_WOO_PRODUCT_COMPARE_ADMIN, 'VI_WOO_PRODUCT_COMPARE_Admin_' );

vi_include_folder( VI_WOO_PRODUCT_COMPARE_FRONTEND, 'VI_WOO_PRODUCT_COMPARE_Frontend_' );
