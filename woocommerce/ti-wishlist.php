<?php
/**
 * The Template for displaying wishlist if a current user is owner.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist.php.
 *
 * @version             1.21.5
 * @package           TInvWishlist\Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
wp_enqueue_script( 'tinvwl' );
?>
<div class="tinv-wishlist woocommerce tinv-wishlist-clear">
	<?php do_action( 'tinvwl_before_wishlist', $wishlist ); ?>
	<?php if ( function_exists( 'wc_print_notices' ) && isset( WC()->session ) ) {
		wc_print_notices();
	} ?>
	<?php
	$wl_paged = get_query_var( 'wl_paged' );
	$form_url = tinv_url_wishlist( $wishlist['share_key'], $wl_paged, true );
	?>
	<form action="<?php echo esc_url( $form_url ); ?>" method="post" autocomplete="off">
		<?php do_action( 'tinvwl_before_wishlist_table', $wishlist ); ?>
		<div class="shop_table tinvwl-table-manage-list">
			<ul>
			<?php do_action( 'tinvwl_wishlist_contents_before' ); ?>
			<?php
			global $product, $post;
			// store global product data.
			$_product_tmp = $product;
			// store global post data.
			$_post_tmp = $post;
			foreach ( $products as $wl_product ) {
				if ( empty( $wl_product['data'] ) ) {
					continue;
				}
				// override global product data.
				$product = apply_filters( 'tinvwl_wishlist_item', $wl_product['data'] );
				// override global post data.
				$post = get_post( $product->get_id() );
				unset( $wl_product['data'] );
				if ( $wl_product['quantity'] > 0 && apply_filters( 'tinvwl_wishlist_item_visible', true, $wl_product, $product ) ) {
					$product_url = apply_filters( 'tinvwl_wishlist_item_url', $product->get_permalink(), $wl_product, $product );
					do_action( 'tinvwl_wishlist_row_before', $wl_product, $product );
					?>
					<li class="<?php echo esc_attr( apply_filters( 'tinvwl_wishlist_item_class', 'wishlist_item', $wl_product, $product ) ); ?>">
					<div class="_product">
						<div class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'tinvwl_wishlist_item_thumbnail', $product->get_image(), $wl_product, $product );

							if ( ! $product->is_visible() ) {
								echo $thumbnail; // WPCS: xss ok.
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_url ), $thumbnail ); // WPCS: xss ok.
							}
							?>
						</div>
						<div class="product-name">
							<?php
							if ( ! $product->is_visible() ) {
								echo apply_filters( 'tinvwl_wishlist_item_name', is_callable( array(
												$product,
												'get_name'
										) ) ? $product->get_name() : $product->get_title(), $wl_product, $product ) . '&nbsp;'; // WPCS: xss ok.
							} else {
								echo apply_filters( 'tinvwl_wishlist_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_url ), is_callable( array(
										$product,
										'get_name'
								) ) ? $product->get_name() : $product->get_title() ), $wl_product, $product ); // WPCS: xss ok.
							}

							echo apply_filters( 'tinvwl_wishlist_item_meta_data', tinv_wishlist_get_item_data( $product, $wl_product ), $wl_product, $product ); // WPCS: xss ok.
							?>
							<div class="product-price">
								<?php
								echo apply_filters( 'tinvwl_wishlist_item_price', $product->get_price_html(), $wl_product, $product ); // WPCS: xss ok.
								?>
							</div>
						</div>
						<div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						
						</div>
						<div class="product-price">
							<?php
							echo apply_filters( 'tinvwl_wishlist_item_price', $product->get_price_html(), $wl_product, $product ); // WPCS: xss ok.
							?>
						</div>
					</div>
					<div class="_actions">
						<div class="product-remove">
							<a type="submit" name="tinvwl-remove"
									data-type="wishlist-remove"
									data-value="<?php echo esc_attr( $wl_product['ID'] ); ?>"
									data-product_id="<?php echo esc_attr( $wl_product['product_id'] ); ?>"
									data-variation_id="<?php echo esc_attr( $wl_product['variation_id'] ); ?>"
									title="<?php _e( 'remove', 'ti-woocommerce-wishlist' ) ?>">
									Eliminar
							</a>
						</div>
						<div class="product-action">
							<?php
							if ( apply_filters( 'tinvwl_wishlist_item_action_add_to_cart', $wishlist_table_row['add_to_cart'], $wl_product, $product ) ) {
								?>
								<a class="alt" name="tinvwl-add-to-cart"
										data-type="wishlist"
										data-value="<?php echo esc_attr( $wl_product['ID'] ); ?>"
										title="<?php echo esc_html( apply_filters( 'tinvwl_wishlist_item_add_to_cart', $wishlist_table_row['text_add_to_cart'], $wl_product, $product ) ); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="32.653" height="19" viewBox="0 0 32.653 19"><defs><style>.cls-1 {font-size: 15px;font-family: Montserrat-Bold, Montserrat;font-weight: 700;}</style></defs><g id="Group_202" data-name="Group 202" transform="translate(-567 -545)"><path id="shopping-cart" d="M18.845,24.618H3.479l-.228-2.545a.807.807,0,0,0-.8-.735H.807a.807.807,0,0,0,0,1.614h.9l.928,10.342A3.105,3.105,0,0,0,3.8,35.4a2.446,2.446,0,1,0,4.228.673h4.417a2.447,2.447,0,1,0,2.3-1.614H5.732a1.5,1.5,0,0,1-1.378-.909l12.9-.758a.807.807,0,0,0,.736-.61l1.64-6.559A.807.807,0,0,0,18.845,24.618ZM5.727,37.736a.833.833,0,1,1,.833-.833A.834.834,0,0,1,5.727,37.736Zm9.019,0a.833.833,0,1,1,.833-.833A.834.834,0,0,1,14.746,37.736Zm1.82-6.523-12.429.73-.513-5.712H17.812Z" transform="translate(580 523.662)"></path><text id="_" data-name="+" class="cls-1" transform="translate(572 560)"><tspan x="-4.492" y="0">+</tspan></text></g></svg>
								</a>
							<?php } elseif ( apply_filters( 'tinvwl_wishlist_item_action_default_loop_button', $wishlist_table_row['add_to_cart'], $wl_product, $product ) ) {
								woocommerce_template_loop_add_to_cart();
							} ?>
						</div>
					</div>
						<?php if ( isset( $wishlist_table_row['colm_stock'] ) && $wishlist_table_row['colm_stock'] && false ) { ?>
							<div class="product-stock">
								<?php
								$availability = (array) $product->get_availability();
								var_dump($availability);
								if ( ! array_key_exists( 'availability', $availability ) ) {
									$availability['availability'] = '';
								}
								if ( ! array_key_exists( 'class', $availability ) ) {
									$availability['class'] = '';
								}
								$availability_html = empty( $availability['availability'] ) ? '<p class="stock ' . esc_attr( $availability['class'] ) . '"><span><i class="ftinvwl ftinvwl-check"></i></span><span class="tinvwl-txt">' . esc_html__( 'In stock', 'ti-woocommerce-wishlist' ) . '</span></p>' : '<p class="stock ' . esc_attr( $availability['class'] ) . '"><span><i class="ftinvwl ftinvwl-' . ( ( 'out-of-stock' === esc_attr( $availability['class'] ) ? 'times' : 'check' ) ) . '"></i></span><span>' . wp_kses_post( $availability['availability'] ) . '</span></p>';

								echo apply_filters( 'tinvwl_wishlist_item_status', $availability_html, $availability['availability'], $wl_product, $product ); // WPCS: xss ok.
								?>
							</div>
						<?php } ?>
					</li>
					<?php
					do_action( 'tinvwl_wishlist_row_after', $wl_product, $product );
				} // End if().
			} // End foreach().
			// restore global product data.
			$product = $_product_tmp;
			// restore global post data.
			$post = $_post_tmp;
			?>
			<?php do_action( 'tinvwl_wishlist_contents_after' ); ?>
			</ul>
			<ul>
			<li>
				<div colspan="100">
					<?php  // do_action( 'tinvwl_after_wishlist_table', $wishlist ); ?>
					<?php wp_nonce_field( 'tinvwl_wishlist_owner', 'wishlist_nonce' ); ?>
				</div>
			</li>
			</ul>
		</div>
	</form>
	<?php do_action( 'tinvwl_after_wishlist', $wishlist ); ?>
	<div class="tinv-lists-nav tinv-wishlist-clear">
		<?php do_action( 'tinvwl_pagenation_wishlist', $wishlist ); ?>
	</div>
</div>
