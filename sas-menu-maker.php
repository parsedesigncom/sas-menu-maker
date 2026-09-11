<?php
/**
 * Plugin Name:       SAS Menu Maker
 * Plugin URI:        https://wordpress.org/plugins/sas-menu-maker/
 * Description:       Display beautiful restaurant and cafe menus for food and drinks.
 * Version:           0.1.2
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Saeid Samani
 * Author URI:        https://saeidsamani.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sas-menu-maker
 * Domain Path:       /languages
 *
 * @package SAS_Menu_Maker
 */

// Prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Plugin constants.
 */
define( 'SAS_MENU_MAKER_VERSION', '0.1.2' );
define( 'SAS_MENU_MAKER_DB_VERSION', '1.5' );
define( 'SAS_MENU_MAKER_PLUGIN_FILE', __FILE__ );
define( 'SAS_MENU_MAKER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SAS_MENU_MAKER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SAS_MENU_MAKER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SAS_MENU_MAKER_TEXT_DOMAIN', 'sas-menu-maker' );

/**
 * Activation hook.
 */
function sas_menu_maker_activate() {
	require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-activator.php';
	SAS_Menu_Maker_Activator::activate();
}
register_activation_hook( __FILE__, 'sas_menu_maker_activate' );

/**
 * Deactivation hook.
 */
function sas_menu_maker_deactivate() {
	require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-deactivator.php';
	SAS_Menu_Maker_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'sas_menu_maker_deactivate' );

/**
 * Load the core plugin class.
 */
require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker.php';

/**
 * Bootstrap the plugin.
 */
function sas_menu_maker_run() {
	$plugin = new SAS_Menu_Maker();
	$plugin->run();
}
sas_menu_maker_run();
