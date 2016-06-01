<?php

class Embed_Image_Shortcode {


	public static function init() {

		self::attach_hooks();

	}

	public static function attach_hooks() {
		add_shortcode( 'embed-image', array( __CLASS__, 'shortcode' ) );
	}

	public static function shortcode( $atts ) {

		$atts = shortcode_atts( array(
			'id'     => 0,
			'href'   => '',
			'target' => '',
			'size'   => 'full',
			'alt'    => '',
			'class'  => '',
			'center' => false,

		), $atts );


		$image_data = wp_get_attachment_image_src( $atts['id'], $atts['size'] );

		if ( ! $image_data ) {
			return '';
		}


		$image_url = $image_data[0];

		$image = sprintf( '<img alt="%s" src="%s">', esc_attr( $atts['alt'] ), esc_url( $image_url ) );

		$elements_before = $elements_after = $outer_wrapper_before = $outer_wrapper_after = '';

		if ( $atts['center'] ) {
			$outer_wrapper_before .= '<div class="text-center">';
			$outer_wrapper_after .= '</div>';
		}

		if ( $atts['href'] ) {
			$target = '';
			if ( $atts['target'] ) {
				$target = ' target="' . esc_attr( $atts['target'] ) . '" ';

			}
			$elements_before .= sprintf( '<a %s href="%s">', $target, esc_url( $atts['href'] ) ); //$target escaped above
			$elements_after  .= '</a>';
		}

		$template = '%s<textarea  onclick="this.focus();this.select()" readonly="readonly" class="embed-code %s">%s %s %s</textarea>%s';
		$output   = sprintf( $template, $outer_wrapper_before, esc_attr( $atts['class'] ),  $elements_before, $image, $elements_after, $outer_wrapper_after );


		return do_shortcode( $output );


	}

}

