<?php
/**
 * SAS Menu Maker — default per-item template used by the [sas_menu] shortcode.
 *
 * Themes may override this file by copying it to:
 *   your-theme/sas-menu-maker/shortcode-item.php
 *
 * Available variables (passed by SAS_Menu_Maker_Public::render_item):
 *   $item      array<string,mixed>            Hydrated item.
 *   $allergens array<int,array<string,mixed>> Allergen map keyed by id.
 *   $tags      array<int,array<string,mixed>> Tag map keyed by id (for pill labels).
 *   $config    array<string,mixed>            Normalised shortcode config
 *                                             (image_pos, variants_mode).
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This template is `include`d from SAS_Menu_Maker_Public::render_item(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$image_pos     = isset( $config['image_pos'] ) ? $config['image_pos'] : 'left';
$variants_mode = isset( $config['variants_mode'] ) ? $config['variants_mode'] : 'inline';

$item_id       = (int) $item['id'];
$active_variants = array();
if ( ! empty( $item['variants'] ) ) {
	foreach ( $item['variants'] as $v ) {
		if ( ! empty( $v['is_active'] ) ) {
			$active_variants[] = $v;
		}
	}
}
$has_variants  = ! empty( $active_variants );
$show_variants_inline = $has_variants && 'inline' === $variants_mode;
$show_variants_modal  = $has_variants && 'modal' === $variants_mode;

$image_url     = ! empty( $item['media_id'] ) ? wp_get_attachment_image_url( (int) $item['media_id'], 'medium' ) : '';
$categories    = isset( $item['category_ids'] ) ? array_map( 'intval', (array) $item['category_ids'] ) : array();
$item_tag_ids  = isset( $item['tag_ids'] ) ? array_map( 'intval', (array) $item['tag_ids'] ) : array();
$item_all_ids  = isset( $item['allergen_ids'] ) ? array_map( 'intval', (array) $item['allergen_ids'] ) : array();
$has_long_desc = ! empty( $item['description_long'] );
$has_details   = $has_long_desc || $show_variants_modal;

// Compute an "effective" price to show in the card header when variants
// are hidden or absent: prefer base price, otherwise the smallest variant
// (with "from" prefix).
$price_display = '';
$price_hint    = '';
if ( null !== $item['price'] ) {
	$price_display = SAS_Menu_Maker_Public::format_price( $item['price'] );
} elseif ( $has_variants ) {
	$min_price = null;
	foreach ( $active_variants as $v ) {
		$p = (float) $v['price'];
		if ( null === $min_price || $p < $min_price ) {
			$min_price = $p;
		}
	}
	if ( null !== $min_price ) {
		$price_display = SAS_Menu_Maker_Public::format_price( $min_price );
		/* translators: prefix for "from X€" price when only the cheapest variant is shown */
		$price_hint = __( 'from', 'sas-menu-maker' );
	}
}
$show_price_header = '' !== $price_display && ! $show_variants_inline;

// Build the modal body HTML once, server-side, so JS only injects
// pre-escaped content. wp_kses_post protects the long description if
// someone pasted markup into it.
$modal_html = '';
if ( $has_long_desc ) {
	$modal_html .= '<div class="sas-menu-maker-modal-desc">' . wpautop( wp_kses_post( $item['description_long'] ) ) . '</div>';
}
if ( $show_variants_modal ) {
	$modal_html .= '<ul class="sas-menu-maker-modal-variants">';
	foreach ( $active_variants as $v ) {
		$modal_html .= '<li>'
			. '<span class="sas-menu-maker-modal-variant-label">' . esc_html( $v['label'] ) . '</span>'
			. '<span class="sas-menu-maker-modal-variant-price">' . esc_html( SAS_Menu_Maker_Public::format_price( $v['price'] ) ) . '</span>'
			. '</li>';
	}
	$modal_html .= '</ul>';
}

// Allergen codes rendered inside the title as a small italic
// superscript suffix (see .sas-menu-maker-item-allergens CSS). Skipped
// entirely when the end-of-menu legend is hidden — without the legend
// the code letters would be meaningless to visitors.
$allergen_prefix = '';
$show_allergens  = ! isset( $config['show_allergens_legend'] ) || ! empty( $config['show_allergens_legend'] );
if ( $show_allergens && ! empty( $item_all_ids ) ) {
	$codes = array();
	foreach ( $item_all_ids as $aid ) {
		if ( isset( $allergens[ $aid ] ) ) {
			$codes[] = $allergens[ $aid ]['code'];
		}
	}
	if ( ! empty( $codes ) ) {
		$allergen_prefix = '<span class="sas-menu-maker-item-allergens" aria-label="'
			. esc_attr__( 'Allergens', 'sas-menu-maker' ) . '">'
			. esc_html( implode( ', ', $codes ) )
			. '</span>';
	}
}

