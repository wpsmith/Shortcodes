<?php

namespace WPS\WP\Shortcodes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\SearchForm' ) ) {
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
		 * Whether the shortcode is active on the page.
		 *
		 * Since no script/style is needed for this shortcode, we optimize performance
		 * by saying the shortcode is already active.
		 *
		 * @var bool
		 */
		protected $is_active = true;

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