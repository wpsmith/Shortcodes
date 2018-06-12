<?php
/**
 * Gallery Shortcode Class
 *
 * Creates a new gallery shortcode.
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\Shortcodes
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2018 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\Shortcodes;

use WPS\Shortcodes\Shortcode;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPS\Shortcodes\Gallery' ) ) {
	/**
	 * Shortcode Abstract Class
	 *
	 * Assists in creating Shortcodes.
	 *
	 * @package WPS\Core
	 * @author  Travis Smith <t@wpsmith.net>
	 */
	class Gallery extends Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var string
		 */
		public $name = 'gallery';

		/**
		 * Gets default attributes.
		 *
		 * @return array Default attributes
		 */
		protected function get_defaults() {
			$post  = get_post();
			$html5 = current_theme_supports( 'html5', 'gallery' );

			return array(
				'order'           => 'ASC',
				'orderby'         => 'menu_order ID',
				'id'              => $post ? $post->ID : 0,
				'itemtag'         => $html5 ? 'figure' : 'dl',
				'icontag'         => $html5 ? 'div' : 'dt',
				'captiontag'      => $html5 ? 'figcaption' : 'dd',
				'columns'         => 3,
				'size'            => 'thumbnail',
				'include'         => '',
				'exclude'         => '',
				'link'            => '',
				'item-classes'    => '',
				'gallery-classes' => '',
			);
		}

		/**
		 * Performs the shortcode.
		 *
		 * @param array  $atts    Array of user attributes.
		 * @param string $content Content of the shortcode.
		 *
		 * @return string Parsed output of the shortcode.
		 */
		public function shortcode( $attr, $content = null ) {
			static $instance = 0;
			$instance ++;

			if ( ! empty( $attr['ids'] ) ) {
				// 'ids' is explicitly ordered, unless you specify otherwise.
				if ( empty( $attr['orderby'] ) ) {
					$attr['orderby'] = 'post__in';
				}
				$attr['include'] = $attr['ids'];
			}

			/**
			 * Filters the default gallery shortcode output.
			 *
			 * If the filtered output isn't empty, it will be used instead of generating
			 * the default gallery template.
			 *
			 * @since 2.5.0
			 * @since 4.2.0 The `$instance` parameter was added.
			 *
			 * @see   gallery_shortcode()
			 *
			 * @param string $output   The gallery output. Default empty.
			 * @param array  $attr     Attributes of the gallery shortcode.
			 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
			 */
			$output = apply_filters( 'post_gallery', '', $attr, $instance );
			if ( $output != '' ) {
				return $output;
			}

			$html5 = current_theme_supports( 'html5', 'gallery' );
			$atts  = shortcode_atts( $this->get_defaults(), $attr, 'gallery' );

			$masonry = false;
			if ( false !== strpos( $atts['gallery-classes'], 'masonry' ) ) {
				$masonry = true;
				add_filter( 'use_default_gallery_style', '__return_false' );
			}

			$id = intval( $atts['id'] );

			if ( ! empty( $atts['include'] ) ) {
				$_attachments = get_posts( array(
					'include'        => $atts['include'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby']
				) );

				$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[ $val->ID ] = $_attachments[ $key ];
				}
			} elseif ( ! empty( $atts['exclude'] ) ) {
				$attachments = get_children( array(
					'post_parent'    => $id,
					'exclude'        => $atts['exclude'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby']
				) );
			} else {
				$attachments = get_children( array(
					'post_parent'    => $id,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby']
				) );
			}

			if ( empty( $attachments ) ) {
				return '';
			}

			if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $att_id => $attachment ) {
					$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
				}

				return $output;
			}

			$itemtag    = tag_escape( $atts['itemtag'] );
			$captiontag = tag_escape( $atts['captiontag'] );
			$icontag    = tag_escape( $atts['icontag'] );
			$valid_tags = wp_kses_allowed_html( 'post' );
			if ( ! isset( $valid_tags[ $itemtag ] ) ) {
				$itemtag = 'dl';
			}
			if ( ! isset( $valid_tags[ $captiontag ] ) ) {
				$captiontag = 'dd';
			}
			if ( ! isset( $valid_tags[ $icontag ] ) ) {
				$icontag = 'dt';
			}

			$columns   = intval( $atts['columns'] );
			$itemwidth = $columns > 0 ? floor( 100 / $columns ) : 100;
			$float     = is_rtl() ? 'right' : 'left';

			$selector = "gallery-{$instance}";

			$gallery_style = '';

			/**
			 * Filters whether to print default gallery styles.
			 *
			 * @since 3.1.0
			 *
			 * @param bool $print Whether to print default gallery styles.
			 *                    Defaults to false if the theme supports HTML5 galleries.
			 *                    Otherwise, defaults to true.
			 */
			if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
				$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
			} elseif ( $masonry ) {
				$gallery_style = "
		<style type='text/css'>
			.masonry, .gallery {
				display: flex;
				flex-flow: column wrap;
				margin-left: -8px; /* Adjustment for the gutter */
				width: 100%;
				counter-reset: item;
				max-height: 3000px;
			    overflow: hidden;
			    vertical-align: baseline;
			}
			.masonry-brick, .gallery-item {
				display: block;
				overflow: hidden;
				position: relative;
				margin: 0 8px 8px 0; /* Some gutter */
			    vertical-align: baseline;
			}
			.masonry-brick:after, .gallery-item:after {
				position: absolute;
				top: 50%;
				left: 50%;
				z-index: 5000;
				transform: translate(-50%, -50%);
				counter-increment: item;
				content: counter(item);
			}
			.gallery-columns-2 .gallery-item { max-width: 49% }
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
			}

			$size_class      = sanitize_html_class( $atts['size'] );
			$gallery_classes = sanitize_html_class( $atts['gallery-sizes'] );
			$gallery_div     = "<div id='$selector' class='wps gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} {$gallery_classes}'>";

			/**
			 * Filters the default gallery shortcode CSS styles.
			 *
			 * @since 2.5.0
			 *
			 * @param string $gallery_style Default CSS styles and opening HTML div container
			 *                              for the gallery shortcode output.
			 */
			$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

			$item_classes = sanitize_html_class( $atts['item-sizes'] );

			$i = 0;
			foreach ( $attachments as $id => $attachment ) {
				$column_classes = WPS\get_column_classes( $columns, $i );

				$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
				if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
					$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
				} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
					$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
				} else {
					$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
				}
				$image_meta = wp_get_attachment_metadata( $id );

				$orientation = '';
				if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
					$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
				}
				$output .= "<{$itemtag} class='gallery-item {$item_classes} {$column_classes}'>";
				$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
				if ( $captiontag && trim( $attachment->post_excerpt ) ) {
					$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . wptexturize( $attachment->post_excerpt ) . "
				</{$captiontag}>";
				}
				$output .= "</{$itemtag}>";
				if ( ! $html5 && $columns > 0 && ++ $i % $columns == 0 ) {
					$output .= '<br style="clear: both" />';
				}
			}

			if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
				$output .= "
			<br style='clear: both' />";
			}

			$output .= "
		</div>\n";

			return $output;
		}
	}
}