/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const initFrontHeader = function() {
		const headerShell = document.querySelector( '.front-header-shell' );
		const topBanner = headerShell ? headerShell.querySelector( '.front-top-banner' ) : null;
		const navRow = headerShell ? headerShell.querySelector( '.front-nav-row' ) : null;
		const closeButton = headerShell ? headerShell.querySelector( '.front-top-banner__close' ) : null;
		let isTicking = false;
		let isScrolled = false;
		const enterThreshold = 72;
		const exitThreshold = 4;
		const progressRange = 260;

		if ( ! headerShell || ! navRow ) {
			return;
		}

		const updateHeaderState = function() {
			const scrollY = window.scrollY || window.pageYOffset || 0;
			const hasScrolled = isScrolled ? scrollY > exitThreshold : scrollY > enterThreshold;
			const scrollProgress = Math.max( 0, Math.min( 1, ( scrollY - enterThreshold ) / progressRange ) );

			isScrolled = hasScrolled;

			headerShell.style.setProperty( '--front-nav-height', hasScrolled ? navRow.offsetHeight + 'px' : '0px' );
			headerShell.style.setProperty( '--front-nav-scroll-progress', scrollProgress.toFixed( 3 ) );
			headerShell.classList.toggle( 'is-scrolled', hasScrolled );
		};

		const onScroll = function() {
			if ( isTicking ) {
				return;
			}

			isTicking = true;
			window.requestAnimationFrame( function() {
				updateHeaderState();
				isTicking = false;
			} );
		};

		if ( closeButton && topBanner ) {
			closeButton.addEventListener( 'click', function( event ) {
				event.preventDefault();
				event.stopPropagation();
				topBanner.classList.add( 'is-dismissed' );
				updateHeaderState();
			} );
		}

		updateHeaderState();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', updateHeaderState );
	};

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initFrontHeader );
		return;
	}

	initFrontHeader();
}() );

( function() {
	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	const targets = document.querySelectorAll( 'main section, .site-main > article, .site-main > .page, .site-main > .post, .entry-content > *' );

	if ( ! targets.length ) {
		return;
	}

	const observer = new IntersectionObserver(
		( entries, io ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					io.unobserve( entry.target );
				}
			} );
		},
		{
			threshold: 0.12,
			rootMargin: '0px 0px -8% 0px',
		}
	);

	targets.forEach( ( target ) => {
		if ( ! target.classList.contains( 'scroll-animate' ) ) {
			target.classList.add( 'scroll-animate' );
		}

		const rect = target.getBoundingClientRect();
		if ( rect.top < window.innerHeight * 0.92 ) {
			target.classList.add( 'is-visible' );
			return;
		}

		observer.observe( target );
	} );
}() );

( function() {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	const closeMenuIfDesktop = function() {
		if ( window.innerWidth > 900 && siteNavigation.classList.contains( 'toggled' ) ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	};

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	window.addEventListener( 'resize', closeMenuIfDesktop );
	closeMenuIfDesktop();

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus( event ) {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}

		if ( event.type === 'touchstart' ) {
			const menuItem = this.parentNode;
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}
}() );
