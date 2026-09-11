<?php
/**
 * Fires when the plugin is uninstalled.
 *
 * Only removes the plugin's data set when the administrator explicitly
 * opted in via the "delete_data_on_uninstall" option. Default is to keep
 * all tables intact so accidental removal does not destroy content.
 *
 * @package SAS_Menu_Maker
 */

// Exit if uninstall not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once __DIR__ . '/includes/class-sas-menu-maker-schema.php';
require_once __DIR__ . '/includes/class-sas-menu-maker-options.php';

// This file runs once at plugin uninstall in a short-lived isolated
// process — `$delete` is a local flag, not a persistent global.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$delete = SAS_Menu_Maker_Options::get( 'delete_data_on_uninstall', '0' );

if ( '1' === (string) $delete ) {
	SAS_Menu_Maker_Schema::drop_tables();
}
