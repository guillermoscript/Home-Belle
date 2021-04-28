<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
$type_shipping = WC()->session->get( 'type_shipping' );
?>

<div class="shop_table woocommerce-checkout-review-order-table">
	<div class="address_list_product">

		<div class="details_address_shipping">
			<!-- <div class="address_info">
				<h4>Dirección de Envío</h4>
				<address>
				</address>
				<a href="#">Editar</a>
			</div> -->
			<div id="type_shipping_field" data-priority="">
				<?php wc_cart_totals_shipping_html(); ?>
			</div>
		</div>
		
		<ul class="list_product_orden">
			<li class="cart_item_heading">
				<p class="product-image"></p>
				<div class="product-name">Producto</div>
				<p class="product-quantity">Cantidad</p>
				<p class="product-total">Precio</p>
			</li>
			<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );
	
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product = new WC_Product($cart_item['product_id']);
					?>
					<li class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<p class="product-image">
							<?php echo $product->get_image(); ?>
						</p>
						<div class="product-name" data-title="Producto">
							<span title="<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
								<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</div>
						<p class="product-quantity" data-title="Cantidad">
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '<span>&times;</span>&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</p>
						<p class="product-total" data-title="Precio">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</p>
					</li>
					<?php
				}
			}
			do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</ul>
		<div class="links-cont flexx">
			<a href="<?php  echo esc_url(wc_get_page_permalink( 'cart' )); ?>" class="button">Editar Orden</a>
			<a id="continueStepTwo" href="#" class="button">Continuar</a>
		</div>
	</div>

	
</div>
