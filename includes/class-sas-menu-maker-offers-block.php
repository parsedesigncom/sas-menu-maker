<?php
/**
 * Gutenberg block for offers.
 *
 * `sas-menu-maker/offers` is a dynamic block whose render_callback wraps the
 * [sas_menu_offers] shortcode. Attributes are split into two groups:
 * "shortcode-facing" ones (image, columns, validity, show_items, show_desc,
 * show_dates, conditions) are re-emitted into the shortcode string, while
 * block-only decoration (font scale, item alignment, border radius, ~10
 * color slots) is applied via classes and a scoped inline <style> on the
 * outer wrapper. The shortcode itself never sees the decoration attrs.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Offers block controller.
 */
class SAS_Menu_Maker_Offers_Block {

	/**
	 * Color-slot registry. Fewer slots than the menu block because offers
	 * have no filter chips, tag pills or allergen legend. Groups are
	 * mirrored in blocks/offers/index.js.
	 *
	 * @return array<int,array{0:string,1:string,2:string}>
	 */
	private static function color_slots() {
		return array(
			// Container.
			array( 'bgColor',           '',                             'background-color' ),
			array( 'textColor',         '',                             'color' ),
			// Cards (offers reuse .sas-menu-maker-item classes for the card).
			array( 'cardBg',            ' .sas-menu-maker-item',             'background-color' ),
			array( 'cardBorder',        ' .sas-menu-maker-item',             'border-color' ),
			array( 'cardTitleColor',    ' .sas-menu-maker-item-title',       'color' ),
			array( 'cardDescColor',     ' .sas-menu-maker-item-desc',        'color' ),
			array( 'cardPriceColor',    ' .sas-menu-maker-item-price',       'color' ),
			// Offer-specific bits.
			array( 'linesColor',        ' .sas-menu-maker-offer-line',       'color' ),
			array( 'validityColor',     ' .sas-menu-maker-offer-validity',   'color' ),
			array( 'conditionsColor',   ' .sas-menu-maker-offer-conditions', 'color' ),
		);
	}

	/**
	 * Selectors that receive the global border-radius when the block's
	 * `borderRadius` attribute is set.
	 *
	 * @return string[]
	 */
	private static function border_radius_selectors() {
		return array(
			'',                              // outer wrapper
			' .sas-menu-maker-item',              // offer card
			' .sas-menu-maker-item-media img',    // card image
			' .sas-menu-maker-modal-dialog',      // modal
		);
	}

