<?php
/**
 * Loads translations for the plugin.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Responsible for loading the plugin text domain.
 *
 * Since WordPress 4.6, translations for plugins hosted on WordPress.org
 * are loaded automatically from translate.wordpress.org — no manual
 * `load_plugin_textdomain()` call is needed and one would be flagged by
 * Plugin Check. The method is kept as a no-op so the loader hook wiring
 * stays intact.
 */
class SAS_Menu_Maker_I18n {

	/**
	 * No-op placeholder; translations load automatically on WP.org.
	 */
	public function load_plugin_textdomain() {
		// Intentionally left blank.
	}
}
