<?php
/**
 * The [menu] shortcode.
 *
 * Example use: [menu name="" id=""]
 *
 * You may copy, distribute and modify the software as long as you track
 * changes/dates in source files. Any modifications to or software including
 * (via compiler) GPL-licensed code must also be made available under the GPL
 * along with build & install instructions.
 *
 * PHP Version 7.2
 *
 * @category   WPS\WP\Shortcodes
 * @package    WPS\WP\Shortcodes
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2020 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://wpsmith.net/
 * @since      0.0.1
 */

namespace WPS\WP\Shortcodes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Menu' ) ) {
	/**
	 * Class Menu
	 *
	 * @package WPS\WP\Shortcodes
	 */
	class Menu extends Shortcode {

		/**
		 * Shortcode name.
		 *
		 * @var string
		 */
		public $name = 'menu';

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
		 * Gets default attributes.
		 *
		 * @return array Default attributes
		 */
		protected function get_defaults() {
			return array(
				'id'   => '',
				'name' => '',
			);
		}

		/**
		 * Performs the shortcode.
		 *
		 * @param array  $atts Array of user attributes.
		 * @param string $content Content of the shortcode.
		 *
		 * @return string Parsed output of the shortcode.
		 */
		public function shortcode( $atts, $content = null ) {
			$atts = $this->shortcode_atts( $atts );

			if ( '' !== $atts['id'] ) {
				return wp_nav_menu( array( 'menu_id' => $atts['id'], 'echo' => false ) );
			}

			if ( '' === $atts['name'] ) {
				return wp_nav_menu( array( 'menu' => $atts['name'], 'echo' => false ) );
			}


		}

	}
}