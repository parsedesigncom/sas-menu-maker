<?php
/**
 * Gutenberg block for one category or tag.
 *
 * `sas-menu-maker/group` is a dynamic block whose render_callback wraps the
 * [sas_menu_group] shortcode. Attributes are split into
 *   - source-facing (source, sourceId + the same layout knobs as
 *     [sas_menu_group]) that translate into shortcode args
 *   - block-only decoration (font scale, item alignment, border radius,
 *     ~14 color slots) applied via classes and a scoped inline <style>
 * The shortcode itself never sees the decoration attrs.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Group block controller.
 */
class SAS_Menu_Maker_Group_Block {

	/**
	 * Color-slot registry — mirrors blocks/group/index.js. Fewer slots
	 * than the menu block (no filter chips) plus header-specific slots.
	 *
	 * @return array<int,array{0:string,1:string,2:string}>
	 */
	private static function color_slots() {
		return array(
			// Container.
			array( 'bgColor',             '',                                       'background-color' ),
			array( 'textColor',           '',                                       'color' ),
			// Header (group image + title + description).
			array( 'headerBg',            ' .sas-menu-maker-group-header',               'background-color' ),
			array( 'headerTitleColor',    ' .sas-menu-maker-group-title',                'color' ),
			array( 'headerDescColor',     ' .sas-menu-maker-group-desc',                 'color' ),
			// Items.
			array( 'itemBg',              ' .sas-menu-maker-item',                       'background-color' ),
			array( 'itemBorder',          ' .sas-menu-maker-item',                       'border-color' ),
			array( 'itemTitleColor',      ' .sas-menu-maker-item-title',                 'color' ),
			array( 'itemDescColor',       ' .sas-menu-maker-item-desc',                  'color' ),
			array( 'itemPriceColor',      ' .sas-menu-maker-item-price',                 'color' ),
			array( 'allergenSupColor',    ' .sas-menu-maker-item-allergens',             'color' ),
			array( 'variantDividerColor', ' .sas-menu-maker-item-variant',               'border-bottom-color' ),
			// Tags.
			array( 'tagBorder',           ' .sas-menu-maker-item-tag',                   'border-color' ),
			array( 'tagText',             ' .sas-menu-maker-item-tag',                   'color' ),
			// Legend.
			array( 'legendBg',            ' .sas-menu-maker-allergens-legend',           'background-color' ),
			array( 'legendText',          ' .sas-menu-maker-allergens-legend',           'color' ),
		);
	}

	/**
	 * Selectors receiving the global border-radius when set. Includes
	 * the group header media so it matches the cards.
	 *
	 * @return string[]
	 */
	private static function border_radius_selectors() {
		return array(
			'',
			' .sas-menu-maker-group-header-media img',
			' .sas-menu-maker-group-expanded-media img',
			' .sas-menu-maker-item',
			' .sas-menu-maker-item-media img',
			' .sas-menu-maker-modal-dialog',
		);
	}

	/**
	 * Register the block type. Category is already registered by
	 * SAS_Menu_Maker_Block.
	 */
	public static function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		register_block_type(
			SAS_MENU_MAKER_PLUGIN_DIR . 'blocks/group',
			array(
				'render_callback' => array( __CLASS__, 'render_block' ),
			)
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations(
				'sas-menu-maker-group-editor-script',
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
		$source_id = isset( $attributes['sourceId'] ) ? (int) $attributes['sourceId'] : 0;
		if ( $source_id <= 0 ) {
			return '<p class="sas-menu-maker-empty">'
				. esc_html__( 'Pick a category or tag in the block sidebar.', 'sas-menu-maker' )
				. '</p>';
		}

		$sc = self::build_shortcode( $attributes );

		$scale      = self::whitelist( isset( $attributes['fontScale'] ) ? $attributes['fontScale'] : 'medium', array( 'small', 'medium', 'large' ), 'medium' );
		$item_align = self::whitelist( isset( $attributes['itemAlign'] ) ? $attributes['itemAlign'] : 'left', array( 'left', 'center', 'right' ), 'left' );

		$scale_short = ( 'small' === $scale ) ? 's' : ( ( 'large' === $scale ) ? 'l' : 'm' );
		$block_id    = wp_unique_id( 'sas-menu-maker-group-block-' );

		$classes = array(
			'wp-block-sas-menu-maker-group',
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
	 * Assemble the `[sas_menu_group …]` shortcode string.
	 *
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	private static function build_shortcode( array $attributes ) {
		$source    = isset( $attributes['source'] ) ? (string) $attributes['source'] : 'category';
		$source_id = isset( $attributes['sourceId'] ) ? (int) $attributes['sourceId'] : 0;

		$parts = array();
		if ( 'tag' === $source ) {
			$parts[] = 'tag="' . esc_attr( (string) $source_id ) . '"';
		} else {
			$parts[] = 'category="' . esc_attr( (string) $source_id ) . '"';
		}

		$map = array(
			'image'           => 'image',
			'variants'        => 'variants',
			'columns'         => 'columns',
			'allergensLegend' => 'allergens_legend',
			'showHeader'      => 'show_header',
			'collapsed'       => 'collapsed',
		);

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

		return '[sas_menu_group ' . implode( ' ', $parts ) . ']';
	}

	/**
	 * Build the scoped CSS rules (no wrapping <style> tag) for color slots
	 * + border-radius. Returned string is meant to be attached via
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
	 */
	private static function whitelist( $value, array $allowed, $fallback ) {
		$value = is_string( $value ) ? strtolower( $value ) : '';
		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	/**
	 * Sanitize CSS color value for safe injection into inline <style>.
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
