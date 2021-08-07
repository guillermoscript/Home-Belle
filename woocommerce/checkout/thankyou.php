<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );?>

<?php if ( $order->has_status( 'failed' ) ) : ?>
	
	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
	
	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>
		
		<?php else : ?>
			<section class="woocommerce-order-overview woocommerce-thankyou-order-details order_details order-contain">
				<div class="order_details-title">
					<h2>Información de la Compra</h2>
				<span>Orden - #<?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p class="woocommerce-order-overview__date date">
					<span><?php esc_html_e( 'Date', 'woocommerce' ); ?></span>
					<span><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</p>
				</div>
				<div class="order_details-content">
					<h3>INFORMACIÓN DEL CLIENTE</h3>
					<div class="order_details-col">
						<p class="woocommerce-order-overview__name name">
						</p>
							<span><?php echo esc_html( 'Nombre del Cliente:' ); ?></span>
							<span><?php
							$user = $order->get_user();
							echo esc_html( $user->first_name . ' ' . $user->last_name );
							?></span>
						<?php if ( $order->get_billing_phone() ) : ?>
							<p class="woocommerce-customer-details--phone">
								<span><span><?php echo esc_html( 'Número de TLF: ' ); ?></span></span>
								<span><?php echo esc_html( $order->get_billing_phone() ); ?></span>
							</p>
						<?php endif; ?>
						<div class="woocommerce-order-overview__address adress">
							<span><?php echo esc_html( 'Dirección de Envío:' ); ?></span>
							<address><?php echo esc_html( 
								$order->get_formatted_billing_full_name() . ', ' .
								$order->get_billing_country() . ', ' .
								$order->get_billing_state() . ', ' .
								$order->get_billing_city() . ', ' .
								$order->get_billing_address_1() . ', ' .
								$order->get_billing_address_2() . ', ' .
								$order->get_billing_postcode() . ', ' .
								$order->get_billing_phone()
							); ?></address>
						</div>
					</div>
					<div class="order_details-col">
						<!-- <p class="woocommerce-order-overview__order order">
							<span><?php esc_html_e( 'Order number', 'woocommerce' ); ?></span>
							<span>#<?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</p> -->
						
					</div>
				</div>
				<?php 
					/**
					 * Action hook fired after the order details.
					 *
					 * @since 4.4.0
					 * @param WC_Order $order Order data.
					 */
					$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
					do_action( 'woocommerce_after_order_details', $order );

					if ( $show_customer_details ) {
						wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
					}

				?>
			</section>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
