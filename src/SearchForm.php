<?php

namespace WPS\Shortcodes;

use WPS\Core;

if ( ! class_exists( '\WPS\Shortcodes\SearchForm' ) ) {
	/**
	 * Class SearchForm
	 *
	 * @package WPS\Shortcodes
	 */
	class SearchForm extends Core\Shortcode {

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