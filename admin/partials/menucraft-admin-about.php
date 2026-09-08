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

$help_url    = admin_url( 'admin.php?page=menucraft-help' );
$github_url  = 'https://github.com/parsedesigncom/MenuCraft';
$wporg_url   = 'https://wordpress.org/plugins/menucraft/';

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
		'title' => __( 'Full menu builder', 'menucraft' ),
		'desc'  => __( 'Items with variants, categories, tags and allergen codes — all in one place.', 'menucraft' ),
	),
	array(
		'icon'  => 'dashicons-tickets-alt',
		'title' => __( 'Offers & specials', 'menucraft' ),
		'desc'  => __( 'Bundle items at a fixed price with validity dates and free-form conditions.', 'menucraft' ),
	),
	array(
		'icon'  => 'dashicons-shortcode',
		'title' => __( 'Shortcodes & blocks', 'menucraft' ),
		'desc'  => __( 'Show the menu with [menucraft], [menucraft_offers] or [menucraft_group], or use the matching Gutenberg blocks.', 'menucraft' ),
	),
	array(
		'icon'  => 'dashicons-admin-appearance',
		'title' => __( 'Themable output', 'menucraft' ),
		'desc'  => __( 'Every template overridable from your theme. One CSS + one JS on the front end, only when needed.', 'menucraft' ),
	),
	array(
		'icon'  => 'dashicons-editor-code',
		'title' => __( 'Developer-friendly', 'menucraft' ),
		'desc'  => __( 'Every rendered region flows through filter and action hooks. REST API under /menucraft/v1/.', 'menucraft' ),
	),
	array(
		'icon'  => 'dashicons-translation',
		'title' => __( 'Translation-ready', 'menucraft' ),
		'desc'  => __( 'All user-facing strings translatable. Kept short so translation tools stay within their limits.', 'menucraft' ),
	),
);
?>
<div class="wrap menucraft-wrap">
	<div class="menucraft-card">
		<header class="menucraft-page-header">
			<div class="menucraft-page-header-row">
				<h1 class="menucraft-page-title">
					<?php esc_html_e( 'About MenuCraft', 'menucraft' ); ?>
				</h1>
				<?php if ( '' !== $version ) : ?>
					<span class="menucraft-about-version">
						<?php
						printf(
							/* translators: %s: version number */
							esc_html__( 'Version %s', 'menucraft' ),
							esc_html( $version )
						);
						?>
					</span>
				<?php endif; ?>
			</div>
			<p class="menucraft-page-description">
				<?php esc_html_e( 'A free, self-contained menu manager for restaurants, cafés and bars.', 'menucraft' ); ?>
			</p>
			<hr class="menucraft-page-sep">
		</header>

		<div class="menucraft-page-body">

			<section class="menucraft-about-intro">
				<p>
					<?php esc_html_e( 'MenuCraft helps you build and display a real restaurant menu — food, drinks, sizes, prices, allergens, seasonal offers — from one place in your WordPress admin.', 'menucraft' ); ?>
				</p>
				<p>
					<?php esc_html_e( 'No external services. No tracking. No jQuery. Your data lives in the plugin\'s own tables, and the front-end output is a single CSS + a single JavaScript file, loaded only on pages that show the menu.', 'menucraft' ); ?>
				</p>
			</section>

			<section class="menucraft-about-features">
				<h2 class="menucraft-about-section-title"><?php esc_html_e( 'What you get', 'menucraft' ); ?></h2>
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
				<h2 class="menucraft-about-section-title"><?php esc_html_e( 'Get help & get involved', 'menucraft' ); ?></h2>
				<ul class="menucraft-about-link-list">
					<li>
						<span class="dashicons dashicons-book" aria-hidden="true"></span>
						<a href="<?php echo esc_url( $urls['help'] ); ?>"><?php esc_html_e( 'Help & Docs', 'menucraft' ); ?></a>
						<span class="menucraft-about-link-desc"><?php esc_html_e( 'Getting started guide plus per-feature walkthroughs.', 'menucraft' ); ?></span>
					</li>
					<li>
						<span class="dashicons dashicons-editor-code" aria-hidden="true"></span>
						<a href="<?php echo esc_url( $urls['github'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'GitHub repository', 'menucraft' ); ?></a>
						<span class="menucraft-about-link-desc"><?php esc_html_e( 'Source code, issue tracker and full developer reference (README.md).', 'menucraft' ); ?></span>
					</li>
					<li>
						<span class="dashicons dashicons-wordpress" aria-hidden="true"></span>
						<a href="<?php echo esc_url( $urls['wporg'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WordPress.org page', 'menucraft' ); ?></a>
						<span class="menucraft-about-link-desc"><?php esc_html_e( 'Ratings, support forum and downloads.', 'menucraft' ); ?></span>
					</li>
				</ul>
			</section>

			<section class="menucraft-about-credits">
				<h2 class="menucraft-about-section-title"><?php esc_html_e( 'Credits & license', 'menucraft' ); ?></h2>
				<div class="menucraft-about-credits-block">
					<a class="menucraft-about-brand"
						href="https://saeidsamani.de"
						target="_blank"
						rel="noopener"
						aria-label="<?php esc_attr_e( 'Saeid Samani — portfolio', 'menucraft' ); ?>">
						<img src="<?php echo esc_url( MENUCRAFT_PLUGIN_URL . 'assets/images/brand.jpg' ); ?>"
							alt="Saeid Samani"
							loading="lazy">
					</a>
					<div class="menucraft-about-credits-body">
						<p>
							<?php
							printf(
								/* translators: %s: author link */
								esc_html__( 'Made by %s — freelance web developer and designer, WordPress work for restaurants, cafés and independent shops.', 'menucraft' ),
								'<a href="https://saeidsamani.de" target="_blank" rel="noopener">Saeid Samani</a>'
							);
							?>
						</p>
						<p>
							<?php
							printf(
								/* translators: %s: license link */
								esc_html__( 'Released under %s. Free to use, modify and redistribute.', 'menucraft' ),
								'<a href="https://www.gnu.org/licenses/gpl-2.0.html" target="_blank" rel="noopener noreferrer">GPL-2.0-or-later</a>'
							);
							?>
						</p>
						<p class="menucraft-about-thanks">
							<?php esc_html_e( 'Thanks for using MenuCraft — enjoy building your menu.', 'menucraft' ); ?>
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
