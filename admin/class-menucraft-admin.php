<?php
/**
 * Admin-facing functionality of the plugin.
 *
 * @package MenuCraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles admin-area hooks, screens, assets and settings.
 */
class MenuCraft_Admin {

	/**
	 * Plugin text domain / handle.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Hook suffixes returned by add_menu_page / add_submenu_page.
	 *
	 * Populated during register_admin_menu() and consulted by enqueue and
	 * body-class filters to scope work to MenuCraft screens only.
	 *
	 * @var string[]
	 */
	private $page_hooks = array();

	/**
	 * Constructor.
	 *
	 * @param string $plugin_name Plugin handle used for asset identifiers.
	 * @param string $version     Plugin version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Enqueue admin styles on MenuCraft screens only.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_styles( $hook_suffix ) {
		if ( ! $this->is_menucraft_screen( $hook_suffix ) ) {
			return;
		}

		wp_enqueue_style(
			'menucraft-admin',
			MENUCRAFT_PLUGIN_URL . 'assets/css/menucraft-admin.css',
			array(),
			$this->version
		);
	}

	/**
	 * Enqueue admin scripts on MenuCraft screens only.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if ( ! $this->is_menucraft_screen( $hook_suffix ) ) {
			return;
		}

		// Loads wp.media (used by our vanilla picker to open the native library UI).
		wp_enqueue_media();

		wp_enqueue_script(
			'menucraft-admin',
			MENUCRAFT_PLUGIN_URL . 'assets/js/menucraft-admin.js',
			array(),
			$this->version,
			true
		);

		wp_localize_script(
			'menucraft-admin',
			'menucraftAdmin',
			array(
				'restUrl'   => esc_url_raw( rest_url( 'sas-menu-maker/v1/' ) ),
				'restNonce' => wp_create_nonce( 'wp_rest' ),
				'currency'  => (string) MenuCraft_Options::get( 'currency', '€' ),
				'i18n'      => array(
					'saving'          => __( 'Saving…', 'sas-menu-maker' ),
					'saveSuccess'     => __( 'Saved.', 'sas-menu-maker' ),
					'saveError'       => __( 'Save failed.', 'sas-menu-maker' ),
					'updateSuccess'   => __( 'Updated.', 'sas-menu-maker' ),
					'deleteSuccess'   => __( 'Deleted.', 'sas-menu-maker' ),
					'deleteError'     => __( 'Delete failed.', 'sas-menu-maker' ),
					'listError'       => __( 'Could not load list.', 'sas-menu-maker' ),
					'empty'           => __( 'No entries yet.', 'sas-menu-maker' ),
					'active'          => __( 'Active', 'sas-menu-maker' ),
					'inactive'        => __( 'Inactive', 'sas-menu-maker' ),
					'edit'            => __( 'Edit', 'sas-menu-maker' ),
					'delete'          => __( 'Delete', 'sas-menu-maker' ),
					'mediaTitle'      => __( 'Select Image', 'sas-menu-maker' ),
					'mediaButton'     => __( 'Use this image', 'sas-menu-maker' ),
					'mediaEmpty'      => __( 'No image selected', 'sas-menu-maker' ),
					'mediaUnavail'    => __( 'Media library unavailable.', 'sas-menu-maker' ),
					'from'            => __( 'from', 'sas-menu-maker' ),
					'noPrice'         => __( 'no price', 'sas-menu-maker' ),
					'variantsNone'    => __( 'None', 'sas-menu-maker' ),
					/* translators: %d: number of variants on an item. */
					'variantsCount'   => __( '%d variant(s)', 'sas-menu-maker' ),
					'variantLabel'    => __( 'Label', 'sas-menu-maker' ),
					'variantPrice'    => __( 'Price', 'sas-menu-maker' ),
					'variantRemove'   => __( 'Remove', 'sas-menu-maker' ),
					'variantLabelHint' => __( 'e.g. Small, Medium, Large', 'sas-menu-maker' ),
					/* translators: %d: number of items the bulk-edit was applied to. */
					'bulkApplied'     => __( 'Applied to %d item(s).', 'sas-menu-maker' ),
					'bulkNoOps'       => __( 'Nothing to apply — pick at least one operation.', 'sas-menu-maker' ),
					'bulkNoSelection' => __( 'Select at least one item first.', 'sas-menu-maker' ),
					/* translators: %d: number of currently active filters. */
					'filtersActive'   => __( '%d filter(s) active', 'sas-menu-maker' ),
					'noMatches'       => __( 'No items match the current filters.', 'sas-menu-maker' ),
					'offerLinesNone'  => __( 'None', 'sas-menu-maker' ),
					/* translators: %d: number of line items in an offer. */
					'offerLinesCount' => __( '%d line(s)', 'sas-menu-maker' ),
					'offerQuantity'   => __( 'Qty', 'sas-menu-maker' ),
					'offerPickVariant' => __( '— pick variant —', 'sas-menu-maker' ),
					'offerNoVariant'  => __( '(no variant)', 'sas-menu-maker' ),
					'offerRemoveLine' => __( 'Remove line', 'sas-menu-maker' ),
					'offerAlways'     => __( 'Always', 'sas-menu-maker' ),
					/* translators: %s: start date of the offer validity. */
					'offerFrom'       => __( 'From %s', 'sas-menu-maker' ),
					/* translators: %s: end date of the offer validity. */
					'offerUntil'      => __( 'Until %s', 'sas-menu-maker' ),
					/* translators: 1: start date, 2: end date of the offer validity. */
					'offerBetween'    => __( '%1$s – %2$s', 'sas-menu-maker' ),
					'offerCurrent'    => __( 'Currently valid', 'sas-menu-maker' ),
					'offerUpcoming'   => __( 'Upcoming', 'sas-menu-maker' ),
					'offerExpired'    => __( 'Expired', 'sas-menu-maker' ),
					'defaultCategory' => __( 'Default', 'sas-menu-maker' ),
					'defaultCategoryTitle' => __( 'Pre-selected in the frontend filter', 'sas-menu-maker' ),
				),
			)
		);
	}

	/**
	 * Append a body class on MenuCraft screens so CSS can scope safely.
	 *
	 * @param string $classes Space-separated body classes.
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && in_array( $screen->id, $this->page_hooks, true ) ) {
			$classes .= ' menucraft-admin';
		}

		return $classes;
	}

	/**
	 * Register the top-level admin menu and its child screens.
	 *
	 * The first submenu re-uses the parent slug to override the label that
	 * WordPress would otherwise auto-generate ("MenuCraft" → "Dashboard").
	 * All child screens render a shared placeholder view until their real
	 * UI is built.
	 */
	public function register_admin_menu() {
		$this->page_hooks[] = add_menu_page(
			__( 'SAS Menu Maker', 'sas-menu-maker' ),
			__( 'SAS Menu Maker', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker',
			array( $this, 'render_admin_page' ),
			'dashicons-coffee'
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'SAS Menu Maker Dashboard', 'sas-menu-maker' ),
			__( 'Dashboard', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker',
			array( $this, 'render_admin_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'Items', 'sas-menu-maker' ),
			__( 'Items', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-items',
			array( $this, 'render_items_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'Categories', 'sas-menu-maker' ),
			__( 'Categories', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-categories',
			array( $this, 'render_categories_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'Tags', 'sas-menu-maker' ),
			__( 'Tags', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-tags',
			array( $this, 'render_tags_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'Allergens', 'sas-menu-maker' ),
			__( 'Allergens', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-allergens',
			array( $this, 'render_allergens_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'Offers', 'sas-menu-maker' ),
			__( 'Offers', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-offers',
			array( $this, 'render_offers_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'Options', 'sas-menu-maker' ),
			__( 'Options', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-options',
			array( $this, 'render_options_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'SAS Menu Maker Help & Documentation', 'sas-menu-maker' ),
			__( 'Help & Docs', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-help',
			array( $this, 'render_help_page' )
		);

		$this->page_hooks[] = add_submenu_page(
			'sas-menu-maker',
			__( 'About SAS Menu Maker', 'sas-menu-maker' ),
			__( 'About', 'sas-menu-maker' ),
			'manage_options',
			'sas-menu-maker-about',
			array( $this, 'render_about_page' )
		);

		// add_menu_page / add_submenu_page return false when the current user
		// lacks the capability — filter those out to keep the list truthy.
		$this->page_hooks = array_values( array_filter( $this->page_hooks ) );
	}

	/**
	 * Render the MenuCraft dashboard (parent-menu page).
	 */
	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-display.php';
	}

	/**
	 * Render a shared placeholder screen for submenus without a real UI yet.
	 */
	public function render_placeholder() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-placeholder.php';
	}

	/**
	 * Render the Categories admin screen.
	 */
	public function render_categories_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-categories.php';
	}

	/**
	 * Render the Tags admin screen.
	 */
	public function render_tags_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-tags.php';
	}

	/**
	 * Render the Allergens admin screen.
	 */
	public function render_allergens_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-allergens.php';
	}

	/**
	 * Render the Items admin screen.
	 */
	public function render_items_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-items.php';
	}

	/**
	 * Render the Offers admin screen.
	 */
	public function render_offers_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-offers.php';
	}

	/**
	 * Render the Options admin screen.
	 */
	public function render_options_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-options.php';
	}

	/**
	 * Render the Help & Docs admin screen.
	 */
	public function render_help_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-help.php';
	}

	/**
	 * Render the About admin screen.
	 */
	public function render_about_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require MENUCRAFT_PLUGIN_DIR . 'admin/partials/menucraft-admin-about.php';
	}

	/**
	 * True when the given hook suffix belongs to a MenuCraft screen.
	 *
	 * @param string $hook_suffix Hook suffix passed by admin_enqueue_scripts.
	 * @return bool
	 */
	private function is_menucraft_screen( $hook_suffix ) {
		return in_array( $hook_suffix, $this->page_hooks, true );
	}
}