$body_html  = '';
$body_html .= '<div class="sas-menu-maker-item-body">';
$body_html .= '<header class="sas-menu-maker-item-head">';
$body_html .= '<h3 class="sas-menu-maker-item-title">' . esc_html( $item['name'] ) . $allergen_prefix . '</h3>';
if ( $show_price_header ) {
	$body_html .= '<span class="sas-menu-maker-item-price">';
	if ( '' !== $price_hint ) {
		$body_html .= '<span class="sas-menu-maker-item-price-hint">' . esc_html( $price_hint ) . '</span> ';
	}
	$body_html .= esc_html( $price_display );
	$body_html .= '</span>';
}
$body_html .= '</header>';

if ( ! empty( $item['description_short'] ) ) {
	$body_html .= '<p class="sas-menu-maker-item-desc">' . esc_html( $item['description_short'] ) . '</p>';
}

if ( $show_variants_inline ) {
	$body_html .= '<ul class="sas-menu-maker-item-variants">';
	foreach ( $active_variants as $v ) {
		$body_html .= '<li class="sas-menu-maker-item-variant">'
			. '<span class="sas-menu-maker-item-variant-label">' . esc_html( $v['label'] ) . '</span>'
			. '<span class="sas-menu-maker-item-variant-price">' . esc_html( SAS_Menu_Maker_Public::format_price( $v['price'] ) ) . '</span>'
			. '</li>';
	}
	$body_html .= '</ul>';
}

if ( ! empty( $item_tag_ids ) ) {
	$rendered_tags = '';
	foreach ( $item_tag_ids as $tid ) {
		if ( ! isset( $tags[ $tid ] ) ) {
			continue;
		}
		$style          = ! empty( $tags[ $tid ]['color'] ) ? ' style="border-color:' . esc_attr( $tags[ $tid ]['color'] ) . '"' : '';
		$rendered_tags .= '<span class="sas-menu-maker-item-tag"' . $style . '>' . esc_html( $tags[ $tid ]['name'] ) . '</span>';
	}
	if ( '' !== $rendered_tags ) {
		$body_html .= '<footer class="sas-menu-maker-item-meta">';
		$body_html .= '<span class="sas-menu-maker-item-tags">' . $rendered_tags . '</span>';
		$body_html .= '</footer>';
	}
}
$body_html .= '</div>';

$media_html = '';
if ( $image_url ) {
	$media_html = '<div class="sas-menu-maker-item-media">'
		. '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $item['name'] ) . '" loading="lazy">'
		. '</div>';
}
?>
<article class="sas-menu-maker-item sas-menu-maker-item--image-<?php echo esc_attr( $image_pos ); ?><?php echo $has_details ? ' sas-menu-maker-item-has-details' : ''; ?>"
	data-sas-menu-maker-item="<?php echo esc_attr( (string) $item_id ); ?>"
	data-sas-menu-maker-categories="<?php echo esc_attr( implode( ',', $categories ) ); ?>"
	data-sas-menu-maker-tags="<?php echo esc_attr( implode( ',', $item_tag_ids ) ); ?>"
	<?php if ( $has_details ) : ?>
		tabindex="0"
		role="button"
		data-sas-menu-maker-open-details="item-<?php echo esc_attr( (string) $item_id ); ?>"
		aria-label="<?php echo esc_attr( sprintf( /* translators: %s: item name */ __( 'Show details for %s', 'sas-menu-maker' ), $item['name'] ) ); ?>"
	<?php endif; ?>>

	<?php
	// HTML order swaps for image=right; top and left both put media first.
	if ( 'right' === $image_pos ) {
		echo $body_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $media_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		echo $media_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $body_html;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<?php if ( $has_details ) : ?>
		<script type="application/json" data-sas-menu-maker-details="item-<?php echo esc_attr( (string) $item_id ); ?>">
			<?php echo wp_json_encode( array( 'title' => $item['name'], 'html' => $modal_html ) ); ?>
		</script>
	<?php endif; ?>

</article>
