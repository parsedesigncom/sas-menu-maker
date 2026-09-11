/**
 * MenuCraft — Gutenberg "offers" block editor script.
 *
 * Dynamic block: the frontend HTML is produced server-side (by wrapping
 * the [menucraft_offers] shortcode); this script only wires up the block-
 * inserter entry, the inspector controls in the sidebar, and a
 * ServerSideRender preview so authors see the real offers inside the
 * editor.
 *
 * Written in plain JS (no JSX / no build step) so the plugin ships
 * uncompiled — WP.org compliant out of the box.
 */
( function ( wp ) {
	'use strict';

	var el       = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var __       = wp.i18n.__;
	var registerBlockType = wp.blocks.registerBlockType;

	var InspectorControls  = wp.blockEditor.InspectorControls;
	var PanelColorSettings = wp.blockEditor.PanelColorSettings;
	var useBlockProps      = wp.blockEditor.useBlockProps;

	var PanelBody     = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl   = wp.components.TextControl;
	var ToggleControl = wp.components.ToggleControl;
	var RangeControl  = wp.components.RangeControl;

	var ServerSideRender = wp.serverSideRender
		|| ( wp.editor && wp.editor.ServerSideRender )
		|| ( wp.components && wp.components.ServerSideRender );

	// Grouped color slots — must mirror class-menucraft-offers-block.php.
	var colorGroups = [
		{
			title: __( 'Colors — Container', 'sas-menu-maker' ),
			colors: [
				{ attr: 'bgColor',   label: __( 'Background', 'sas-menu-maker' ) },
				{ attr: 'textColor', label: __( 'Text (fallback)', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Cards', 'sas-menu-maker' ),
			colors: [
				{ attr: 'cardBg',         label: __( 'Card background', 'sas-menu-maker' ) },
				{ attr: 'cardBorder',     label: __( 'Card border', 'sas-menu-maker' ) },
				{ attr: 'cardTitleColor', label: __( 'Title', 'sas-menu-maker' ) },
				{ attr: 'cardDescColor',  label: __( 'Description', 'sas-menu-maker' ) },
				{ attr: 'cardPriceColor', label: __( 'Price', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Offer details', 'sas-menu-maker' ),
			colors: [
				{ attr: 'linesColor',      label: __( 'Composition list', 'sas-menu-maker' ) },
				{ attr: 'validityColor',   label: __( 'Validity dates', 'sas-menu-maker' ) },
				{ attr: 'conditionsColor', label: __( 'Conditions text', 'sas-menu-maker' ) },
			],
		},
	];

	function colorSettingsFor( group, attrs, set ) {
		return group.colors.map( function ( slot ) {
			return {
				label:       slot.label,
				value:       attrs[ slot.attr ] || '',
				enableAlpha: true,
				onChange: function ( v ) {
					var upd = {};
					upd[ slot.attr ] = v || '';
					set( upd );
				},
			};
		} );
	}

	registerBlockType( 'menucraft/offers', {
		edit: function ( props ) {
			var attrs = props.attributes;
			var set   = props.setAttributes;
			var blockProps = useBlockProps ? useBlockProps() : {};

			var gridEnabled = attrs.columns && attrs.columns.length > 0;

			var inspectorChildren = [
				el(
					PanelBody,
					{ title: __( 'Content & layout', 'sas-menu-maker' ), initialOpen: true, key: 'content' },
					el( SelectControl, {
						label: __( 'Which offers?', 'sas-menu-maker' ),
						help:  __( 'Preview: running now or starting within 7 days. All: every active offer.', 'sas-menu-maker' ),
						value: attrs.validity,
						options: [
							{ label: __( 'Preview (default)', 'sas-menu-maker' ), value: 'preview' },
							{ label: __( 'All active', 'sas-menu-maker' ),        value: 'all' }
						],
						onChange: function ( v ) { set( { validity: v } ); }
					} ),
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
					el( ToggleControl, {
						label:   __( 'Show validity dates', 'sas-menu-maker' ),
						checked: 'show' === attrs.showDates,
						onChange: function ( on ) { set( { showDates: on ? 'show' : 'hide' } ); }
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Card vs. modal', 'sas-menu-maker' ), initialOpen: false, key: 'placement' },
					el( SelectControl, {
						label: __( 'Description', 'sas-menu-maker' ),
						value: attrs.showDesc,
						options: [
							{ label: __( 'On the card',   'sas-menu-maker' ), value: 'inline' },
							{ label: __( 'Only in modal', 'sas-menu-maker' ), value: 'modal'  },
							{ label: __( "Don't show",    'sas-menu-maker' ), value: 'hide'   }
						],
						onChange: function ( v ) { set( { showDesc: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Items list', 'sas-menu-maker' ),
						value: attrs.showItems,
						options: [
							{ label: __( 'On the card',   'sas-menu-maker' ), value: 'inline' },
							{ label: __( 'Only in modal', 'sas-menu-maker' ), value: 'modal'  },
							{ label: __( "Don't show",    'sas-menu-maker' ), value: 'hide'   }
						],
						onChange: function ( v ) { set( { showItems: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Conditions text', 'sas-menu-maker' ),
						value: attrs.conditions,
						options: [
							{ label: __( 'Only in modal', 'sas-menu-maker' ), value: 'modal'  },
							{ label: __( 'On the card',   'sas-menu-maker' ), value: 'inline' },
							{ label: __( "Don't show",    'sas-menu-maker' ), value: 'hide'   }
						],
						onChange: function ( v ) { set( { conditions: v } ); }
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Grid layout', 'sas-menu-maker' ), initialOpen: false, key: 'grid' },
					el( ToggleControl, {
						label: __( 'Show offers as a grid', 'sas-menu-maker' ),
						checked: gridEnabled,
						onChange: function ( on ) {
							set( { columns: on ? '720__1 1024__2 1400__3' : '' } );
						}
					} ),
					gridEnabled && el( TextControl, {
						label: __( 'Columns spec', 'sas-menu-maker' ),
						help:  __( 'Format: <max-width>__<columns>, space-separated.', 'sas-menu-maker' ),
						value: attrs.columns,
						onChange: function ( v ) { set( { columns: v } ); }
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Alignment & size', 'sas-menu-maker' ), initialOpen: false, key: 'align' },
					el( SelectControl, {
						label: __( 'Font size', 'sas-menu-maker' ),
						help:  __( 'Scales the whole block up or down.', 'sas-menu-maker' ),
						value: attrs.fontScale,
						options: [
							{ label: __( 'Small',  'sas-menu-maker' ), value: 'small'  },
							{ label: __( 'Medium', 'sas-menu-maker' ), value: 'medium' },
							{ label: __( 'Large',  'sas-menu-maker' ), value: 'large'  }
						],
						onChange: function ( v ) { set( { fontScale: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Card content alignment', 'sas-menu-maker' ),
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
						help:  __( 'One value for container, cards, image and modal.', 'sas-menu-maker' ),
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

			// Wrap each color group in a PanelBody to force the same
			// collapse-on-title toggle used by the other panels; newer
			// WP versions render PanelColorSettings as a flat dropdown
			// list rather than a collapsible section, so its own
			// initialOpen has no effect.
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
							block: 'menucraft/offers',
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
