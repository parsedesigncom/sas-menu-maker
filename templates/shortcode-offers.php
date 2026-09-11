<?php
/**
 * SAS Menu Maker — default [sas_menu_offers] shortcode template.
 *
 * Themes may override this file by copying it to:
 *   your-theme/sas-menu-maker/shortcode-offers.php
 *
 * Available variables (extracted by SAS_Menu_Maker_Public::render_offers_shortcode):
 *   $offers     array<int,array>  Filtered active offers.
 *   $items_map  array<int,array>  id => hydrated item, used to render lines.
 *   $config     array             Normalised shortcode config.
 *   $atts       array             Raw shortcode attributes.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This template is `include`d from SAS_Menu_Maker_Public::render_offers_shortcode(),
// so every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$instance_id  = isset( $config['instance_id'] ) ? $config['instance_id'] : 'sas-menu-maker-offers';
$image_pos    = isset( $config['image_pos'] ) ? $config['image_pos'] : 'left';
$grid_class   = ! empty( $config['grid_enabled'] ) ? ' sas-menu-maker-offers--grid' : ' sas-menu-maker-offers--rows';
$custom_class = ! empty( $config['custom_class'] ) ? ' ' . $config['custom_class'] : '';

$list_override = apply_filters( 'sas_menu_maker_offers_shortcode_items_html', '', $offers );
?>
<div class="sas-menu-maker sas-menu-maker-offers sas-menu-maker-image-<?php echo esc_attr( $image_pos ); ?><?php echo esc_attr( $grid_class ); ?><?php echo esc_attr( $custom_class ); ?>"
	id="<?php echo esc_attr( $instance_id ); ?>"
	data-sas-menu-maker-root>

	<?php if ( is_string( $list_override ) && '' !== $list_override ) : ?>
		<?php echo $list_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
		<?php do_action( 'sas_menu_maker_before_offers', $offers ); ?>
		<div class="sas-menu-maker-offers-list" data-sas-menu-maker-offers-list>
			<?php if ( empty( $offers ) ) : ?>
				<p class="sas-menu-maker-empty">
					<?php esc_html_e( 'No current offers.', 'sas-menu-maker' ); ?>
				</p>
			<?php else : ?>
				<?php foreach ( $offers as $offer ) : ?>
					<?php echo SAS_Menu_Maker_Public::render_offer( $offer, $items_map, $config ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php do_action( 'sas_menu_maker_after_offers', $offers ); ?>
	<?php endif; ?>

	<?php // ---- Details modal (populated by JS) ---- ?>
	<div class="sas-menu-maker-modal"
		id="<?php echo esc_attr( $instance_id ); ?>-modal"
		role="dialog"
		aria-modal="true"
		aria-hidden="true"
		aria-labelledby="<?php echo esc_attr( $instance_id ); ?>-modal-title"
		data-sas-menu-maker-modal>
		<div class="sas-menu-maker-modal-backdrop" data-sas-menu-maker-modal-close></div>
		<div class="sas-menu-maker-modal-dialog">
			<header class="sas-menu-maker-modal-header">
				<h2 class="sas-menu-maker-modal-title" id="<?php echo esc_attr( $instance_id ); ?>-modal-title"></h2>
				<button type="button"
					class="sas-menu-maker-modal-close"
					data-sas-menu-maker-modal-close
					aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">&times;</button>
			</header>
			<div class="sas-menu-maker-modal-body" data-sas-menu-maker-modal-body></div>
		</div>
	</div>

</div>
