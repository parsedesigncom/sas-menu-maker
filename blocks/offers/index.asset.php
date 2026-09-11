<?php
/**
 * Dependency + version manifest for blocks/offers/index.js.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

return array(
	'dependencies' => array(
		'wp-blocks',
		'wp-block-editor',
		'wp-components',
		'wp-element',
		'wp-i18n',
		'wp-server-side-render',
	),
	'version'      => defined( 'SAS_MENU_MAKER_VERSION' ) ? SAS_MENU_MAKER_VERSION : '0.0.0',
);
