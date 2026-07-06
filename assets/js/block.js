/**
 * Editor UI for the "SweetHome3D Model" block.
 *
 * Written without JSX so the plugin ships with no build step.
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var components = wp.components;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var __experimentalNumberControl = components.__experimentalNumberControl;
	var ServerSideRender = wp.serverSideRender;

	var settings = window.EmbedSweetHome3D || { models: [] };

	var modelOptions = [ { value: 0, label: __( '— Select a model —', 'embed-sweethome3d' ) } ].concat(
		settings.models || []
	);

	var ratioOptions = [
		{ value: '4:3', label: '4:3' },
		{ value: '16:9', label: '16:9' },
		{ value: '3:2', label: '3:2' },
		{ value: '1:1', label: '1:1' },
	];

	var navOptions = [
		{ value: 'none', label: __( 'Hidden', 'embed-sweethome3d' ) },
		{ value: 'default', label: __( 'Default panel', 'embed-sweethome3d' ) },
	];

	registerBlockType( 'embed-sweethome3d/model', {
		attributes: {
			id: { type: 'number', default: 0 },
			width: { type: 'number', default: 0 },
			ratio: { type: 'string', default: '4:3' },
			rotation: { type: 'number', default: 0 },
			nav: { type: 'string', default: 'none' },
		},

		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps();

			var controls = el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Model settings', 'embed-sweethome3d' ), initialOpen: true },
					el( SelectControl, {
						label: __( 'Model', 'embed-sweethome3d' ),
						value: attributes.id,
						options: modelOptions,
						onChange: function ( value ) {
							setAttributes( { id: parseInt( value, 10 ) || 0 } );
						},
					} ),
					el( SelectControl, {
						label: __( 'Aspect ratio', 'embed-sweethome3d' ),
						value: attributes.ratio,
						options: ratioOptions,
						onChange: function ( value ) {
							setAttributes( { ratio: value } );
						},
					} ),
					el( __experimentalNumberControl || components.TextControl, {
						label: __( 'Max width (px, 0 = responsive)', 'embed-sweethome3d' ),
						value: attributes.width,
						min: 0,
						onChange: function ( value ) {
							setAttributes( { width: parseInt( value, 10 ) || 0 } );
						},
					} ),
					el( RangeControl, {
						label: __( 'Auto-rotation (rounds / min)', 'embed-sweethome3d' ),
						value: attributes.rotation,
						min: 0,
						max: 10,
						onChange: function ( value ) {
							setAttributes( { rotation: value || 0 } );
						},
					} ),
					el( SelectControl, {
						label: __( 'Navigation panel', 'embed-sweethome3d' ),
						value: attributes.nav,
						options: navOptions,
						onChange: function ( value ) {
							setAttributes( { nav: value } );
						},
					} )
				)
			);

			var preview;
			if ( attributes.id > 0 ) {
				preview = el( ServerSideRender, {
					block: 'embed-sweethome3d/model',
					attributes: attributes,
				} );
			} else {
				preview = el(
					'p',
					{ className: 'sweethome3d-block-placeholder' },
					__( 'Select a SweetHome3D model in the block settings.', 'embed-sweethome3d' )
				);
			}

			return el( 'div', blockProps, controls, preview );
		},

		// Dynamic block: rendering happens on the server.
		save: function () {
			return null;
		},
	} );
} )( window.wp );
