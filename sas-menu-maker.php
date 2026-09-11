<?php
/**
 * Plugin Name:       SAS Menu Maker
 * Plugin URI:        https://wordpress.org/plugins/sas-menu-maker/
 * Description:       Display beautiful restaurant and cafe menus for food and drinks.
 * Version:           0.1.1
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
define( 'MENUCRAFT_VERSION', '0.1.1' );
define( 'MENUCRAFT_DB_VERSION', '1.5' );
define( 'MENUCRAFT_PLUGIN_FILE', __FILE__ );
define( 'MENUCRAFT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MENUCRAFT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MENUCRAFT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'MENUCRAFT_TEXT_DOMAIN', 'sas-menu-maker' );

/**
 * Activation hook.
 */
function menucraft_activate() {
	require_once MENUCRAFT_PLUGIN_DIR . 'includes/class-menucraft-activator.php';
	MenuCraft_Activator::activate();
}
register_activation_hook( __FILE__, 'menucraft_activate' );

/**
 * Deactivation hook.
 */
function menucraft_deactivate() {
	require_once MENUCRAFT_PLUGIN_DIR . 'includes/class-menucraft-deactivator.php';
	MenuCraft_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'menucraft_deactivate' );

/**
 * Load the core plugin class.
 */
require_once MENUCRAFT_PLUGIN_DIR . 'includes/class-menucraft.php';

/**
 * Bootstrap the plugin.
 */
function menucraft_run() {
	$plugin = new MenuCraft();
	$plugin->run();
}
menucraft_run();
