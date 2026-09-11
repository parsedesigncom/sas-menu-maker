/**
 * MenuCraft — Gutenberg "group" block editor script.
 *
 * Dynamic block: the render_callback (in class-menucraft-group-block.php)
 * wraps the [menucraft_group] shortcode. This script drives the sidebar
 * and shows a live ServerSideRender preview.
 *
 * The Source panel loads Categories and Tags via REST so the author can
 * pick a specific one from a dropdown instead of typing a slug.
 *
 * Vanilla JS, no JSX, no build step.
 */
( function ( wp ) {
	'use strict';

	var el        = wp.element.createElement;
	var Fragment  = wp.element.Fragment;
	var useState  = wp.element.useState;
	var useEffect = wp.element.useEffect;
	var __        = wp.i18n.__;
	var apiFetch  = wp.apiFetch;
	var registerBlockType = wp.blocks.registerBlockType;

	var InspectorControls  = wp.blockEditor.InspectorControls;
	var PanelColorSettings = wp.blockEditor.PanelColorSettings;
	var useBlockProps      = wp.blockEditor.useBlockProps;

	var PanelBody     = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl   = wp.components.TextControl;
	var ToggleControl = wp.components.ToggleControl;
	var RangeControl  = wp.components.RangeControl;
	var Spinner       = wp.components.Spinner;

	var ServerSideRender = wp.serverSideRender
		|| ( wp.editor && wp.editor.ServerSideRender )
		|| ( wp.components && wp.components.ServerSideRender );

	var colorGroups = [
		{
			title: __( 'Colors — Container', 'sas-menu-maker' ),
			colors: [
				{ attr: 'bgColor',   label: __( 'Background', 'sas-menu-maker' ) },
				{ attr: 'textColor', label: __( 'Text (fallback)', 'sas-menu-maker' ) },
			],
		},
		{
			title: __( 'Colors — Header', 'sas-menu-maker' ),
			colors: [
				{ attr: 'headerBg',         label: __( 'Header background', 'sas-menu-maker' ) },
				{ attr: 'headerTitleColor', label: __( 'Header title', 'sas-menu-maker' ) },
				{ attr: 'headerDescColor',  label: __( 'Header description', 'sas-menu-maker' ) },
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
				enableAlpha: true,
				onChange: function ( v ) {
					var upd = {};
					upd[ slot.attr ] = v || '';
					set( upd );
				},
			};
		} );
	}

	registerBlockType( 'sas-menu-maker/group', {
		edit: function ( props ) {
			var attrs = props.attributes;
			var set   = props.setAttributes;
			var blockProps = useBlockProps ? useBlockProps() : {};

			// Load source lists once per editor session.
			var sourcesState = useState( { categories: null, tags: null } );
			var sources      = sourcesState[ 0 ];
			var setSources   = sourcesState[ 1 ];

			useEffect( function () {
				if ( ! apiFetch ) return;
				apiFetch( { path: 'sas-menu-maker/v1/categories' } ).then( function ( rows ) {
					setSources( function ( prev ) {
						return { categories: rows || [], tags: prev.tags };
					} );
				} ).catch( function () {
					setSources( function ( prev ) { return { categories: [], tags: prev.tags }; } );
				} );
				apiFetch( { path: 'sas-menu-maker/v1/tags' } ).then( function ( rows ) {
					setSources( function ( prev ) {
						return { categories: prev.categories, tags: rows || [] };
					} );
				} ).catch( function () {
					setSources( function ( prev ) { return { categories: prev.categories, tags: [] }; } );
				} );
			}, [] );

			var currentList = 'tag' === attrs.source ? sources.tags : sources.categories;
			var loading     = currentList === null;

			var sourceOptions = [ { label: __( '— pick one —', 'sas-menu-maker' ), value: 0 } ];
			if ( currentList && currentList.length ) {
				currentList.forEach( function ( row ) {
					sourceOptions.push( { label: row.name, value: row.id } );
				} );
			}

			var gridEnabled = attrs.columns && attrs.columns.length > 0;

			var inspectorChildren = [
				el(
					PanelBody,
					{ title: __( 'Source', 'sas-menu-maker' ), initialOpen: true, key: 'source' },
					el( SelectControl, {
						label: __( 'Show items from', 'sas-menu-maker' ),
						value: attrs.source,
						options: [
							{ label: __( 'Category', 'sas-menu-maker' ), value: 'category' },
							{ label: __( 'Tag',      'sas-menu-maker' ), value: 'tag'      }
						],
						onChange: function ( v ) {
							set( { source: v, sourceId: 0 } );
						}
					} ),
					loading
						? el( 'p', {}, el( Spinner ), ' ', __( 'Loading…', 'sas-menu-maker' ) )
						: el( SelectControl, {
							label: 'tag' === attrs.source
								? __( 'Tag', 'sas-menu-maker' )
								: __( 'Category', 'sas-menu-maker' ),
							value: attrs.sourceId,
							options: sourceOptions,
							onChange: function ( v ) { set( { sourceId: parseInt( v, 10 ) || 0 } ); }
						} )
				),
				el(
					PanelBody,
					{ title: __( 'Header', 'sas-menu-maker' ), initialOpen: false, key: 'header' },
					el( ToggleControl, {
						label: __( 'Show header (image, title, description)', 'sas-menu-maker' ),
						checked: 'show' === attrs.showHeader,
						onChange: function ( on ) { set( { showHeader: on ? 'show' : 'hide' } ); }
					} ),
					el( ToggleControl, {
						label: __( 'Start collapsed', 'sas-menu-maker' ),
						help:  __( 'Show only title + description; visitors click to reveal image and items.', 'sas-menu-maker' ),
						checked: 'yes' === attrs.collapsed,
						onChange: function ( on ) { set( { collapsed: on ? 'yes' : 'no' } ); }
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Layout', 'sas-menu-maker' ), initialOpen: false, key: 'layout' },
					el( SelectControl, {
						label: __( 'Image position (per item)', 'sas-menu-maker' ),
						value: attrs.image,
						options: [
							{ label: __( 'Left',  'sas-menu-maker' ), value: 'left'  },
							{ label: __( 'Right', 'sas-menu-maker' ), value: 'right' },
							{ label: __( 'Top',   'sas-menu-maker' ), value: 'top'   }
						],
						onChange: function ( v ) { set( { image: v } ); }
					} ),
					el( SelectControl, {
						label: __( 'Variants', 'sas-menu-maker' ),
						value: attrs.variants,
						options: [
							{ label: __( 'Inline on the card', 'sas-menu-maker' ), value: 'inline' },
							{ label: __( 'Only in modal', 'sas-menu-maker' ),      value: 'modal'  }
						],
						onChange: function ( v ) { set( { variants: v } ); }
					} ),
					el( ToggleControl, {
						label: __( 'Show allergen legend', 'sas-menu-maker' ),
						checked: 'show' === attrs.allergensLegend,
						onChange: function ( on ) { set( { allergensLegend: on ? 'show' : 'hide' } ); }
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
					attrs.sourceId
						? ( ServerSideRender
							? el( ServerSideRender, { block: 'sas-menu-maker/group', attributes: attrs } )
							: el( 'p', {}, __( 'Preview unavailable in this WordPress version.', 'sas-menu-maker' ) )
						)
						: el( 'p', { className: 'menucraft-block-hint' }, __( 'Pick a category or tag in the sidebar to see the preview.', 'sas-menu-maker' ) )
				)
			);
		},

		save: function () {
			return null;
		}
	} );
}( window.wp ) );
