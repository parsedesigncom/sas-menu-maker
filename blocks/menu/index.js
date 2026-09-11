/**
 * SAS Menu Maker — Gutenberg "menu" block editor script.
 *
 * Dynamic block: the frontend HTML is produced server-side (by wrapping
 * the [sas_menu] shortcode); this script only wires up the block-inserter
 * entry, the inspector controls in the sidebar, and a ServerSideRender
 * preview so authors see the real menu inside the editor.
 *
 * Written in plain JS (no JSX / no build step) so the plugin ships
 * uncompiled — WP.org compliant out of the box.
 */
( function ( wp ) {
	'use strict';

	var el          = wp.element.createElement;
	var Fragment    = wp.element.Fragment;
	var __          = wp.i18n.__;
	var registerBlockType = wp.blocks.registerBlockType;

	var InspectorControls   = wp.blockEditor.InspectorControls;
	var PanelColorSettings  = wp.blockEditor.PanelColorSettings;
	var useBlockProps       = wp.blockEditor.useBlockProps;

	var PanelBody     = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl   = wp.components.TextControl;
	var ToggleControl = wp.components.ToggleControl;
	var RangeControl  = wp.components.RangeControl;

	// ServerSideRender lives at different locations across WP versions;
	// fall back through the known ones.
	var ServerSideRender = wp.serverSideRender
		|| ( wp.editor && wp.editor.ServerSideRender )
		|| ( wp.components && wp.components.ServerSideRender );

	// -------- Color-slot registry --------
	// Grouped for the sidebar UI. Each `attr` name must exist in
	// block.json and be mirrored in class-sas-menu-maker-block.php::color_slots().
	var colorGroups = [
		{
			title: __( 'Colors — Container', 'sas-menu-maker' ),
			colors: [
				{ attr: 'bgColor',   label: __( 'Background', 'sas-menu-maker' ) },
				{ attr: 'textColor', label: __( 'Text (fallback)', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Filter', 'sas-menu-maker' ),
			colors: [
				{ attr: 'filterBarBg',      label: __( 'Filter bar background', 'sas-menu-maker' ) },
				{ attr: 'filterBarBorder',  label: __( 'Filter bar border', 'sas-menu-maker' ) },
				{ attr: 'filterLabelColor', label: __( 'Filter labels', 'sas-menu-maker' ) },
				{ attr: 'chipBg',           label: __( 'Chip background', 'sas-menu-maker' ) },
				{ attr: 'chipText',         label: __( 'Chip text', 'sas-menu-maker' ) },
				{ attr: 'chipBorder',       label: __( 'Chip border', 'sas-menu-maker' ) },
				{ attr: 'chipActiveBg',     label: __( 'Chip active background', 'sas-menu-maker' ) },
				{ attr: 'chipActiveText',   label: __( 'Chip active text', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Items', 'sas-menu-maker' ),
			colors: [
				{ attr: 'itemBg',              label: __( 'Card background', 'sas-menu-maker' ) },
				{ attr: 'itemBorder',          label: __( 'Card border', 'sas-menu-maker' ) },
				{ attr: 'itemTitleColor',      label: __( 'Title', 'sas-menu-maker' ) },
				{ attr: 'itemDescColor',       label: __( 'Description', 'sas-menu-maker' ) },
				{ attr: 'itemPriceColor',      label: __( 'Price', 'sas-menu-maker' ) },
				{ attr: 'allergenSupColor',    label: __( 'Allergen superscript', 'sas-menu-maker' ) },
				{ attr: 'variantDividerColor', label: __( 'Variant divider', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Tags', 'sas-menu-maker' ),
			colors: [
				{ attr: 'tagBorder', label: __( 'Tag pill border', 'sas-menu-maker' ) },
				{ attr: 'tagText',   label: __( 'Tag pill text', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Allergen legend', 'sas-menu-maker' ),
			colors: [
				{ attr: 'legendBg',   label: __( 'Legend background', 'sas-menu-maker' ) },
				{ attr: 'legendText', label: __( 'Legend text', 'sas-menu-maker' ) },
			],
		},
	];

	function colorSettingsFor( group, attrs, set ) {
		return group.colors.map( function ( slot ) {
			return {
				label:       slot.label,
				value:       attrs[ slot.attr ] || '',
				// Show the alpha slider in the color picker so authors
				// can dial in transparency (e.g. rgba(0,0,0,0.5) or a
				// #RRGGBBAA hex). Values pass through the PHP sanitizer
				// which already accepts hex-with-alpha, rgba() and hsla().
				enableAlpha: true,
				onChange: function ( v ) {
					var upd = {};
					upd[ slot.attr ] = v || '';
					set( upd );
				},
			};
		} );
	}

	registerBlockType( 'sas-menu-maker/menu', {
		edit: function ( props ) {
			var attrs = props.attributes;
			var set   = props.setAttributes;
			var blockProps = useBlockProps ? useBlockProps() : {};

			var gridEnabled = attrs.columns && attrs.columns.length > 0;

			// Build the ordered list of inspector children.
			var inspectorChildren = [
				el(
					PanelBody,
					{ title: __( 'Layout', 'sas-menu-maker' ), initialOpen: true, key: 'layout' },
					el( SelectControl, {
						label: __( 'Image position', 'sas-menu-maker' ),
						value: attrs.image,
						options: [
							{ label: __( 'Left', 'sas-menu-maker' ),  value: 'left'  },
							{ label: __( 'Right', 'sas-menu-maker' ), value: 'right' },
							{ label: __( 'Top', 'sas-menu-maker' ),   value: 'top'   }
						],
						onChange: function ( v ) { set( { image: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Variants', 'sas-menu-maker' ),
						help:  __( 'Show variants directly on the item, or only in the details window.', 'sas-menu-maker' ),
						value: attrs.variants,
						options: [
							{ label: __( 'Inline on the card', 'sas-menu-maker' ), value: 'inline' },
							{ label: __( 'Only in modal', 'sas-menu-maker' ),      value: 'modal'  }
						],
						onChange: function ( v ) { set( { variants: v } ); }
					} ),
					el( ToggleControl, {
						label:   __( 'Show allergen legend', 'sas-menu-maker' ),
						help:    __( 'A small allergen list is printed at the end of the menu. Hiding it also removes the code letters next to each item.', 'sas-menu-maker' ),
						checked: 'show' === attrs.allergensLegend,
						onChange: function ( on ) {
							set( { allergensLegend: on ? 'show' : 'hide' } );
						}
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Filter titles', 'sas-menu-maker' ), initialOpen: false, key: 'titles' },
					el( TextControl, {
						label: __( 'Categories label', 'sas-menu-maker' ),
						value: attrs.categoriesTitle,
						onChange: function ( v ) { set( { categoriesTitle: v } ); }
					} ),
					el( TextControl, {
						label: __( 'Tags label', 'sas-menu-maker' ),
						value: attrs.tagsTitle,
						onChange: function ( v ) { set( { tagsTitle: v } ); }
					} ),
					el( TextControl, {
						label: __( 'Allergens label', 'sas-menu-maker' ),
						value: attrs.allergensTitle,
						onChange: function ( v ) { set( { allergensTitle: v } ); }
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Grid layout', 'sas-menu-maker' ), initialOpen: false, key: 'grid' },
					el( ToggleControl, {
						label: __( 'Show items as a grid', 'sas-menu-maker' ),
						checked: gridEnabled,
						onChange: function ( on ) {
							set( { columns: on ? '720__1 1024__2 1400__3' : '' } );
						}
					} ),
					gridEnabled && el( TextControl, {
						label: __( 'Columns spec', 'sas-menu-maker' ),
						help:  __( 'Format: <max-width>__<columns>, space-separated. Example: 720__1 1024__2 1400__3', 'sas-menu-maker' ),
						value: attrs.columns,
						onChange: function ( v ) { set( { columns: v } ); }
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Alignment & size', 'sas-menu-maker' ), initialOpen: false, key: 'align' },
					el( SelectControl, {
						label: __( 'Font size', 'sas-menu-maker' ),
						help:  __( 'Scales the whole menu up or down.', 'sas-menu-maker' ),
						value: attrs.fontScale,
						options: [
							{ label: __( 'Small',  'sas-menu-maker' ), value: 'small'  },
							{ label: __( 'Medium', 'sas-menu-maker' ), value: 'medium' },
							{ label: __( 'Large',  'sas-menu-maker' ), value: 'large'  }
						],
						onChange: function ( v ) { set( { fontScale: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Filter alignment', 'sas-menu-maker' ),
						value: attrs.filterAlign,
						options: [
							{ label: __( 'Left',   'sas-menu-maker' ), value: 'left'   },
							{ label: __( 'Center', 'sas-menu-maker' ), value: 'center' },
							{ label: __( 'Right',  'sas-menu-maker' ), value: 'right'  }
						],
						onChange: function ( v ) { set( { filterAlign: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Item content alignment', 'sas-menu-maker' ),
						value: attrs.itemAlign,
						options: [
							{ label: __( 'Left',   'sas-menu-maker' ), value: 'left'   },
							{ label: __( 'Center', 'sas-menu-maker' ), value: 'center' },
							{ label: __( 'Right',  'sas-menu-maker' ), value: 'right'  }
						],
						onChange: function ( v ) { set( { itemAlign: v } ); }
					} ),
					el( RangeControl, {
						label: __( 'Border radius (px)', 'sas-menu-maker' ),
						help:  __( 'One value for the whole block — container, filter, items, image and tags.', 'sas-menu-maker' ),
						value: '' === attrs.borderRadius ? undefined : parseInt( attrs.borderRadius, 10 ),
						min:   0,
						max:   40,
						step:  1,
						allowReset: true,
						onChange: function ( v ) {
							set( { borderRadius: ( v === undefined || v === null ) ? '' : String( v ) } );
						}
					} )
				),
			];

			// Each color group is wrapped in its own PanelBody so it gets
			// the same collapse-on-title toggle as the panels above.
			// `PanelColorSettings` alone stopped honouring initialOpen in
			// newer WP where it renders as a flat dropdown list instead
			// of a collapsible section — the outer PanelBody restores
			// consistent UX. The inner title is intentionally blank so
			// no duplicate header shows through in either WP branch.
			colorGroups.forEach( function ( group, idx ) {
				inspectorChildren.push(
					el(
						PanelBody,
						{ title: group.title, initialOpen: false, key: 'colors-panel-' + idx },
						el( PanelColorSettings, {
							title: '',
							colorSettings: colorSettingsFor( group, attrs, set )
						} )
					)
				);
			} );

			return el(
				Fragment,
				{},
				el( InspectorControls, {}, inspectorChildren ),
				el(
					'div',
					blockProps,
					ServerSideRender
						? el( ServerSideRender, {
							block: 'sas-menu-maker/menu',
							attributes: attrs
						} )
						: el( 'p', {}, __( 'Preview unavailable in this WordPress version.', 'sas-menu-maker' ) )
				)
			);
		},

		// Dynamic block — server prints the HTML at request time.
		save: function () {
			return null;
		}
	} );
}( window.wp ) );
