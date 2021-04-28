<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

use App\Base\EmailReciber;

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */

function oceanwp_child_enqueue_parent_style() {
	$theme   = wp_get_theme( 'OceanWP' );
	$version = $theme->get( 'Version' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'oceanwp-style' ), $version );
	
}
add_action( 'wp_enqueue_scripts', 'oceanwp_child_enqueue_parent_style' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( class_exists( 'App\\Init' ) ) {
	App\Init::registerServices();
}

if ( ! function_exists( 'oceanwp_wcmenucart_m	enu_item' ) ) {
	function oceanwp_wcmenucart_menu_item() {
		// Classes
		$classes = array( 'wcmenucart' );
		// Hide items if "hide if empty cart" is checked
		if ( true == get_theme_mod( 'ocean_woo_menu_icon_hide_if_empty', false )
			&& ! WC()->cart->cart_contents_count > 0 ) {
			$classes[] = 'wcmenucart-hide';
		}
		// Turn classes into space seperated string
		$classes = implode( ' ', $classes );
		// Return if is in the Elementor edit mode, to avoid error
		if ( OCEANWP_ELEMENTOR_ACTIVE
			&& \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}
		// Vars
		$icon_style   = get_theme_mod( 'ocean_woo_menu_icon_style', 'drop_down' );
		$custom_link  = get_theme_mod( 'ocean_woo_menu_icon_custom_link' );
		$cart_count = ( (int)WC()->cart->get_cart_contents_count() >= 100 )? '99+' : WC()->cart->get_cart_contents_count();
		// URL
		if ( 'custom_link' == $icon_style && $custom_link ) {
			$url = esc_url( $custom_link );
		} else {
			$cart_id = wc_get_page_id( 'cart' );
			if ( function_exists( 'icl_object_id' ) ) {
				$cart_id = icl_object_id( $cart_id, 'page' );
			}
			$url = get_permalink( $cart_id );
		}
		// Cart total
		$display = get_theme_mod( 'ocean_woo_menu_icon_display', 'icon_count' );
		if ( 'icon_total' == $display ) {
			$cart_extra = WC()->cart->get_total();
			$cart_extra = str_replace( 'amount', 'wcmenucart-details', $cart_extra );
		} elseif ( 'icon_count' == $display ) {
			$cart_extra = '<span class="wcmenucart-details count">'.$cart_count .'</span>';
		} elseif ( 'icon_count_total' == $display ) {
			$cart_extra = '<span class="wcmenucart-details count">'.$cart_count .'</span>';
			$cart_total = WC()->cart->get_total();
			$cart_extra .= str_replace( 'amount', 'wcmenucart-details', $cart_total );
		} else {
			$cart_extra = '';
		}
		// If bag style
		if ( 'yes' == get_theme_mod( 'ocean_woo_menu_bag_style', 'no' ) ) { ?>
			<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $classes ); ?>">
				<?php
				if ( true == get_theme_mod( 'ocean_woo_menu_bag_style_total', false ) ) { ?>
					<span class="wcmenucart-total"><?php echo WC()->cart->get_total(); ?></span>
				<?php } ?>
				<span class="wcmenucart-cart-icon">
					<span class="wcmenucart-count"><?php echo $cart_count ?></span>
				</span>
			</a>
		<?php } else { ?>
			<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $classes ); ?>">
				<span class="wcmenucart-count">
				<svg xmlns="http://www.w3.org/2000/svg" width="25.917" height="23.754" viewBox="0 0 25.917 23.754"><path id="shopping-cart" d="M24.852,25.663H4.588l-.3-3.356a1.064,1.064,0,0,0-1.06-.969H1.064a1.064,1.064,0,0,0,0,2.129H2.254L3.478,37.105A4.1,4.1,0,0,0,5.011,39.88a3.226,3.226,0,1,0,5.575.887h5.825a3.227,3.227,0,1,0,3.034-2.129H7.559a1.977,1.977,0,0,1-1.817-1.2l17.01-1a1.064,1.064,0,0,0,.97-.8l2.163-8.65a1.065,1.065,0,0,0-1.033-1.322Zm-17.3,17.3a1.1,1.1,0,1,1,1.1-1.1A1.1,1.1,0,0,1,7.552,42.963Zm11.894,0a1.1,1.1,0,1,1,1.1-1.1A1.1,1.1,0,0,1,19.446,42.963Zm2.4-8.6-16.391.963-.676-7.532H23.489Z" transform="translate(0 -21.338)"/></svg>	
				<?php echo wp_kses_post( $cart_extra ); ?>
			</span>
			</a>
		<?php
		}
	}
}


