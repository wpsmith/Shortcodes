<?php
/**
 * The [parallax_image] shortcode.
 *
 * Example use: [parallax_image]
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

if ( ! class_exists( __NAMESPACE__ . '\ParallaxImage' ) ) {
	/**
	 * Class ParallaxImage
	 *
	 * @package WPS\WP\Shortcodes
	 */
	class ParallaxImage extends Shortcode {

		/**
		 * Shortcode name.
		 *
		 * @var string
		 */
		public $name = 'parallax_image';

		/**
		 * Gets default attributes.
		 *
		 * @return array Default attributes
		 */
		protected function get_defaults() {
			return array(
				'src'   => '',
				'style' => '',
			);
		}

		/**
		 * An array of allowed HTML elements and attributes, or a context name such as 'post'.
		 *
		 * @return array[]|string
		 */
		protected function get_allowed_html() {
			return array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
					'class' => array(),
				),
				'h2'     => array(
					'class' => array(),
				),
				'h3'     => array(
					'class' => array(),
				),
				'h4'     => array(
					'class' => array(),
				),
				'h5'     => array(
					'class' => array(),
				),
				'h6'     => array(
					'class' => array(),
				),
				'p'      => array(
					'class' => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
			);
		}

		/**
		 * Register the style in init hook.
		 *
		 * This is called automagically on init hook.
		 * If init hook has already been done, this method is run immediately.
		 */
		public function register_scripts() {
			$suffix = wp_scripts_get_suffix();

			wp_register_style(
				'parallax-image-shortcode',
				plugins_url( "assets/parallaxiamge/parallax-image.{$suffix}css", __FILE__ ),
				null,
				filemtime( plugin_dir_path( __FILE__ ) . "assets/parallaxiamge/parallax-image.{$suffix}css" )
			);
		}

		/**
		 * Enqueue the style.
		 *
		 * This is called automagically on wp_enqueue_scripts if the shortcode exists on the page.
		 * If wp_enqueue_scripts hook has already been done, this method is run immediately.
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'parallax-image-shortcode' );
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
				'<div class="parallax-image-section full-width"><div class="image-container image-section" style="background-image:url(\'%s\'); %s"><div class="image-content">%s</div></div></div>',
				esc_attr( $atts['src'] ),
				esc_attr( $atts['style'] ),
				wp_kses( $content, $this->get_allowed_html() )
			);
		}
	}
}