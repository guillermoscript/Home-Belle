<?php
/**
 * The Template for displaying empty wishlist.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist-empty.php.
 *
 * @version             1.21.11
 * @package           TInvWishlist\Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="tinv-wishlist woocommerce">
	<?php do_action( 'tinvwl_before_wishlist', $wishlist ); ?>
	<?php if ( function_exists( 'wc_print_notices' ) && isset( WC()->session ) && false) {
		wc_print_notices();
	} ?>
	<!-- <p class="cart-empty">
		<?php if ( get_current_user_id() === $wishlist['author'] ) { ?>
			<?php // esc_html_e( 'Your Wishlist is currently empty.', 'ti-woocommerce-wishlist' ); ?>
		<?php } else { ?>
			<?php // esc_html_e( 'Wishlist is currently empty.', 'ti-woocommerce-wishlist' ); ?>
		<?php } ?>
	</p> -->

	<?php do_action( 'tinvwl_wishlist_is_empty' ); ?>

	<p class="return-to-shop" style="width: 100%;">No tienes elementos guardados en este momento.</p>
</div>
