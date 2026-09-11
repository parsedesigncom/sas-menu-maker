<?php
/**
 * Runs on plugin activation.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles activation tasks: schema creation and default option seeding.
 */
class SAS_Menu_Maker_Activator {

	/**
	 * Activation callback.
	 *
	 * Delegates schema setup to SAS_Menu_Maker_Schema::maybe_upgrade() so fresh
	 * installs and re-activations after a version bump take the same code
	 * path — this guarantees structural migrations run even when the user
	 * deactivates and re-activates the plugin between updates.
	 */
	public static function activate() {
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-schema.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-options.php';

		SAS_Menu_Maker_Schema::maybe_upgrade();

		// Safety switch for uninstall — user must opt in to destroy their data.
		if ( null === SAS_Menu_Maker_Options::get( 'delete_data_on_uninstall', null ) ) {
			SAS_Menu_Maker_Options::update( 'delete_data_on_uninstall', '0' );
		}
	}
}
