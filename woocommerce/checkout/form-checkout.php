<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">


	<div class="dropdown-cont">
		<div id="step-1" class="drop-helper">
			<div class="drop-title-cont">

				<div class="drop-number">
					<div class="drop-circle">
						<h2>1</h2>
					</div>
				</div>
				<div class="drop-tilte">
					<h3>Delivery y Opciones</h3>
				</div>
				<ul id="factuara" class="total_orden_payment">
					<div id="total_orden">
						<li class="total-header">
							<p>Facturación</p>
							<p>$</p>
						</li>
						<li class="currency hidden">
							<p>Tasa de cambio</p>
							<p>
								<?php
								$convertion_rate = get_option('asiahs_currency');
								echo esc_html('Bs ' . number_format($convertion_rate, 2, ',', '.'));
								?>
							</p>
						</li>
						<li class="total-bf">
							<p>Total Bs.S</p>
							<p>

							</p>
						</li>
						<!-- <?php add_filter('wc_price', 'my_custom_price_format', 10, 3); ?> -->
						<!-- <li class="cart-subtotal currency hidden">
							<p><?php esc_html_e('Subtotal', 'woocommerce'); ?></p>
							<p><?php wc_cart_totals_subtotal_html(); ?></p>
						</li> -->
						<!-- <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
							<li class="cart-discount currency hidden coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
								<p><?php wc_cart_totals_coupon_label($coupon); ?></p>
								<p><?php wc_cart_totals_coupon_html($coupon); ?></p>
							</li>
						<?php endforeach; ?> -->
						<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
							<li class="woocommerce-shipping-totals shipping currency hidden">
								<?php do_action('woocommerce_review_order_before_shipping'); ?>
								<?php wc_cart_totals_shipping_html(); ?>
								<?php do_action('woocommerce_review_order_after_shipping'); ?>
							</li>
						<?php endif; ?>
						<?php foreach (WC()->cart->get_fees() as $fee) : ?>
							<li class="fee currency hidden">
								<p><?php echo esc_html($fee->name); ?></p>
								<p><?php wc_cart_totals_fee_html($fee); ?></p>
							</li>
						<?php endforeach; ?>
						<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
							<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
								<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited 
								?>
									<li class="tax-rate currency hidden tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
										<p><?php echo esc_html($tax->label); ?></p>
										<p><?php echo wp_kses_post($tax->formatted_amount); ?></p>
									</li>
								<?php endforeach; ?>
							<?php else : ?>
								<li class="tax-total currency hidden">
									<p><?php echo esc_html(WC()->countries->tax_or_vat()); ?></p>
									<p><?php wc_cart_totals_taxes_total_html(); ?></p>
								</li>
							<?php endif; ?>
						<?php endif; ?>
						<?php do_action('woocommerce_review_order_before_order_total'); ?>
						<!-- <li class="order-total currency hidden">
								<p><?php esc_html_e('Total', 'woocommerce'); ?></p>
								<p><?php wc_cart_totals_order_total_html(); ?></p>
							</li> -->
						<?php do_action('woocommerce_review_order_after_order_total'); ?>
						<?php remove_filter('wc_price', 'my_custom_price_format', 10, 3); ?>
						<li class="cart-subtotal">
							<p><?php esc_html_e('Subtotal', 'woocommerce'); ?></p>
							<p><?php wc_cart_totals_subtotal_html(); ?></p>
						</li>

						<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
							<li class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
								<p><?php wc_cart_totals_coupon_label($coupon); ?></p>
								<p><?php wc_cart_totals_coupon_html($coupon); ?></p>
							</li>
						<?php endforeach; ?>

						<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

							<li class="woocommerce-shipping-totals shipping">
								<?php do_action('woocommerce_review_order_before_shipping'); ?>
								<?php wc_cart_totals_shipping_html(); ?>
								<?php do_action('woocommerce_review_order_after_shipping'); ?>
							</li>
						<?php endif; ?>

						<?php foreach (WC()->cart->get_fees() as $fee) : ?>
							<li class="fee">
								<p><?php echo esc_html($fee->name); ?></p>
								<p><?php wc_cart_totals_fee_html($fee); ?></p>
							</li>
						<?php endforeach; ?>

						<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
							<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
								<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited 
								?>
									<li class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
										<p><?php echo esc_html($tax->label); ?></p>
										<p><?php echo wp_kses_post($tax->formatted_amount); ?></p>
									</li>
								<?php endforeach; ?>
							<?php else : ?>
								<li class="tax-total">
									<p><?php echo esc_html(WC()->countries->tax_or_vat()); ?></p>
									<p><?php wc_cart_totals_taxes_total_html(); ?></p>
								</li>
							<?php endif; ?>
						<?php endif; ?>

						<?php do_action('woocommerce_review_order_before_order_total'); ?>

						<li class="order-total">
							<p><?php esc_html_e('Total', 'woocommerce'); ?></p>
							<p><?php wc_cart_totals_order_total_html(); ?></p>
						</li>
					</div>
				</ul>
				<div id="pencil-icon" class="pencil-cont">
					<i class="far fa-edit"></i>
				</div>
			</div>

			<div class="drop-cont-table flexx">
				<!-- <div id="order_review" class="drop-cont-table woocommerce-checkout-review-order"> -->
				<!-- <h3 id="order_review_heading"><?php echo  esc_html('Mi Orden'); ?></h3> -->
				<?php do_action('woocommerce_checkout_order_review'); ?>
				<!-- </div> -->
			</div>
		</div>
	</div>



	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

		<div class="dropdown-cont ">
			<div id="step-2" class="drop-helper drop-disable">
				<div class="drop-title-cont">
					<div class="drop-number">
						<div class="drop-circle">
							<h2>2</h2>
						</div>
					</div>
					<div class="drop-tilte">
						<h3>Informacion De Pago</h3>
					</div>
				</div>

				<div class="drop-cont-table hiddenn">
					<div id="customer_details">
						<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

						<!-- <h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3> -->

						<?php do_action('woocommerce_checkout_before_order_review'); ?>

						<div class="back_overlay"></div>
						<?php do_action('woocommerce_checkout_billing'); ?>

						<?php do_action('woocommerce_checkout_shipping'); ?>
					</div>

					<?php do_action('woocommerce_checkout_after_customer_details'); ?>
				</div>
			</div>
		</div>


	<?php endif; ?>

	<?php do_action('woocommerce_review_order_after_order_total'); ?>

	<div class="dropdown-cont">
		<div id="step-3" class="drop-helper drop-disable">

			<div class="drop-title-cont">
				<div class="drop-number">
					<div class="drop-circle">
						<h2>3</h2>
					</div>
				</div>
				<div class="drop-tilte">
					<h3>Metodo de Pago</h3>
				</div>
			</div>

			<div class="drop-cont-table payment-cont hiddenn">

			</div>
		</div>
	</div>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>