	/**
	 * Register the block type. The block category itself is already
	 * registered by SAS_Menu_Maker_Block so we don't double-add it here.
	 */
	public static function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		register_block_type(
			SAS_MENU_MAKER_PLUGIN_DIR . 'blocks/offers',
			array(
				'render_callback' => array( __CLASS__, 'render_block' ),
			)
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations(
				'sas-menu-maker-offers-editor-script',
				'sas-menu-maker',
				SAS_MENU_MAKER_PLUGIN_DIR . 'languages'
			);
		}
	}

	/**
	 * Server-side render callback.
	 *
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		$sc = self::build_shortcode( $attributes );

		$scale      = self::whitelist( isset( $attributes['fontScale'] ) ? $attributes['fontScale'] : 'medium', array( 'small', 'medium', 'large' ), 'medium' );
		$item_align = self::whitelist( isset( $attributes['itemAlign'] ) ? $attributes['itemAlign'] : 'left', array( 'left', 'center', 'right' ), 'left' );

		$scale_short = ( 'small' === $scale ) ? 's' : ( ( 'large' === $scale ) ? 'l' : 'm' );
		$block_id    = wp_unique_id( 'sas-menu-maker-offers-block-' );

		$classes = array(
			'wp-block-sas-menu-maker-offers',
			'sas-menu-maker-block',
			'sas-menu-maker-scale-' . $scale_short,
			'sas-menu-maker-content-align-' . $item_align,
		);

		if ( ! empty( $attributes['className'] ) ) {
			$classes[] = (string) $attributes['className'];
		}
		if ( ! empty( $attributes['align'] ) && in_array( (string) $attributes['align'], array( 'wide', 'full' ), true ) ) {
			$classes[] = 'align' . $attributes['align'];
		}

		wp_enqueue_style( 'sas-menu-maker-public' );
		$css = self::build_style_css( $block_id, $attributes );
		if ( '' !== $css ) {
			wp_add_inline_style( 'sas-menu-maker-public', $css );
		}

		// Editor preview via ServerSideRender REST endpoint does not receive
		// wp_add_inline_style attachments — emit a scoped <style> only in
		// that REST context. Frontend page renders never take this branch.
		$preview_style = '';
		if ( '' !== $css && defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			$preview_style = '<style>' . $css . '</style>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Editor-preview only.
		}

		return sprintf(
			'<div id="%1$s" class="%2$s">%3$s%4$s</div>',
			esc_attr( $block_id ),
			esc_attr( implode( ' ', $classes ) ),
			$preview_style,
			do_shortcode( $sc )
		);
	}

	/**
	 * Assemble the `[sas_menu_offers …]` shortcode string from the
	 * shortcode-facing attributes only. Decoration attrs stay on the
	 * PHP side.
	 *
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	private static function build_shortcode( array $attributes ) {
		$map = array(
			'image'      => 'image',
			'columns'    => 'columns',
			'validity'   => 'validity',
			'showItems'  => 'show_items',
			'showDesc'   => 'show_desc',
			'showDates'  => 'show_dates',
			'conditions' => 'conditions',
		);

		$parts = array();
		foreach ( $map as $block_key => $shortcode_key ) {
			if ( ! isset( $attributes[ $block_key ] ) ) {
				continue;
			}
			$value = (string) $attributes[ $block_key ];
			if ( '' === $value ) {
				continue;
			}
			$parts[] = $shortcode_key . '="' . esc_attr( $value ) . '"';
		}

		if ( ! empty( $attributes['className'] ) ) {
			$parts[] = 'class="' . esc_attr( (string) $attributes['className'] ) . '"';
		}

		return '[sas_menu_offers' . ( empty( $parts ) ? '' : ' ' . implode( ' ', $parts ) ) . ']';
	}

	/**
	 * Build the scoped CSS rules (no wrapping <style> tag) for color slots
	 * + global border-radius. Returned string is meant to be attached via
	 * wp_add_inline_style().
	 *
	 * @param string              $block_id   Outer wrapper id.
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	private static function build_style_css( $block_id, array $attributes ) {
		$rules = array();

		foreach ( self::color_slots() as $slot ) {
			list( $attr, $selector, $property ) = $slot;
			if ( ! isset( $attributes[ $attr ] ) ) {
				continue;
			}
			$value = self::sanitize_css_value( (string) $attributes[ $attr ] );
			if ( '' === $value ) {
				continue;
			}
			$rules[] = '#' . $block_id . $selector . '{' . $property . ':' . $value . ';}';
		}

		if ( isset( $attributes['borderRadius'] ) && '' !== (string) $attributes['borderRadius'] ) {
			$radius = max( 0, (int) $attributes['borderRadius'] );
			foreach ( self::border_radius_selectors() as $sel ) {
				$rules[] = '#' . $block_id . $sel . '{border-radius:' . $radius . 'px;}';
			}
		}

		return implode( '', $rules );
	}

	/**
	 * Whitelist a value against a set of allowed strings.
	 *
	 * @param string   $value    Candidate.
	 * @param string[] $allowed  Allowed values.
	 * @param string   $fallback Value returned when candidate is not allowed.
	 * @return string
	 */
	private static function whitelist( $value, array $allowed, $fallback ) {
		$value = is_string( $value ) ? strtolower( $value ) : '';
		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	/**
	 * Sanitize a CSS color value for safe injection into inline <style>.
	 * Same whitelist as SAS_Menu_Maker_Block: strips anything that could escape
	 * the property context (;, {, <, > etc.).
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	private static function sanitize_css_value( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		$safe = preg_replace( '/[^a-zA-Z0-9#()\-_.,%\s]/', '', $value );
		return is_string( $safe ) ? $safe : '';
	}
}
