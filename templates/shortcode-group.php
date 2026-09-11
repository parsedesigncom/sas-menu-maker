<?php
/**
 * SAS Menu Maker — default [sas_menu_group] shortcode template.
 *
 * Themes may override this file by copying it to:
 *   your-theme/sas-menu-maker/shortcode-group.php
 *
 * Available variables (extracted by SAS_Menu_Maker_Public::render_group_shortcode):
 *   $source_type  string             'category' | 'tag' | ''
 *   $source       array|null         Resolved category/tag row, or null.
 *   $items        array<int,array>   Active items in that group.
 *   $allergens    array<int,array>   Allergen map keyed by id.
 *   $tags         array<int,array>   Tag map keyed by id.
 *   $config       array              Normalised shortcode config.
 *   $atts         array              Raw shortcode attributes.
 *
 * Hook overview:
 *   Filter  sas_menu_maker_group_shortcode_html               ($html, $context)
 *   Filter  sas_menu_maker_group_shortcode_source             ($source, $type, $ref)
 *   Filter  sas_menu_maker_group_shortcode_items              ($items, $type, $source_id)
 *   Filter  sas_menu_maker_group_shortcode_header_html        ($html, $source, $config)
 *   Filter  sas_menu_maker_group_shortcode_items_html         ($html, $items)
 *   Filter  sas_menu_maker_group_shortcode_allergens_legend_html ($html, $allergens)
 *   Filter  sas_menu_maker_shortcode_item_html                ($html, $item, …)  reused per item
 *   Action  sas_menu_maker_before_group_shortcode / _after_group_shortcode ($context)
 *   Action  sas_menu_maker_before_group_header  / _after_group_header       ($source, $config)
 *   Action  sas_menu_maker_before_items         / _after_items               ($items)
 *   Action  sas_menu_maker_before_item          / _after_item                ($item, $config)  reused per item
 *   Action  sas_menu_maker_before_allergens_legend / _after_allergens_legend ($allergens)
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This template is `include`d from SAS_Menu_Maker_Public::render_group_shortcode(),
// so every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$instance_id  = isset( $config['instance_id'] ) ? $config['instance_id'] : 'sas-menu-maker-group';
$image_pos    = isset( $config['image_pos'] ) ? $config['image_pos'] : 'left';
$grid_class   = ! empty( $config['grid_enabled'] ) ? ' sas-menu-maker-group--grid' : ' sas-menu-maker-group--rows';
$custom_class = ! empty( $config['custom_class'] ) ? ' ' . $config['custom_class'] : '';
$collapsed    = ! empty( $config['collapsed'] );

$source_name  = $source ? (string) $source['name'] : '';
$source_desc  = $source ? (string) $source['description'] : '';
$source_media = ( $source && ! empty( $source['media_id'] ) )
	? wp_get_attachment_image_url( (int) $source['media_id'], 'large' )
	: '';

/**
 * Filter: replace the hero header HTML entirely. Return '' to keep default.
 *
 * @param string                   $html   Empty by default.
 * @param array<string,mixed>|null $source Resolved source row.
 * @param array<string,mixed>      $config Normalised config.
 */
$header_override = apply_filters( 'sas_menu_maker_group_shortcode_header_html', '', $source, $config );

$header_html = '';
if ( ! empty( $config['show_header'] ) && $source ) {
	if ( is_string( $header_override ) && '' !== $header_override ) {
		$header_html = $header_override;
	} else {
		$header_html .= '<header class="sas-menu-maker-group-header">';
		if ( $source_media && ! $collapsed ) {
			$header_html .= '<div class="sas-menu-maker-group-header-media"><img src="' . esc_url( $source_media ) . '" alt="' . esc_attr( $source_name ) . '" loading="lazy"></div>';
		}
		$header_html .= '<div class="sas-menu-maker-group-header-body">';
		$header_html .= '<h2 class="sas-menu-maker-group-title">' . esc_html( $source_name ) . '</h2>';
		if ( '' !== $source_desc ) {
			$header_html .= '<p class="sas-menu-maker-group-desc">' . esc_html( $source_desc ) . '</p>';
		}
		$header_html .= '</div>';
		$header_html .= '</header>';
	}
}

// Collapsed mode places the image in the expanded content, not the header.
$expanded_media_html = '';
if ( $collapsed && $source_media && ! empty( $config['show_header'] ) ) {
	$expanded_media_html = '<div class="sas-menu-maker-group-expanded-media"><img src="' . esc_url( $source_media ) . '" alt="' . esc_attr( $source_name ) . '" loading="lazy"></div>';
}

