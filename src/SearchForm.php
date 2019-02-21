<?php

namespace WPS\WP\Shortcodes;

use WPS\WP\Shortcodes\Shortcode;

if ( ! class_exists( '\WPS\WP\Shortcodes\SearchForm' ) ) {
	/**
	 * Class SearchForm
	 *
	 * @package WPS\WP\Shortcodes
	 */
	class SearchForm extends Shortcode {

		/**
		 * Shortcode name.
		 *
		 * @var string
		 */
		public $name = 'search_form';

		/**
		 * Performs the shortcode.
		 *
		 * @param array  $atts    Array of user attributes.
		 * @param string $content Content of the shortcode.
		 *
		 * @return string Parsed output of the shortcode.
		 */
		public function shortcode( $atts, $content = null ) {
			return sprintf( '<div class="search-wrap">%s</div>', get_search_form( false ) );
		}

	}
}