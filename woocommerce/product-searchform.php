<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
	<div class="search_content">
		<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
		<div id="preview_search"></div>
	</div>
	<select id="select_cat" style="display: none; width: 100%"> 
		<option value="<?php echo esc_url( home_url( '/' ) ); ?>">Departamentos</option> 
		<?php 
		$categories = get_categories( array( 
			'taxonomy' => 'product_cat',
			'parent'  => 0,
		) ); 
		foreach ( $categories as $category ) {
			printf( '<option value="%1$s">%2$s</option>',
				esc_attr( home_url( '/' ) . 'categoria/' . $category->category_nicename . '/' ),
				esc_html( $category->cat_name )
			);
		}
		?>
	</select>
	<button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="19.462" height="19.463" viewBox="0 0 19.462 19.463">
			<path id="search" d="M6.3,14.529a.838.838,0,0,0,.11.007.811.811,0,0,0,.108-1.614,5.68,5.68,0,1,1,2.951-.38.811.811,0,1,0,.618,1.5A7.3,7.3,0,0,0,11.853,13l6.224,6.224a.811.811,0,0,0,1.147-1.147L13,11.856a7.3,7.3,0,1,0-6.7,2.673Zm0,0" transform="translate(0 0)"/>
		</svg>
	</button>
	<input type="hidden" name="post_type" value="product" />
</form>