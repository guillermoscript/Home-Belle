<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

// if(is_archive()){
	$text = '<svg xmlns="http://www.w3.org/2000/svg" width="32.653" height="19" viewBox="0 0 32.653 19"><defs><style>.cls-1 {font-size: 15px;font-family: Montserrat-Bold, Montserrat;font-weight: 700;}</style></defs><g id="Group_202" data-name="Group 202" transform="translate(-567 -545)"><path id="shopping-cart" d="M18.845,24.618H3.479l-.228-2.545a.807.807,0,0,0-.8-.735H.807a.807.807,0,0,0,0,1.614h.9l.928,10.342A3.105,3.105,0,0,0,3.8,35.4a2.446,2.446,0,1,0,4.228.673h4.417a2.447,2.447,0,1,0,2.3-1.614H5.732a1.5,1.5,0,0,1-1.378-.909l12.9-.758a.807.807,0,0,0,.736-.61l1.64-6.559A.807.807,0,0,0,18.845,24.618ZM5.727,37.736a.833.833,0,1,1,.833-.833A.834.834,0,0,1,5.727,37.736Zm9.019,0a.833.833,0,1,1,.833-.833A.834.834,0,0,1,14.746,37.736Zm1.82-6.523-12.429.73-.513-5.712H17.812Z" transform="translate(580 523.662)"/><text id="_" data-name="+" class="cls-1" transform="translate(572 560)"><tspan x="-4.492" y="0">+</tspan></text></g></svg>';
// } else {
// 	$text = esc_html( $product->add_to_cart_text() );
// }

echo apply_filters(
	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		$text
	),
	$product,
	$args
);
