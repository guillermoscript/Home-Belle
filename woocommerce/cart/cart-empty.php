<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<div id="cc_cart" class="container_custom">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" style="display: flex;flex-direction: column;">
			<h2 style="padding-bottom: 0.5rem;">0 artículos en su carrito</h2>
			<p>¡Adelante, agrega algo!</p>
			<p style="padding-top: 5rem;text-align: center;padding-bottom: 0.5rem;">¿Tiene artículos en su carrito?</p>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button" style="background-color: black;padding: 0.625rem 1.25rem;border-radius: 3px;font-weight: 500;font-size: 0.9375rem;text-transform: initial;color: white !important;margin: 0 auto !important;">Agregar productos</a>
			<div class="actions">
				<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
				<?php do_action( 'woocommerce_cart_actions' ); ?>
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>
			<div class="cart-collaterals">
				<div class="cart_totals">
					<h2>Total</h2>
					<table cellspacing="0" class="shop_table shop_table_responsive">
						<tbody><tr class="cart-subtotal">
							<th>Subtotal</th>
							<td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>0.00</bdi></span></td>
						</tr>
						<tr class="tax-rate tax-rate-ve-iva-16-1">
							<th>IVA 16%</th>
							<td data-title="IVA 16%"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>0.00</span></td>
						</tr>
						<tr class="order-total">
							<th>Total</th>
							<td data-title="Total"><strong><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>0.00</bdi></span></strong> </td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<?php echo do_shortcode( '[ti_wishlistsview]' ); ?>
		</form>
		<div class="cart-collaterals">
			<div class="cart_totals">
				<h2>Total</h2>
				<table cellspacing="0" class="shop_table shop_table_responsive">
					<tbody><tr class="cart-subtotal">
						<th>Subtotal</th>
						<td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>0.00</bdi></span></td>
					</tr>
					<tr class="tax-rate tax-rate-ve-iva-16-1">
						<th>IVA 16%</th>
						<td data-title="IVA 16%"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>0.00</span></td>
					</tr>
					<tr class="order-total">
						<th>Total</th>
						<td data-title="Total"><strong><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>0.00</bdi></span></strong> </td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php endif; ?>
