<?php
/**
 * SAS Menu Maker — default per-offer template used by [sas_menu_offers].
 *
 * Themes may override this file by copying it to:
 *   your-theme/sas-menu-maker/shortcode-offer.php
 *
 * Available variables (passed by SAS_Menu_Maker_Public::render_offer):
 *   $offer     array<string,mixed>            Hydrated offer.
 *   $items_map array<int,array<string,mixed>> id => item lookup.
 *   $config    array<string,mixed>            Normalised shortcode config.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This template is `include`d from SAS_Menu_Maker_Public::render_offer(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$image_pos       = isset( $config['image_pos'] ) ? $config['image_pos'] : 'left';
$show_items_mode = isset( $config['show_items'] ) ? $config['show_items'] : 'inline';
$show_desc_mode  = isset( $config['show_desc'] ) ? $config['show_desc'] : 'inline';
$show_dates      = ! empty( $config['show_dates'] );
$conditions_mode = isset( $config['conditions'] ) ? $config['conditions'] : 'modal';

$offer_id       = (int) $offer['id'];
$image_url      = ! empty( $offer['media_id'] ) ? wp_get_attachment_image_url( (int) $offer['media_id'], 'medium' ) : '';
$lines          = isset( $offer['items'] ) ? (array) $offer['items'] : array();
$has_lines      = ! empty( $lines );
$has_desc       = ! empty( $offer['description'] );
$has_conditions = ! empty( $offer['conditions_text'] );

$show_items_inline = $has_lines && 'inline' === $show_items_mode;
$show_items_modal  = $has_lines && 'modal'  === $show_items_mode;
$show_desc_inline  = $has_desc && 'inline' === $show_desc_mode;
$show_desc_modal   = $has_desc && 'modal'  === $show_desc_mode;
$show_cond_inline  = $has_conditions && 'inline' === $conditions_mode;
$show_cond_modal   = $has_conditions && 'modal'  === $conditions_mode;

// Only clickable when the modal will actually contain something.
$has_details = $show_desc_modal || $show_items_modal || $show_cond_modal;

$validity_line = $show_dates ? SAS_Menu_Maker_Public::format_offer_validity( $offer ) : '';

// Build inner blocks (body + media) as strings so we can flip order
// based on image_pos, same trick as the item template.
$body_html  = '';
$body_html .= '<div class="sas-menu-maker-item-body">';
$body_html .= '<header class="sas-menu-maker-item-head">';
$body_html .= '<h3 class="sas-menu-maker-item-title">' . esc_html( $offer['name'] ) . '</h3>';
$body_html .= '<span class="sas-menu-maker-item-price">' . esc_html( SAS_Menu_Maker_Public::format_price( $offer['price'] ) ) . '</span>';
$body_html .= '</header>';

if ( $show_desc_inline ) {
	$body_html .= '<p class="sas-menu-maker-item-desc">' . esc_html( $offer['description'] ) . '</p>';
}

if ( $show_items_inline ) {
	$body_html .= '<ul class="sas-menu-maker-offer-lines">';
	foreach ( $lines as $line ) {
		$label = SAS_Menu_Maker_Public::offer_line_label( $line, $items_map );
		if ( '' === $label ) {
			continue;
		}
		$body_html .= '<li class="sas-menu-maker-offer-line">' . $label . '</li>';
	}
	$body_html .= '</ul>';
}

if ( '' !== $validity_line ) {
	$body_html .= '<div class="sas-menu-maker-offer-validity">' . esc_html( $validity_line ) . '</div>';
}

if ( $show_cond_inline ) {
	$body_html .= '<p class="sas-menu-maker-offer-conditions">' . esc_html( $offer['conditions_text'] ) . '</p>';
}

$body_html .= '</div>';

$media_html = '';
if ( $image_url ) {
	$media_html = '<div class="sas-menu-maker-item-media">'
		. '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $offer['name'] ) . '" loading="lazy">'
		. '</div>';
}

// Build the modal payload only when there IS content for the modal.
$modal_html = '';
if ( $show_desc_modal ) {
	$modal_html .= '<p class="sas-menu-maker-modal-offer-desc">' . esc_html( $offer['description'] ) . '</p>';
}
if ( $show_items_modal ) {
	$modal_html .= '<ul class="sas-menu-maker-modal-offer-lines">';
	foreach ( $lines as $line ) {
		$label = SAS_Menu_Maker_Public::offer_line_label( $line, $items_map );
		if ( '' === $label ) {
			continue;
		}
		$modal_html .= '<li>' . $label . '</li>';
	}
	$modal_html .= '</ul>';
}
if ( $show_cond_modal ) {
	$modal_html .= '<p class="sas-menu-maker-modal-offer-conditions">'
		. esc_html( $offer['conditions_text'] )
		. '</p>';
}
?>
<article class="sas-menu-maker-item sas-menu-maker-offer sas-menu-maker-item--image-<?php echo esc_attr( $image_pos ); ?><?php echo $has_details ? ' sas-menu-maker-offer-has-details' : ''; ?>"
	data-sas-menu-maker-offer="<?php echo esc_attr( (string) $offer_id ); ?>"
	<?php if ( $has_details ) : ?>
		tabindex="0"
		role="button"
		data-sas-menu-maker-open-details="offer-<?php echo esc_attr( (string) $offer_id ); ?>"
		aria-label="<?php echo esc_attr( sprintf( /* translators: %s: offer name */ __( 'Show details for %s', 'sas-menu-maker' ), $offer['name'] ) ); ?>"
	<?php endif; ?>>

	<?php
	if ( 'right' === $image_pos ) {
		echo $body_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $media_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		echo $media_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $body_html;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<?php if ( $has_details ) : ?>
		<script type="application/json" data-sas-menu-maker-details="offer-<?php echo esc_attr( (string) $offer_id ); ?>">
			<?php echo wp_json_encode( array( 'title' => $offer['name'], 'html' => $modal_html ) ); ?>
		</script>
	<?php endif; ?>

</article>