/**
 * Filter: replace the items list HTML entirely. Return '' to keep default.
 *
 * @param string $html
 * @param array  $items
 */
$items_override = apply_filters( 'sas_menu_maker_group_shortcode_items_html', '', $items );

/**
 * Filter: replace the allergen legend HTML entirely. Return '' to keep default.
 *
 * @param string $html
 * @param array  $allergens
 */
$legend_override = apply_filters( 'sas_menu_maker_group_shortcode_allergens_legend_html', '', $allergens );

// Render helpers — small closures so the collapsed / expanded branches
// don't duplicate the item-list and legend markup.
$render_header = function () use ( $source, $header_html, $config ) {
	if ( '' === $header_html ) {
		return;
	}
	/**
	 * Action: right before the group hero header.
	 *
	 * @param array<string,mixed> $source
	 * @param array<string,mixed> $config
	 */
	do_action( 'sas_menu_maker_before_group_header', $source, $config );

	echo $header_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	/**
	 * Action: right after the group hero header.
	 *
	 * @param array<string,mixed> $source
	 * @param array<string,mixed> $config
	 */
	do_action( 'sas_menu_maker_after_group_header', $source, $config );
};

$render_items = function () use ( $items, $items_override, $allergens, $tags, $config ) {
	if ( is_string( $items_override ) && '' !== $items_override ) {
		echo $items_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}
	do_action( 'sas_menu_maker_before_items', $items );
	echo '<div class="sas-menu-maker-items" data-sas-menu-maker-items>';
	if ( empty( $items ) ) {
		echo '<p class="sas-menu-maker-empty">' . esc_html__( 'No menu items to display yet.', 'sas-menu-maker' ) . '</p>';
	} else {
		foreach ( $items as $item ) {
			echo SAS_Menu_Maker_Public::render_item( $item, $allergens, $tags, $config ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	echo '</div>';
	do_action( 'sas_menu_maker_after_items', $items );
};

$render_legend = function () use ( $allergens, $legend_override, $config ) {
	if ( empty( $config['show_allergens_legend'] ) || empty( $allergens ) ) {
		return;
	}
	if ( is_string( $legend_override ) && '' !== $legend_override ) {
		echo $legend_override; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}
	do_action( 'sas_menu_maker_before_allergens_legend', $allergens );
	echo '<div class="sas-menu-maker-allergens-legend" data-sas-menu-maker-allergens-legend>';
	echo '<span class="sas-menu-maker-allergens-legend-label">' . esc_html__( 'Allergens', 'sas-menu-maker' ) . ':</span>';
	echo '<span class="sas-menu-maker-allergens-legend-list">';
	foreach ( $allergens as $allergen ) {
		echo '<span class="sas-menu-maker-allergens-legend-item"><strong>' . esc_html( $allergen['code'] ) . '</strong> ' . esc_html( $allergen['name'] ) . '</span>';
	}
	echo '</span>';
	echo '</div>';
	do_action( 'sas_menu_maker_after_allergens_legend', $allergens );
};
?>
<div class="sas-menu-maker sas-menu-maker-group sas-menu-maker-image-<?php echo esc_attr( $image_pos ); ?><?php echo esc_attr( $grid_class ); ?><?php echo esc_attr( $custom_class ); ?><?php echo $collapsed ? ' sas-menu-maker-group--collapsible' : ''; ?>"
	id="<?php echo esc_attr( $instance_id ); ?>"
	data-sas-menu-maker-root>

	<?php if ( ! $source ) : ?>
		<p class="sas-menu-maker-empty"><?php esc_html_e( 'No matching category or tag found.', 'sas-menu-maker' ); ?></p>
	<?php elseif ( $collapsed ) : ?>
		<details class="sas-menu-maker-group-details">
			<summary class="sas-menu-maker-group-summary">
				<?php $render_header(); ?>
				<span class="sas-menu-maker-group-summary-icon" aria-hidden="true"></span>
			</summary>
			<div class="sas-menu-maker-group-content">
				<?php echo $expanded_media_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php $render_items(); ?>
				<?php $render_legend(); ?>
			</div>
		</details>
	<?php else : ?>
		<?php $render_header(); ?>
		<?php $render_items(); ?>
		<?php $render_legend(); ?>
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
