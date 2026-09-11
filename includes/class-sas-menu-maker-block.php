<?php
/**
 * Gutenberg block registration.
 *
 * `sas-menu-maker/menu` is a dynamic block: attributes are saved as JSON in
 * post_content and the HTML is produced at render time by wrapping the
 * `[sas_menu]` shortcode with an outer div that carries per-instance
 * colors, alignment and font-scale — everything block-only. The
 * shortcode itself never sees these attributes.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block controller.
 */
class SAS_Menu_Maker_Block {

	/**
	 * Color-slot registry.
	 *
	 * Each entry: [ block-attribute name, CSS selector (relative to the
	 * outer wrapper; empty = the wrapper itself), CSS property ].
	 *
	 * When adding a slot, mirror the entry in blocks/menu/index.js so the
	 * sidebar exposes it.
	 *
	 * @return array<int,array{0:string,1:string,2:string}>
	 */
	private static function color_slots() {
		return array(
			// Container.
			array( 'bgColor',             '',                                       'background-color' ),
			array( 'textColor',           '',                                       'color' ),
			// Filter.
			array( 'filterBarBg',         ' .sas-menu-maker-filter-bar',                 'background-color' ),
			array( 'filterBarBorder',     ' .sas-menu-maker-filter-bar',                 'border-color' ),
			array( 'filterLabelColor',    ' .sas-menu-maker-filter-label',               'color' ),
			array( 'chipBg',              ' .sas-menu-maker-filter-chip',                'background-color' ),
			array( 'chipText',            ' .sas-menu-maker-filter-chip',                'color' ),
			array( 'chipBorder',          ' .sas-menu-maker-filter-chip',                'border-color' ),
			array( 'chipActiveBg',        ' .sas-menu-maker-filter-chip.is-active',      'background-color' ),
			array( 'chipActiveText',      ' .sas-menu-maker-filter-chip.is-active',      'color' ),
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
	 * Register the block type from its block.json + inject our custom
	 * block category into the inserter.
	 */
	public static function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return; // WordPress < 5.0.
		}

		register_block_type(
			SAS_MENU_MAKER_PLUGIN_DIR . 'blocks/menu',
			array(
				'render_callback' => array( __CLASS__, 'render_block' ),
			)
		);

		// Wire up JSON translations for the editor script so the sidebar
		// panels (Layout, Colors, etc.) speak the site's language.
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations(
				'sas-menu-maker-menu-editor-script',
				'sas-menu-maker',
				SAS_MENU_MAKER_PLUGIN_DIR . 'languages'
			);
		}