// add_action( 'woocommerce_before_cart', 'image_header', 0 );

function image_header() {
	?>
		<img id="image_header" src="<?php echo home_url( '/' ); ?>wp-content/themes/home&belle/assets/img/cart_header.jpg" alt="Cabecera de imagen">
	<?php
}

function my_custom_price_format( $formatted_price, $price, $args ) {

    // The currency conversion custom calculation function
    $price_usd = convert_idr_to_usd_cart($price);

    // the currency symbol for US dollars
    $currency = '';
    $currency_symbol = get_woocommerce_currency_symbol( $currency );
    $price_usd = 'Bs '.$price_usd; // adding currency symbol

    // The USD formatted price
    $formatted_price_usd = "<span class='price-vef'> $price_usd</span>";

    // Return both formatted currencies
    return $formatted_price_usd;
	// return $formatted_price . $formatted_price_usd;
	// https://stackoverflow.com/questions/45318117/calculations-displaying-2-currencies-in-woocommerce-cart-prices
}

function convert_idr_to_usd_cart( $price ){
	$convertion_rate = get_option( 'asiahs_currency' );
    $new_price = $price * $convertion_rate;
    return number_format($new_price, 2, ',', '.');
}

add_action('wp_ajax_validation_register' , 'validation_register');
add_action('wp_ajax_nopriv_validation_register','validation_register');

function validation_register() {
	// $nonce = sanitize_text_field( $_REQUEST['nonce'] );  
	// if ( ! wp_verify_nonce( $nonce, 'woocommerce-login' ) ) {
		// 	die ();
		// }
	$name = sanitize_text_field( $_REQUEST['name'] );  
	switch ($name) {
		case 'username':
			$user = sanitize_user($_REQUEST['value']);
			$test = username_exists($user) ? 'yes' : 'no';
		break;
		case 'email':
			$email = sanitize_email($_REQUEST['value']);
			$test = email_exists($email) ? 'yes' : 'no';
		break;
	}
	
	echo $test;

	wp_die();
}

add_action( 'woocommerce_before_main_content', 'image_header_shop' );

function image_header_shop(){
	if(is_archive()):
		?>
			<img id="image_header" src="<?php echo home_url( '/' ); ?>wp-content/themes/home&belle/assets/img/shop_header.jpg" alt="Cabecera de imagen">
		<?php
	endif;
}

add_action( 'woocommerce_sidebar', 'subscribe_form' );

function subscribe_form() {
	if(is_archive()):
		?>
		<section id="subscribe">
			<div class="_container">
				<h3>Mantente Informado</h3>
				<p>Suscríbete para recibir información actualizada de nuestros productos.</p>
				<form action="">
					<div>
						<label class="subscribe-text" for="subscribe_field">Buscar por:</label>
						<input type="email" id="subscribe_field" class="subscribefield"  name="subscribe_field" autocomplete="" placeholder="Escribir correo electrónico">
					</div>
					<button type="submit" value="subscribe">Suscribir</button>
				</form>
			</div>
		</section>
		<?php
	endif;
}

add_shortcode( 'recently_viewed_products', 'bbloomer_recently_viewed_shortcode' );

function bbloomer_recently_viewed_shortcode( $atts ) {

	$shortcode_atts = shortcode_atts( array(
		'limit' => '-1',
	), $atts );

	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

	if ( empty( $viewed_products ) ) return;

	$product_ids = implode( ",", $viewed_products );

	ob_start();
	echo '<h3>Productos Vistos Recientemente</h3>';
	echo do_shortcode("[products ids='$product_ids' limit='$shortcode_atts[limit]' orderby='post__in']");
	
	return ob_get_clean();
}

function custom_track_product_view() {
    if ( ! is_singular( 'product' ) ) return;

    global $post;

    if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );

    if ( in_array( $post->ID, $viewed_products ) ) unset($viewed_products[ array_search($post->ID, $viewed_products) ]);
	
	$viewed_products[] = $post->ID;

    if ( sizeof( $viewed_products ) > 150 ) array_shift( $viewed_products );

    // Store for session only
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}

