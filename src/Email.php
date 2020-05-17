<?php
/**
 * The [email] shortcode.
 *
 * Example use: [email email="" title=""]Email Me![/email]
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

if ( ! class_exists( __NAMESPACE__ . '\Email' ) ) {
	/**
	 * Class Email
	 *
	 * @package WPS\WP\Shortcodes
	 */
	class Email extends Shortcode {

		/**
		 * Shortcode name.
		 *
		 * @var string
		 */
		public $name = 'email';

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
				'title' => __( 'Email Us', 'wps' ),
				'email' => 'noreply@' . $_SERVER['HTTP_HOST'],
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

			return sprintf(
				'<a href="mailto:%s" title="%s">%s</a>',
				antispambot( $atts['email'], 1 ),
				esc_attr( $atts['title'] ),
				antispambot( $content )
			);
		}

	}
}