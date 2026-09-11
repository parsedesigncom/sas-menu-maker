<?php
/**
 * The core plugin class.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class. Loads dependencies and wires hooks for admin and public areas.
 */
class SAS_Menu_Maker {

	/**
	 * Loader instance responsible for registering hooks.
	 *
	 * @var SAS_Menu_Maker_Loader
	 */
	protected $loader;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load required dependencies for this plugin.
	 */
	private function load_dependencies() {
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-loader.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-i18n.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-schema.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-options.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-slug.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-category-repository.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-tag-repository.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-allergen-repository.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-item-repository.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-offer-repository.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-rest.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-block.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-offers-block.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'includes/class-sas-menu-maker-group-block.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'admin/class-sas-menu-maker-admin.php';
		require_once SAS_MENU_MAKER_PLUGIN_DIR . 'public/class-sas-menu-maker-public.php';

		$this->loader = new SAS_Menu_Maker_Loader();
	}

	/**
	 * Register text domain for internationalization.
	 */
	private function set_locale() {
		$i18n = new SAS_Menu_Maker_I18n();
		$this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register admin-area hooks.
	 */
	private function define_admin_hooks() {
		$admin = new SAS_Menu_Maker_Admin( SAS_MENU_MAKER_TEXT_DOMAIN, SAS_MENU_MAKER_VERSION );
		$this->loader->add_action( 'admin_menu', $admin, 'register_admin_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'admin_body_class', $admin, 'admin_body_class' );
		$this->loader->add_action( 'admin_init', 'SAS_Menu_Maker_Schema', 'maybe_upgrade' );
		$this->loader->add_action( 'rest_api_init', 'SAS_Menu_Maker_REST', 'register_routes' );
	}

	/**
	 * Register public-facing hooks.
	 *
	 * Public assets are registered on `init` (not `wp_enqueue_scripts`) so
	 * their handles exist in both frontend and block-editor contexts —
	 * blocks reference them via block.json's `style` / `viewScript` fields
	 * and WP looks them up at block-render time.
	 */
	private function define_public_hooks() {
		$public = new SAS_Menu_Maker_Public( SAS_MENU_MAKER_TEXT_DOMAIN, SAS_MENU_MAKER_VERSION );
		$this->loader->add_action( 'init', $public, 'register_shortcodes' );
		$this->loader->add_action( 'init', $public, 'register_assets' );
		$this->loader->add_action( 'init', 'SAS_Menu_Maker_Block', 'register' );
		$this->loader->add_action( 'init', 'SAS_Menu_Maker_Offers_Block', 'register' );
		$this->loader->add_action( 'init', 'SAS_Menu_Maker_Group_Block', 'register' );
	}

	/**
	 * Run the plugin by executing the loader.
	 */
	public function run() {
		$this->loader->run();
	}
}