add_action( 'template_redirect', 'custom_track_product_view', 20 );

add_filter( 'woocommerce_shortcode_products_query', 'woocommerce_shortcode_products_orderby' );
function woocommerce_shortcode_products_orderby( $args ) {
    $standard_array = array('menu_order','title','date','rand','id');
	if( isset( $args['orderby'] ) && !in_array( $args['orderby'], $standard_array ) ) {
		$args['orderby']  = 'post__in'; 
	}

return $args;
}

add_filter( 'woocommerce_cart_item_price', 'bbloomer_change_cart_table_price_display', 30, 3 );

function bbloomer_change_cart_table_price_display( $price, $values, $cart_item_key ) {
	$slashed_price = $values['data']->get_price_html();
	$is_on_sale = $values['data']->is_on_sale();
	if ( $is_on_sale ) {
		$price = '<span class="woocommerce-Price-amount amount">';
		$price .= $slashed_price;
		$price .= '</span>';
	};
	if( (int)$values['quantity'] > 1 ) $price .= '<span>c/u</span>';
	return $price;
}


add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){
    $the_query = new WP_Query( array( 'posts_per_page' => 5, 's' => esc_attr( $_POST['keyword'] ), 'post_type' => 'product' ) );
    if( $the_query->have_posts() ) :
        while( $the_query->have_posts() ): $the_query->the_post(); ?>
			<?php $product = new WC_Product(get_the_ID()); ?>
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
				<?php echo $product->get_image(); ?>
				<div class="preview_content">
				<?php if(strlen($product->get_title()) > 50) : ?>
					<p class="preview_title"><?php echo esc_html(substr( $product->get_title(), 0, 50 ) . '...'); ?> </p>
					<?php else: ?>
						<p class="preview_title"><?php echo esc_html($product->get_title()); ?> </p>
				<?php endif; ?>
				<?php echo $product->get_price_html(); ?>
				</div>
			</a>
        <?php endwhile;
		wp_reset_postdata();  
	else: 
		echo '<h3>No se han encontrado resultados.</h3>';
    endif;
    die();
}

function my_custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'my_custom_flush_rewrite_rules' );

if ( ! function_exists( 'woocommerce_quantity_input' ) ) {

	/**
	 * Output the quantity input for add to cart forms.
	 *
	 * @param  array           $args Args for the input.
	 * @param  WC_Product|null $product Product.
	 * @param  boolean         $echo Whether to return or echo|string.
	 *
	 * @return string
	 */
	function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
		if ( is_null( $product ) ) {
			$product = $GLOBALS['product'];
		}

		$defaults = array(
			'input_id'     => uniqid( 'quantity_' ),
			'input_name'   => 'quantity',
			'input_value'  => '1',
			'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
			'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
			'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
			'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
			'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
			'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
			'product_name' => $product ? $product->get_title() : '',
			'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
		);

		$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

		// Apply sanity to min/max args - min cannot be lower than 0.
		$args['min_value'] = max( $args['min_value'], 0 );
		$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : 30;

		// Max cannot be lower than min if defined.
		if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) $args['max_value'] = $args['min_value'];

		if( $args['input_value'] >= 1 && $args['input_value'] <= 12 ) {
			$options = '';
			for ( $count = $args['min_value']; $count <= $args['max_value']; $count = $count + $args['step'] ) {
			// Cart item quantity defined?
				$selected = ( '' !== $args['input_value'] && $args['input_value'] >= 1 && $count == $args['input_value'] )? 'selected' : '';
				$options .= '<option value="' . $count . '"' . $selected . '>' . ( ( is_cart() && (int)$count === 13 )? $count . '+' : $count  ) . '</option>';
				if( is_cart() && (int)$count === 13 ) break;
				// if((int)$product->get_stock_quantity() === (int)$count + 1) break;
			}
			$string = '<div class="quantity qty_select"><select ' . $args['input_id'] . ' name="' . $args['input_name'] . '" class="qty">' . $options . '</select><i class="fas fa-chevron-down"></i></div>';
			if ( $echo ) {
				echo $string;
			} else {
				return $string;
			}
		} else{
			ob_start();
			wc_get_template( 'global/quantity-input.php', $args );
			if ( $echo ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo ob_get_clean();
			} else {
				return ob_get_clean();
			}
		}
		// https://www.businessbloomer.com/woocommerce-change-add-cart-quantity-drop/
	}
}

