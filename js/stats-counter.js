/**
 * Smooth count-up animation for stat numbers.
 */
( function() {
	const SELECTOR = '[data-stat-count]';
	const DURATION_MS = 1400;

	function easeOutCubic( progress ) {
		return 1 - Math.pow( 1 - progress, 3 );
	}

	function toNumber( value ) {
		const parsed = Number( value );
		return Number.isFinite( parsed ) ? parsed : NaN;
	}

	function renderValue( element, value ) {
		const suffix = element.getAttribute( 'data-stat-suffix' ) || '';
		element.textContent = String( value ) + suffix;
	}

	function finishAnimation( element, target ) {
		renderValue( element, target );
		element.setAttribute( 'data-stat-animated', '1' );
	}

	function animateCounter( element, prefersReducedMotion ) {
		if ( '1' === element.getAttribute( 'data-stat-animated' ) ) {
			return;
		}

		const target = toNumber( element.getAttribute( 'data-stat-count' ) );
		if ( Number.isNaN( target ) ) {
			return;
		}

		if ( prefersReducedMotion ) {
			finishAnimation( element, target );
			return;
		}

		let startTime = null;

		function tick( timestamp ) {
			if ( null === startTime ) {
				startTime = timestamp;
			}

			const elapsed = Math.min( timestamp - startTime, DURATION_MS );
			const progress = elapsed / DURATION_MS;
			const eased = easeOutCubic( progress );
			const currentValue = Math.round( target * eased );

			renderValue( element, currentValue );

			if ( elapsed < DURATION_MS ) {
				window.requestAnimationFrame( tick );
				return;
			}

			finishAnimation( element, target );
		}

		window.requestAnimationFrame( tick );
	}

	function initCounters() {
		const counters = Array.from( document.querySelectorAll( SELECTOR ) );
		if ( ! counters.length ) {
			return;
		}

		const prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		if ( ! ( 'IntersectionObserver' in window ) ) {
			counters.forEach( ( counter ) => animateCounter( counter, prefersReducedMotion ) );
			return;
		}

		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) {
						return;
					}

					animateCounter( entry.target, prefersReducedMotion );
					observer.unobserve( entry.target );
				} );
			},
			{
				threshold: 0.35,
				rootMargin: '0px 0px -10% 0px',
			}
		);

		counters.forEach( ( counter ) => observer.observe( counter ) );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initCounters );
		return;
	}

	initCounters();
}() );
