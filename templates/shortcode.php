<?php
/**
 * SAS Menu Maker — default [sas_menu] shortcode template.
 *
 * Themes may override this file by copying it to:
 *   your-theme/sas-menu-maker/shortcode.php
 *
 * Available variables (extracted by SAS_Menu_Maker_Public::render_shortcode):
 *   $items       array<int,array>  Hydrated, filtered, active items.
 *   $categories  array<int,array>  Categories that appear on visible items.
 *   $tags        array<int,array>  Tags that appear on visible items.
 *   $allergens   array<int,array>  Allergen map keyed by allergen id.
 *   $config      array             Normalised shortcode config (see SAS_Menu_Maker_Public::normalise_atts).
 *   $atts        array             Raw shortcode attributes.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This template is `include`d from SAS_Menu_Maker_Public::render_shortcode(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$instance_id  = isset( $config['instance_id'] ) ? $config['instance_id'] : 'sas-menu-maker-menu';
$image_pos    = isset( $config['image_pos'] ) ? $config['image_pos'] : 'left';
$grid_class   = ! empty( $config['grid_enabled'] ) ? ' sas-menu-maker-menu--grid' : ' sas-menu-maker-menu--rows';
$titles       = isset( $config['titles'] ) ? $config['titles'] : array();
$custom_class = ! empty( $config['custom_class'] ) ? ' ' . $config['custom_class'] : '';

$filters_override = apply_filters( 'sas_menu_maker_shortcode_filters_html', '', $categories, $tags );
$cats_override    = apply_filters( 'sas_menu_maker_shortcode_categories_html', '', $categories );
$tags_override    = apply_filters( 'sas_menu_maker_shortcode_tags_html', '', $tags );
$items_override   = apply_filters( 'sas_menu_maker_shortcode_items_html', '', $items );
?>
<div class="sas-menu-maker sas-menu-maker-menu<?php echo esc_attr( $grid_class ); ?> sas-menu-maker-image-<?php echo esc_attr( $image_pos ); ?><?php echo esc_attr( $custom_class ); ?>"
	id="<?php echo esc_attr( $instance_id ); ?>"
	data-sas-menu-maker-menu
	data-sas-menu-maker-root>

	<?php if ( is_string( $filters_override ) && '' !== $filters_override ) : ?>
		<?php echo $filters_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
		<?php do_action( 'sas_menu_maker_before_filters', $categories, $tags ); ?>
		<div class="sas-menu-maker-filter-bar" data-sas-menu-maker-filter-bar>

			<?php if ( ! empty( $categories ) ) : ?>
				<?php if ( is_string( $cats_override ) && '' !== $cats_override ) : ?>
					<?php echo $cats_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<div class="sas-menu-maker-filter-group" data-sas-menu-maker-filter-group="category">
						<span class="sas-menu-maker-filter-label"><?php echo esc_html( $titles['categories'] ); ?></span>
						<div class="sas-menu-maker-filter-chips">
							<?php foreach ( $categories as $cat ) : ?>
								<button type="button"
									class="sas-menu-maker-filter-chip"
									data-sas-menu-maker-filter="category"
									data-sas-menu-maker-value="<?php echo esc_attr( (string) $cat['id'] ); ?>"
									<?php if ( ! empty( $cat['is_default'] ) ) : ?>data-sas-menu-maker-default<?php endif; ?>>
									<?php echo esc_html( $cat['name'] ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! empty( $tags ) ) : ?>
				<?php if ( is_string( $tags_override ) && '' !== $tags_override ) : ?>
					<?php echo $tags_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<div class="sas-menu-maker-filter-group" data-sas-menu-maker-filter-group="tag">
						<span class="sas-menu-maker-filter-label"><?php echo esc_html( $titles['tags'] ); ?></span>
						<div class="sas-menu-maker-filter-chips">
							<?php foreach ( $tags as $tag ) : ?>
								<button type="button"
									class="sas-menu-maker-filter-chip"
									data-sas-menu-maker-filter="tag"
									data-sas-menu-maker-value="<?php echo esc_attr( (string) $tag['id'] ); ?>">
									<?php echo esc_html( $tag['name'] ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

		</div>
		<?php do_action( 'sas_menu_maker_after_filters', $categories, $tags ); ?>
	<?php endif; ?>

	<?php if ( is_string( $items_override ) && '' !== $items_override ) : ?>
		<?php echo $items_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
		<?php do_action( 'sas_menu_maker_before_items', $items ); ?>
		<div class="sas-menu-maker-items" data-sas-menu-maker-items>
			<?php if ( empty( $items ) ) : ?>
				<p class="sas-menu-maker-empty">
					<?php esc_html_e( 'No menu items to display yet.', 'sas-menu-maker' ); ?>
				</p>
			<?php else : ?>
				<?php foreach ( $items as $item ) : ?>
					<?php echo SAS_Menu_Maker_Public::render_item( $item, $allergens, $tags, $config ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php do_action( 'sas_menu_maker_after_items', $items ); ?>
	<?php endif; ?>

	<?php
	// ---------- Allergens legend (fine-print list at end of menu) ----------
	if ( ! empty( $config['show_allergens_legend'] ) && ! empty( $allergens ) ) :
		/**
		 * Filter: replace the allergens-legend HTML entirely.
		 * Return '' to keep the built-in output.
		 *
		 * @param string $html      Empty by default.
		 * @param array  $allergens Allergen rows keyed by id.
		 */
		$legend_override = apply_filters( 'sas_menu_maker_shortcode_allergens_legend_html', '', $allergens );

		if ( is_string( $legend_override ) && '' !== $legend_override ) {
			echo $legend_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			/**
			 * Action: right before the allergens legend.
			 *
			 * @param array $allergens
			 */
			do_action( 'sas_menu_maker_before_allergens_legend', $allergens );
			?>
			<div class="sas-menu-maker-allergens-legend" data-sas-menu-maker-allergens-legend>
				<span class="sas-menu-maker-allergens-legend-label">
					<?php echo esc_html( $titles['allergens'] ); ?>:
				</span>
				<span class="sas-menu-maker-allergens-legend-list">
					<?php foreach ( $allergens as $allergen ) : ?>
						<span class="sas-menu-maker-allergens-legend-item">
							<strong><?php echo esc_html( $allergen['code'] ); ?></strong>
							<?php echo esc_html( $allergen['name'] ); ?>
						</span>
					<?php endforeach; ?>
				</span>
			</div>
			<?php
			/**
			 * Action: right after the allergens legend.
			 *
			 * @param array $allergens
			 */
			do_action( 'sas_menu_maker_after_allergens_legend', $allergens );
		}
	endif;
	?>

	<?php // ---- Long-description / variants modal shell (populated by JS) ---- ?>
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