add_action('wp_ajax_move_wishlist' , 'move_wishlist');
add_action('wp_ajax_nopriv_move_wishlist','move_wishlist');
function move_wishlist() {
	$type = sanitize_text_field( $_REQUEST['type'] );
	if( empty($type) ) echo 'error';

	$value 		  = sanitize_text_field( $_REQUEST['value'] );
	$cart_item    = sanitize_text_field( $_REQUEST['cart_item'] );
	$product_id   = sanitize_text_field( $_REQUEST['product_id'] );
	$variation_id = sanitize_text_field( $_REQUEST['variation_id'] );

// 	$wishlist = tinv_wishlist_get();
// 	$wl_product = new TInvWL_Product(  );
// 	$wl_id = $wl_product->wishlist_id();
// 	
	$wl       = new TInvWL_Wishlist( 'tinvwl' );
	$wishlist = $wl->add_sharekey_default();
	$wl_product      = new TInvWL_Product( $wishlist );
	
	if( $type === 'cart' ){
		$add_wl = $wl_product->add( array(
			'product_id' => $product_id,
			'variation_id' => $variation_id,
			'wishlist_id' => 0,
			'quantity'   => 1,
		) );
		if( $add_wl ) {

			$product_cart_id = WC()->cart->generate_cart_id( $product_id );
			$cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
			if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );

		} else {
			var_dump($add_wl);
		}
	}
	
	if( $type === 'wishlist' ){
		TInvWL_Public_Cart::add( $wishlist, $value, 1 );
	}

	if( $type === 'wishlist-remove' ){
		$wl_product->remove_product_from_wl( $wl_product->wishlist_id(), $product_id, $variation_id );
	}

    // var_dump($test->remove_product_from_wl( $test->wishlist_id(), $test->get_wishlist()[0]['product_id'], $test->get_wishlist()[0]['variation_id'] ));
    //$test->wishlist_id()
    // var_dump($test->get_wishlist()[0]);
	
	wp_die();
}

add_filter('woocommerce_single_product_image_gallery_classes', 'add_class_image_gallery');

function add_class_image_gallery( $args ) {
	$args[] = 'swiper-container';
	return $args;
}


add_action('template_redirect','check_if_logged_in');
function check_if_logged_in()
{
	$pageid = get_option( 'woocommerce_checkout_page_id' );
	if(!is_user_logged_in() && is_page($pageid)){
		$url = add_query_arg(
			'redirect_to',
			get_permalink($pagid),
			site_url('/my-account/') // your my acount url
		);
		wp_redirect($url);
		exit;
	}
	if (is_user_logged_in()) {
		if(is_page(get_option( 'woocommerce_myaccount_page_id' ))) {
			
			$redirect = $_GET['redirect_to'];
			if (isset($redirect)) {
				echo '<script>window.location.href = "'.$redirect.'";</script>';
			}

		}
	}
}

add_filter( 'woocommerce_coupon_error','coupon_error_message_change',10,3 );

function coupon_error_message_change($err, $err_code, $parm )
{
   switch ( $err_code ) {
	case 105:
	/* translators: %s: coupon code */
	$err = sprintf( __( 'Cupon "%s" no existe', 'woocommerce' ), $parm->get_code() );
       break;
 }
return $err;
}

function filter_woocommerce_cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
    // Change text
    $coupon_html = $discount_amount_html . ' <a href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . __( 'X', 'woocommerce' ) . '</a>';

    return $coupon_html;
}
add_filter( 'woocommerce_cart_totals_coupon_html', 'filter_woocommerce_cart_totals_coupon_html', 10, 3 );

// After registration, logout the user and redirect to home page
function custom_registration_redirect() {
    // wp_logout();
    return home_url('/');
}
add_action('woocommerce_registration_redirect', 'custom_registration_redirect', 2);

function my_woo_outofstock_text( $text ) {
	$text = __( 'Agotado', 'oceanwp' );
	return $text;
}
add_filter( 'ocean_woo_outofstock_text', 'my_woo_outofstock_text', 20 );


/**
 * Notify admin when a new customer account is created
 */
// add_action( 'woocommerce_created_customer', 'woocommerce_created_customer_admin_notification' );
// function woocommerce_created_customer_admin_notification( $customer_id ) {
//   wp_send_new_user_notifications( $customer_id, 'admin' );
// }