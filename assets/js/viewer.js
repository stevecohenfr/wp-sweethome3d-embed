/**
 * Front-end bootstrap for the SweetHome3D HTML5 viewer.
 *
 * Initialises every `.sweethome3d-model` element on the page, so several
 * models can live on a single page — each with its own options.
 */
( function () {
	'use strict';

	function sizeCanvas( container, canvas ) {
		var width = container.clientWidth;
		var height = canvas.clientHeight || Math.round( ( width * 3 ) / 4 );
		canvas.width = width;
		canvas.height = height;
	}

	function initViewer( container ) {
		var url = container.getAttribute( 'data-model' );
		var canvas = container.querySelector( '.sweethome3d-canvas' );

		if ( ! url || ! canvas || ! canvas.id ) {
			return;
		}

		var rotation = parseInt( container.getAttribute( 'data-rotation' ), 10 ) || 0;
		var nav = container.getAttribute( 'data-nav' ) || 'none';
		var progressWrap = container.querySelector( '.sweethome3d-progress' );
		var progressBar = progressWrap ? progressWrap.querySelector( 'progress' ) : null;
		var progressLabel = progressWrap ? progressWrap.querySelector( 'label' ) : null;

		sizeCanvas( container, canvas );

		var onerror = function ( err ) {
			if ( progressWrap ) {
				progressWrap.style.display = 'none';
			}
			if ( err === 'No WebGL' ) {
				window.console.error( 'SweetHome3D: WebGL is not supported by this browser.' );
			} else {
				window.console.error( 'SweetHome3D viewer error:', err );
			}
		};

		var onprogression = function ( part, info, percentage ) {
			if ( ! progressBar ) {
				return;
			}

			if ( typeof HomeRecorder !== 'undefined' && part === HomeRecorder.READING_HOME ) {
				progressBar.value = percentage * 100;
				info = info.substring( info.lastIndexOf( '/' ) + 1 );
			} else if ( typeof Node3D !== 'undefined' && part === Node3D.READING_MODEL ) {
				progressBar.value = 100 + percentage * 100;
				if ( percentage === 1 && progressWrap ) {
					progressWrap.style.display = 'none';
				}
			}

			if ( progressLabel ) {
				progressLabel.textContent =
					( percentage ? Math.floor( percentage * 100 ) + '% ' : '' ) + part + ' ' + info;
			}
		};

		// Provided by assets/lib/viewhome.min.js.
		viewHome( canvas.id, url, onerror, onprogression, {
			roundsPerMinute: rotation,
			navigationPanel: nav,
		} );

		// Keep the canvas backing store in sync with its displayed size.
		var resizeTimer;
		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( function () {
				sizeCanvas( container, canvas );
			}, 200 );
		} );
	}

	function initAll() {
		var containers = document.querySelectorAll( '.sweethome3d-model' );
		Array.prototype.forEach.call( containers, initViewer );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
} )();
