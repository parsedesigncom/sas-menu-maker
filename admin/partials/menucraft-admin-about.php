<?php
/**
 * About MenuCraft admin screen.
 *
 * Compact "who we are, what this does, where to get help" page. All
 * text lives in short __() strings so translation tools handle it
 * cleanly.
 *
 * @package MenuCraft
 */

defined( 'ABSPATH' ) || exit;

// This file is `require`d from MenuCraft_Admin::render_about_page(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$version = defined( 'MENUCRAFT_VERSION' ) ? MENUCRAFT_VERSION : '';

$help_url    = admin_url( 'admin.php?page=sas-menu-maker-help' );
$github_url  = 'https://github.com/saeidsamani/sas-menu-maker';
$wporg_url   = 'https://wordpress.org/plugins/sas-menu-maker/';

/**
 * Filter the URLs displayed on the About page.
 *
 * @param array<string,string> $urls Keyed by link handle: help, github, wporg.
 */
$urls = apply_filters(
	'menucraft_about_urls',
	array(
		'help'   => $help_url,
		'github' => $github_url,
		'wporg'  => $wporg_url,
	)
);

$features = array(
	array(
		'icon'  => 'dashicons-list-view',
		'title' => __( 'Full menu builder', 'sas-menu-maker' ),
		'desc'  => __( 'Items with variants, categories, tags and allergen codes — all in one place.', 'sas-menu-maker' ),
	),
	array(
		'icon'  => 'dashicons-tickets-alt',
		'title' => __( 'Offers & specials', 'sas-menu-maker' ),
		'desc'  => __( 'Bundle items at a fixed price with validity dates and free-form conditions.', 'sas-menu-maker' ),
	),
	array(
		'icon'  => 'dashicons-shortcode',
		'title' => __( 'Shortcodes & blocks', 'sas-menu-maker' ),
		'desc'  => __( 'Show the menu with [sas_menu], [sas_menu_offers] or [sas_menu_group], or use the matching Gutenberg blocks.', 'sas-menu-maker' ),
	),
	array(
		'icon'  => 'dashicons-admin-appearance',
		'title' => __( 'Themable output', 'sas-menu-maker' ),
		'desc'  => __( 'Every template overridable from your theme. One CSS + one JS on the front end, only when needed.', 'sas-menu-maker' ),
	),
	array(
		'icon'  => 'dashicons-editor-code',
		'title' => __( 'Developer-friendly', 'sas-menu-maker' ),
		'desc'  => __( 'Every rendered region flows through filter and action hooks. REST API under /sas-menu-maker/v1/.', 'sas-menu-maker' ),
	),
	array(
		'icon'  => 'dashicons-translation',
		'title' => __( 'Translation-ready', 'sas-menu-maker' ),
		'desc'  => __( 'All user-facing strings translatable. Kept short so translation tools stay within their limits.', 'sas-menu-maker' ),
	),
);
?>
<div class="wrap menucraft-wrap">
	<div class="menucraft-card">
		<header class="menucraft-page-header">
			<div class="menucraft-page-header-row">
				<h1 class="menucraft-page-title">
					<?php esc_html_e( 'About SAS Menu Maker', 'sas-menu-maker' ); ?>
				</h1>
				<?php if ( '' !== $version ) : ?>
					<span class="menucraft-about-version">
						<?php
						printf(
							/* translators: %s: version number */
							esc_html__( 'Version %s', 'sas-menu-maker' ),
							esc_html( $version )
						);
						?>
					</span>
				<?php endif; ?>
			</div>
			<p class="menucraft-page-description">
				<?php esc_html_e( 'A free, self-contained menu manager for restaurants, cafés and bars.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="menucraft-page-sep">
		</header>

		<div class="menucraft-page-body">

			<section class="menucraft-about-intro">
				<p>
					<?php esc_html_e( 'SAS Menu Maker helps you build and display a real restaurant menu — food, drinks, sizes, prices, allergens, seasonal offers — from one place in your WordPress admin.', 'sas-menu-maker' ); ?>
				</p>
				<p>
					<?php esc_html_e( 'No external services. No tracking. No jQuery. Your data lives in the plugin\'s own tables, and the front-end output is a single CSS + a single JavaScript file, loaded only on pages that show the menu.', 'sas-menu-maker' ); ?>
				</p>
			</section>

			<section class="menucraft-about-features">
				<h2 class="menucraft-about-section-title"><?php esc_html_e( 'What you get', 'sas-menu-maker' ); ?></h2>
				<?php foreach ( $features as $i => $f ) : ?>
					<?php if ( $i > 0 ) : ?>
						<hr class="menucraft-about-sep">
					<?php endif; ?>
					<div class="menucraft-about-feature">
						<span class="menucraft-about-feature-icon dashicons <?php echo esc_attr( $f['icon'] ); ?>" aria-hidden="true"></span>
						<div class="menucraft-about-feature-body">
							<h3 class="menucraft-about-feature-title"><?php echo esc_html( $f['title'] ); ?></h3>
							<p class="menucraft-about-feature-desc"><?php echo esc_html( $f['desc'] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</section>

			<section class="menucraft-about-links">
				<h2 class="menucraft-about-section-title"><?php esc_html_e( 'Get help & get involved', 'sas-menu-maker' ); ?></h2>
				<ul class="menucraft-about-link-list">
					<li>
						<span class="dashicons dashicons-book" aria-hidden="true"></span>
						<a href="<?php echo esc_url( $urls['help'] ); ?>"><?php esc_html_e( 'Help & Docs', 'sas-menu-maker' ); ?></a>
						<span class="menucraft-about-link-desc"><?php esc_html_e( 'Getting started guide plus per-feature walkthroughs.', 'sas-menu-maker' ); ?></span>
					</li>
					<li>
						<span class="dashicons dashicons-editor-code" aria-hidden="true"></span>
						<a href="<?php echo esc_url( $urls['github'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'GitHub repository', 'sas-menu-maker' ); ?></a>
						<span class="menucraft-about-link-desc"><?php esc_html_e( 'Source code, issue tracker and full developer reference (README.md).', 'sas-menu-maker' ); ?></span>
					</li>
					<li>
						<span class="dashicons dashicons-wordpress" aria-hidden="true"></span>
						<a href="<?php echo esc_url( $urls['wporg'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WordPress.org page', 'sas-menu-maker' ); ?></a>
						<span class="menucraft-about-link-desc"><?php esc_html_e( 'Ratings, support forum and downloads.', 'sas-menu-maker' ); ?></span>
					</li>
				</ul>
			</section>

			<section class="menucraft-about-credits">
				<h2 class="menucraft-about-section-title"><?php esc_html_e( 'Credits & license', 'sas-menu-maker' ); ?></h2>
				<div class="menucraft-about-credits-block">
					<a class="menucraft-about-brand"
						href="https://saeidsamani.de"
						target="_blank"
						rel="noopener"
						aria-label="<?php esc_attr_e( 'Saeid Samani — portfolio', 'sas-menu-maker' ); ?>">
						<img src="<?php echo esc_url( MENUCRAFT_PLUGIN_URL . 'assets/images/brand.jpg' ); ?>"
							alt="Saeid Samani"
							loading="lazy">
					</a>
					<div class="menucraft-about-credits-body">
						<p>
							<?php
							printf(
								/* translators: %s: author link */
								esc_html__( 'Made by %s — freelance web developer and designer, WordPress work for restaurants, cafés and independent shops.', 'sas-menu-maker' ),
								'<a href="https://saeidsamani.de" target="_blank" rel="noopener">Saeid Samani</a>'
							);
							?>
						</p>
						<p>
							<?php
							printf(
								/* translators: %s: license link */
								esc_html__( 'Released under %s. Free to use, modify and redistribute.', 'sas-menu-maker' ),
								'<a href="https://www.gnu.org/licenses/gpl-2.0.html" target="_blank" rel="noopener noreferrer">GPL-2.0-or-later</a>'
							);
							?>
						</p>
						<p class="menucraft-about-thanks">
							<?php esc_html_e( 'Thanks for using SAS Menu Maker — enjoy building your menu.', 'sas-menu-maker' ); ?>
						</p>
					</div>
				</div>
			</section>

		</div>

		<footer class="menucraft-page-footer">
			<hr class="menucraft-page-sep">
		</footer>
	</div>
</div>
