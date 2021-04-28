<?php 
/**
 * @package  Asia Home Shops
 */
namespace App\Base;

defined( 'ABSPATH' ) || exit;

class Enqueue 
{
	public function register()
	{
		add_action( 'wp_enqueue_scripts',  			array( $this, 'enqueue_styles' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', 			array( $this, 'enqueue_scripts' ), PHP_INT_MAX );

		add_action( 'wp_enqueue_scripts', 			array( $this, 'load_logo' ), PHP_INT_MAX );

		add_action( 'wp_footer', 					array( $this, 'ajax_fetch' ), PHP_INT_MAX );
	}
	public function enqueue_styles()
    {
		$theme   = wp_get_theme( 'OceanWP' );
		$version = $theme->get( 'Version' );
		wp_enqueue_style( 'child-select2', home_url('/') . 'wp-content/plugins/woocommerce/assets/css/select2.css', array( 'oceanwp-style' ), $version );
		wp_enqueue_style( 'child-style_custom', get_stylesheet_directory_uri() . '/assets/css/style_custom.css', array( 'oceanwp-style' ), $version );
		if(is_front_page()){
			wp_enqueue_style( 'child-landing', get_stylesheet_directory_uri() . '/assets/css/landing.css', array( 'oceanwp-style' ), $version );
			wp_enqueue_style( 'child-swiper', 'https://unpkg.com/swiper@6.3.5/swiper-bundle.min.css', array( 'oceanwp-style' ), $version );
		}
		if(is_archive()){
			wp_enqueue_style( 'child-shop', get_stylesheet_directory_uri() . '/assets/css/shop.css', array( 'oceanwp-style' ), $version );
		}
		if(is_product()){
			wp_enqueue_style( 'child-product', get_stylesheet_directory_uri() . '/assets/css/product.css', array( 'oceanwp-style' ), $version );
			wp_enqueue_style( 'child-swiper', 'https://unpkg.com/swiper@6.3.5/swiper-bundle.min.css', array( 'oceanwp-style' ), $version );
		}
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if(is_cart() || $actual_link === home_url(  ) . '/wishlist/'){
			wp_enqueue_style( 'child-cart', get_stylesheet_directory_uri() . '/assets/css/cart.css', array( 'oceanwp-style' ), $version );
		}
		if(is_account_page()){
			wp_enqueue_style( 'child-account', get_stylesheet_directory_uri() . '/assets/css/account.css', array( 'oceanwp-style' ), $version );
		}
		if(is_checkout() && !is_order_received_page()){
			wp_enqueue_style( 'child-checkout', get_stylesheet_directory_uri() . '/assets/css/checkout.css', array( 'oceanwp-style' ), $version );
		}
	}
	public function enqueue_scripts()
    {
        // if ( is_cart() || is_checkout() || is_wc_endpoint_url( 'edit-address' ) ) {
        // wp_enqueue_script( 'wc-city-select', ShippingVzla::plugin_url() . 'shipping-vzla/assets/js/place-select.js', array( 'jquery', 'woocommerce' ), '1.0.0', true );
		// $places = json_encode( WoocommerceSettingShipping::get_places() );
		// wp_localize_script( 'wc-city-select', 'wc_city_select_params', array( 'cities' => $places, 'i18n_select_city_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' ) ) );
		// }
		$theme   = wp_get_theme( 'OceanWP' );
		$version = $theme->get( 'Version' );
		wp_enqueue_script( 'child-script_Select2', home_url('/') . 'wp-content/plugins/woocommerce/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), '', true );	
		wp_enqueue_script( 'child-script_custom', get_stylesheet_directory_uri() . '/assets/js/script_custom.js', array( 'jquery' ), $version, true );
		if(is_checkout() || is_account_page()){
			wp_enqueue_script( 'child-jquery_validate', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array( 'jquery' ), $version, true );	
			wp_enqueue_script( 'child-additional_methods', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'jquery' ), $version, true );	
		}
		if(is_front_page()){
			wp_enqueue_script( 'child-script_landing', get_stylesheet_directory_uri() . '/assets/js/landing.js', array( 'jquery' ), $version, true );	
			wp_enqueue_script( 'child-script_swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array( 'jquery' ), $version, true );	
		}
		if(is_product()){
			wp_enqueue_script( 'child-script_swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array( 'jquery' ), $version, true );	
			wp_enqueue_script( 'child-product', get_stylesheet_directory_uri() . '/assets/js/product.js', array( 'jquery' ), $version, true );
		}
		// if(is_archive()){
			// 	wp_enqueue_script( 'child-script_shop', get_stylesheet_directory_uri() . '/assets/js/shop.js', array( 'jquery' ), $version, true );	
		// 	wp_enqueue_script( 'child-script_Select2', home_url('/') . 'wp-content/plugins/woocommerce/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), $version, true );	
		// }
		if(is_cart()){
			wp_enqueue_script( 'child-script_cart', get_stylesheet_directory_uri() . '/assets/js/cart.js', array( 'jquery' ), $version, true );	
			wp_localize_script( 'child-script_cart', 'cart_ajax', array(
			    'url'    => admin_url( 'admin-ajax.php' ),
				'action' => 'move_wishlist'
			) );
		}
		if ( is_checkout() && ! ( is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' ) ) ) {
			wp_enqueue_script( 'child-script_checkout', get_stylesheet_directory_uri() . '/assets/js/checkout.js', array( 'jquery' ), $version, true );	
		}
		if(is_account_page()){
			wp_enqueue_script( 'wc-single-product', get_stylesheet_directory_uri() . '/assets/js/single-product.min.js', array( 'jquery' ), $version, true );	
			wp_enqueue_script( 'child-script_account', get_stylesheet_directory_uri() . '/assets/js/account.js', array( 'jquery' ), $version, true );
			wp_localize_script( 'child-script_account', 'register_ajax', array(
				'url'    => admin_url( 'admin-ajax.php' ),
				'action' => 'validation_register'
			) );
		}
		if( is_shop() || preg_match('/category/', $_SERVER['REQUEST_URI']) ) {
			wp_enqueue_script( 'wc-shop-cust', get_stylesheet_directory_uri() . '/assets/js/shop.js', array( 'jquery' ), $version, true );	
		}
	}
	public function load_logo()
	{
		// if(is_account_page()){
			add_action('ocean_after_logo_img', array( $this, 'logo_white' ));
		// }
	}

	public function logo_white()
	{
		?>
		<a href="<?php echo home_url( '/' ) ?>" class="custom-logo-link_white" rel="home">
			<img id="image_header" src="<?php echo home_url( '/' ); ?>wp-content/uploads/2021/04/Logo-Home-Belle2.png" alt="Logo Blanco">
		</a>
		<?php
	}
	public function ajax_fetch()
	{
		?>
		<script type="text/javascript">
		jQuery('#woocommerce-product-search-field-0').focusout(function() { jQuery('#preview_search').hide() })
		jQuery('#woocommerce-product-search-field-0').keyup(function (e) { fetchResults() })
		jQuery('#woocommerce-product-search-field-0').focusin(function() { jQuery('#preview_search').show() })
		function fetchResults(){
			var keyword = jQuery('#woocommerce-product-search-field-0').val();
			if(keyword === ""){
				jQuery('#preview_search').html("");
				if(jQuery('#preview_search').hasClass('preview_show')){
					jQuery('#preview_search').removeClass('preview_show')
				}
			} else {
				jQuery('#preview_search').html( 
					`<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="150px" height="50px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
						<circle cx="84" cy="50" r="10" fill="#6a6a6a">
							<animate attributeName="r" repeatCount="indefinite" dur="0.5s" calcMode="spline" keyTimes="0;1" values="10;0" keySplines="0 0.5 0.5 1" begin="0s"></animate>
							<animate attributeName="fill" repeatCount="indefinite" dur="2s" calcMode="discrete" keyTimes="0;0.25;0.5;0.75;1" values="#6a6a6a;#e2e2e2;#bdbdbd;#979797;#6a6a6a" begin="0s"></animate>
						</circle><circle cx="16" cy="50" r="10" fill="#6a6a6a">
							<animate attributeName="r" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"></animate>
							<animate attributeName="cx" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="0s"></animate>
						</circle><circle cx="50" cy="50" r="10" fill="#979797">
							<animate attributeName="r" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"></animate>
							<animate attributeName="cx" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.5s"></animate>
						</circle><circle cx="84" cy="50" r="10" fill="#bdbdbd">
							<animate attributeName="r" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-1s"></animate>
							<animate attributeName="cx" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-1s"></animate>
						</circle><circle cx="16" cy="50" r="10" fill="#e2e2e2">
							<animate attributeName="r" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="0;0;10;10;10" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-1.5s"></animate>
							<animate attributeName="cx" repeatCount="indefinite" dur="2s" calcMode="spline" keyTimes="0;0.25;0.5;0.75;1" values="16;16;16;50;84" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" begin="-1.5s"></animate>
						</circle>
					</svg>` );
				jQuery('#preview_search').addClass('preview_show')
				jQuery.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'post',
					data: { action: 'data_fetch', keyword: keyword  },
					success: function(data) {
						jQuery('#preview_search').html( data );
					}
				});
			}
		}
		</script>
		<?php
	}
}