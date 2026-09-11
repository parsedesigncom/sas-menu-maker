<?php
/**
 * Main SAS Menu Maker admin page view (Dashboard).
 *
 * At-a-glance counters — offers on top because they're time-sensitive,
 * then items with the variant + missing-image breakdown, then a per-
 * taxonomy count. Each number animates from 0 to its target on page
 * load via the small counter helper in sas-menu-maker-admin.js.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This file is `require`d from SAS_Menu_Maker_Admin::render_admin_page(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

// ---- Load raw data ---------------------------------------------------

$items      = SAS_Menu_Maker_Item_Repository::all();
$offers     = SAS_Menu_Maker_Offer_Repository::all();
$categories = SAS_Menu_Maker_Category_Repository::all();
$tags       = SAS_Menu_Maker_Tag_Repository::all();
$allergens  = SAS_Menu_Maker_Allergen_Repository::all();

// ---- Item stats ------------------------------------------------------

$items_total          = count( $items );
$items_no_image       = 0;
$items_with_variants  = 0;
$variants_total       = 0;

foreach ( $items as $it ) {
	if ( empty( $it['media_id'] ) ) {
		$items_no_image++;
	}
	$count = ! empty( $it['variants'] ) ? count( $it['variants'] ) : 0;
	if ( $count > 0 ) {
		$items_with_variants++;
		$variants_total += $count;
	}
}

// ---- Offer stats -----------------------------------------------------

$offers_total   = count( $offers );
$offers_active  = 0;
$offers_current = 0;
$now_mysql      = current_time( 'mysql', 1 );

foreach ( $offers as $of ) {
	if ( ! empty( $of['is_active'] ) ) {
		$offers_active++;
	}
	if ( empty( $of['is_active'] ) ) {
		continue;
	}
	$from  = ! empty( $of['valid_from'] ) ? $of['valid_from'] : null;
	$until = ! empty( $of['valid_until'] ) ? $of['valid_until'] : null;
	$in_from  = ! $from || $from <= $now_mysql;
	$in_until = ! $until || $until >= $now_mysql;
	if ( $in_from && $in_until ) {
		$offers_current++;
	}
}

// Tile helper — renders one counter card. $value is the numeric target,
// the initial text is "0" so the count-up starts from zero.
$tile = function ( $value, $label, $modifier = '' ) {
	$class = 'sas-menu-maker-counter' . ( '' !== $modifier ? ' sas-menu-maker-counter--' . $modifier : '' );
	?>
	<div class="<?php echo esc_attr( $class ); ?>">
		<span class="sas-menu-maker-counter-value" data-sas-menu-maker-counter="<?php echo esc_attr( (string) $value ); ?>">0</span>
		<span class="sas-menu-maker-counter-label"><?php echo esc_html( $label ); ?></span>
	</div>
	<?php
};
?>
<div class="wrap sas-menu-maker-wrap">
	<div class="sas-menu-maker-card">
		<header class="sas-menu-maker-page-header">
			<div class="sas-menu-maker-page-header-row">
				<h1 class="sas-menu-maker-page-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			</div>
			<p class="sas-menu-maker-page-description">
				<?php esc_html_e( 'At a glance — the numbers behind your menu.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">

			<?php // -------------------- Offers (top priority) -------------------- ?>
			<section class="sas-menu-maker-counters sas-menu-maker-counters--primary">
				<h2 class="sas-menu-maker-counters-title"><?php esc_html_e( 'Offers', 'sas-menu-maker' ); ?></h2>
				<div class="sas-menu-maker-counters-grid">
					<?php $tile( $offers_total,   __( 'Total offers', 'sas-menu-maker' ),      'primary' ); ?>
					<?php $tile( $offers_active,  __( 'Active', 'sas-menu-maker' ),           'primary' ); ?>
					<?php $tile( $offers_current, __( 'Currently valid', 'sas-menu-maker' ), 'primary' ); ?>
				</div>
			</section>

			<?php // -------------------- Items -------------------- ?>
			<section class="sas-menu-maker-counters">
				<h2 class="sas-menu-maker-counters-title"><?php esc_html_e( 'Items', 'sas-menu-maker' ); ?></h2>
				<div class="sas-menu-maker-counters-grid">
					<?php $tile( $items_total,         __( 'Total items', 'sas-menu-maker' ) ); ?>
					<?php $tile( $items_with_variants, __( 'Items with variants', 'sas-menu-maker' ) ); ?>
					<?php $tile( $variants_total,      __( 'Variants in total', 'sas-menu-maker' ) ); ?>
					<?php $tile( $items_no_image,      __( 'Without image', 'sas-menu-maker' ), $items_no_image > 0 ? 'warn' : '' ); ?>
				</div>
			</section>

			<?php // -------------------- Taxonomies -------------------- ?>
			<section class="sas-menu-maker-counters">
				<h2 class="sas-menu-maker-counters-title"><?php esc_html_e( 'Categories, tags & allergens', 'sas-menu-maker' ); ?></h2>
				<div class="sas-menu-maker-counters-grid">
					<?php $tile( count( $categories ), __( 'Categories', 'sas-menu-maker' ) ); ?>
					<?php $tile( count( $tags ),       __( 'Tags', 'sas-menu-maker' ) ); ?>
					<?php $tile( count( $allergens ),  __( 'Allergens', 'sas-menu-maker' ) ); ?>
				</div>
			</section>

		</div>

		<footer class="sas-menu-maker-page-footer">
			<hr class="sas-menu-maker-page-sep">
		</footer>
	</div>
</div>
