/**
 * Loads the model-viewer library (about 250 KB) only when the 3D viewer is close to the screen,
 * so product pages stay fast for visitors who never scroll down to it.
 */
( function () {
	var viewer = document.querySelector( '.liepa-viewer' );
	if ( ! viewer || ! window.liepaViewer ) {
		return;
	}

	var loaded = false;
	function loadLibrary() {
		if ( loaded ) {
			return;
		}
		loaded = true;
		var script = document.createElement( 'script' );
		script.type = 'module';
		script.src = window.liepaViewer.src;
		document.head.appendChild( script );
	}

	if ( ! ( 'IntersectionObserver' in window ) ) {
		loadLibrary();
		return;
	}

	var observer = new IntersectionObserver(
		function ( entries ) {
			if ( entries[ 0 ].isIntersecting ) {
				loadLibrary();
				observer.disconnect();
			}
		},
		{ rootMargin: '300px' }
	);
	observer.observe( viewer );
} )();