		add_filter( 'block_categories_all', array( __CLASS__, 'inject_category' ), 10, 1 );
	}

	/**
	 * Add a dedicated "SAS Menu Maker" category to the block inserter so our
	 * blocks live in one predictable place instead of scattered across
	 * Widgets/Common.
	 *
	 * @param array $categories Existing block categories.
	 * @return array
	 */
	public static function inject_category( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'sas-menu-maker',
					'title' => __( 'SAS Menu Maker', 'sas-menu-maker' ),
					'icon'  => 'coffee',
				),
			),
			$categories
		);
	}

	/**
	 * Server-side render callback declared in block.json.
	 *
	 * Builds a `[sas_menu …]` shortcode from the layout/title/columns
	 * attributes, wraps its output in an outer div that carries block-only
	 * decoration (colors, alignment, font-scale) and prepends a scoped
	 * <style> block with the color overrides. The scoping selector is
	 * the outer wrapper's unique id, generated per instance.
	 *
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		$sc = self::build_shortcode( $attributes );

		$scale        = self::whitelist( isset( $attributes['fontScale'] ) ? $attributes['fontScale'] : 'medium', array( 'small', 'medium', 'large' ), 'medium' );
		$filter_align = self::whitelist( isset( $attributes['filterAlign'] ) ? $attributes['filterAlign'] : 'left', array( 'left', 'center', 'right' ), 'left' );
		$item_align   = self::whitelist( isset( $attributes['itemAlign'] ) ? $attributes['itemAlign'] : 'left', array( 'left', 'center', 'right' ), 'left' );

		$scale_short = ( 'small' === $scale ) ? 's' : ( ( 'large' === $scale ) ? 'l' : 'm' );

		$block_id = wp_unique_id( 'sas-menu-maker-block-' );

		// Build the wrapper class list explicitly. Earlier we routed this
		// through get_block_wrapper_attributes(), but its behaviour when
		// passing a custom `class` extra differs across WP versions and
		// sometimes dropped our alignment/scale classes silently. Doing
		// it by hand is predictable and still WP-standard: the base
		// `wp-block-sas-menu-maker-menu` class + the user's `className` are
		// applied, plus `align{wide,full}` when the toolbar toggle is set.
		$classes = array(
			'wp-block-sas-menu-maker-menu',
			'sas-menu-maker-block',
			'sas-menu-maker-scale-' . $scale_short,
			'sas-menu-maker-filter-align-' . $filter_align,
			'sas-menu-maker-content-align-' . $item_align,
		);

		if ( ! empty( $attributes['className'] ) ) {
			$classes[] = (string) $attributes['className'];
		}
		if ( ! empty( $attributes['align'] ) && in_array( (string) $attributes['align'], array( 'wide', 'full' ), true ) ) {
			$classes[] = 'align' . $attributes['align'];
		}

		// Per-instance color/border-radius rules are attached to the public
		// stylesheet handle instead of being echoed as a raw <style> tag,
		// per WP.org's "enqueue all CSS" guideline. Enqueue first so the
		// handle exists before add_inline_style targets it.
		wp_enqueue_style( 'sas-menu-maker-public' );
		$css = self::build_style_css( $block_id, $attributes );
		if ( '' !== $css ) {
			wp_add_inline_style( 'sas-menu-maker-public', $css );
		}

		// In the Gutenberg editor the block preview is fetched via a REST
		// endpoint (ServerSideRender); wp_add_inline_style attaches CSS to
		// the handle in the REST process but that inline CSS does not travel
		// with the response to the editor iframe. Emit a scoped <style> in
		// the returned HTML only in that REST context so the editor preview
		// mirrors the frontend. Regular page renders never see this branch.
		$preview_style = '';
		if ( '' !== $css && defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			$preview_style = '<style>' . $css . '</style>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Editor-preview only; frontend uses wp_add_inline_style above.
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
	 * Assemble the `[sas_menu …]` shortcode string from the block's
	 * shortcode-facing attributes. Block-only attributes (colors,
	 * alignment, scale) are handled outside via CSS and never leak into
	 * the shortcode call.
	 *
	 * @param array<string,mixed> $attributes Block attributes.
	 * @return string
	 */
	private static function build_shortcode( array $attributes ) {
		$map = array(
			'image'           => 'image',
			'variants'        => 'variants',
			'categoriesTitle' => 'categories_title',
			'tagsTitle'       => 'tags_title',
			'allergensTitle'  => 'allergens_title',
			'columns'         => 'columns',
			'allergensLegend' => 'allergens_legend',
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

		return '[sas_menu' . ( empty( $parts ) ? '' : ' ' . implode( ' ', $parts ) ) . ']';
	}

	/**
	 * Selectors that receive a globally-applied border-radius when the
	 * `borderRadius` attribute is set. One value, many targets — covers
	 * container / filter bar / chips / item cards / item images / tag
	 * pills / modal dialog so the whole block adopts the same corner
	 * roundness in one setting.
	 *
	 * @return string[]
	 */
	private static function border_radius_selectors() {
		return array(
			'',                              // outer wrapper itself
			' .sas-menu-maker-filter-bar',
			' .sas-menu-maker-filter-chip',
			' .sas-menu-maker-item',
			' .sas-menu-maker-item-media img',
			' .sas-menu-maker-item-tag',
			' .sas-menu-maker-modal-dialog',
		);
	}

	/**
	 * Build the CSS rules (no wrapping <style> tag) scoped to the outer
	 * wrapper id, applying whatever color slots the author actually filled
	 * in plus a global border-radius when that attribute is set. Returned
	 * value is meant to be attached via wp_add_inline_style().
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
	 * Sanitize a color value coming from the block editor's color picker
	 * for safe injection into an inline <style>. Accepts hex, rgb(),
	 * hsl(), var(--…) and CSS named colors; strips everything that could
	 * escape the CSS property context.
	 *
	 * @param string $value Raw value.
	 * @return string Safe value, or empty string when input is not usable.
	 */
	private static function sanitize_css_value( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		// Strip any character that shouldn't appear in a CSS color value —
		// notably ; { } < > " ' which could break out of the property.
		$safe = preg_replace( '/[^a-zA-Z0-9#()\-_.,%\s]/', '', $value );
		return is_string( $safe ) ? $safe : '';
	}
}
