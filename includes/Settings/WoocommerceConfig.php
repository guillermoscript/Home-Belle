<?php
/**
 * @package  Asia Home Shops
 */
namespace App\Settings;

defined( 'ABSPATH' ) || exit;

use WC_Shortcode_My_Account;

class WoocommerceConfig
{

    public $icon_account;

	public function __construct()
    {
        $this->icon_account = '<svg class="profile" xmlns="http://www.w3.org/2000/svg" width="19.463" height="19.463" viewBox="0 0 19.463 19.463"><path id="Path_14" data-name="Path 14" d="M15.408,9.731H13.7a5.677,5.677,0,1,0-7.93,0H4.055A4.059,4.059,0,0,0,0,13.786V14.6a.811.811,0,1,0,1.622,0v-.811a2.435,2.435,0,0,1,2.433-2.433H15.408a2.435,2.435,0,0,1,2.433,2.433v4.866a.811.811,0,1,0,1.622,0V13.786A4.059,4.059,0,0,0,15.408,9.731ZM5.677,5.677A4.055,4.055,0,1,1,9.731,9.731,4.059,4.059,0,0,1,5.677,5.677Zm0,0"/><path id="Path_15" data-name="Path 15" d="M.811,338.433a.811.811,0,0,0,.811-.811v-.811a.811.811,0,1,0-1.622,0v.811A.811.811,0,0,0,.811,338.433Zm0,0" transform="translate(0 -318.97)"/></svg>';
        get_theme_mod( 'ocean_woo_add_mobile_mini_cart', false );
        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

        add_action( 'ocean_before_archive_product_add_to_cart_inner', array($this, 'get_star_rating') );

        // Register
        add_action( 'woocommerce_register_form-child', array($this, 'edit_my_account_page_woocommerce'), 15 );
        add_action( 'woocommerce_register_form_start', array($this, 'add_name_woo_account_registration') );
        // add_action( 'woocommerce_register_form-child', array($this, 'wc_register_form_password_repeat') );
        // add_filter( 'woocommerce_registration_errors', array($this, 'registration_errors_validation'), 10,3);
        add_filter( 'woocommerce_registration_errors', array($this, 'validate_privacy_registration'), 10, 3 );
        add_action( 'woocommerce_created_customer',    array($this, 'save_name_fields') );


        add_filter( 'ocean_before_nav',                 array($this, 'form_product_search') );
        add_filter( 'wp_nav_menu_items',                array($this, 'add_loginout_link'), 10, 2 );
        add_action( 'wp_footer',                        array($this , 'menu_account'), 0 );
    }
    public function get_star_rating()
    {
        global $woocommerce, $product;
        $average = $product->get_average_rating();

        echo '<div class="star-rating"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
    }
    public function woocommerce_edit_my_account_page()
    {
        return apply_filters( 'woocommerce_forms_field', array(
            'woocommerce_privacy_policy' => array(
                'type'          => 'checkbox',
                'class'         => array('form-row privacy'),
                'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
                'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
                'required'      => true,
                'label'         => '<span>Al registrarme, declaro que soy mayor de edad y acepto los <a href="">Términos y condiciones y las Políticas de privacidad</a> de Home & Belle.</span>',
            ),
        ) );
    }
    public function edit_my_account_page_woocommerce()
    {
        $fields = $this->woocommerce_edit_my_account_page();
        foreach ( $fields as $key => $field_args ) {
            woocommerce_form_field( $key, $field_args );
        }
    }
    public function add_name_woo_account_registration() {
        ?>
        <p class="form-row form-row-first">
            <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" placeholder="Nombre"/>
        </p>
        <p class="form-row form-row-last">
            <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" placeholder="Apellido"/>
        </p>
        <?php
    }
    public function wc_register_form_password_repeat()
    {
        ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-col">
            <label for="reg_password2"><?php esc_html_e( 'Password Repeat', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password2" id="reg_password2" placeholder="Repetir Contraseña" />
        </p>
        <?php
    }
    public function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email)
    {
        global $woocommerce;
        extract( $_POST );
        if ( strcmp( $password, $password2 ) !== 0 ) {
            return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
        }
        return $reg_errors;
    }
    public function validate_privacy_registration( $errors, $username, $email )
    {
        if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
            $errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: Nombre es requerido!', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
            $errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Apellido es requerido!.', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_country'] ) && empty( $_POST['billing_country'] ) ) {
            $errors->add( 'billing_country_error', __( '<strong>Error</strong>: País es requerido!.', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_address_1'] ) && empty( $_POST['billing_address_1'] ) ) {
            $errors->add( 'billing_address_1_error', __( '<strong>Error</strong>: Dirección de la calle es requerido!.', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_state'] ) && empty( $_POST['billing_state'] ) ) {
            $errors->add( 'billing_state_error', __( '<strong>Error</strong>: Estado es requerido!.', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_city'] ) && empty( $_POST['billing_city'] ) ) {
            $errors->add( 'billing_city_error', __( '<strong>Error</strong>: Ciudad es requerido!.', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_postcode'] ) && empty( $_POST['billing_postcode'] ) ) {
            $errors->add( 'billing_postcode_error', __( '<strong>Error</strong>: Código postal es requerido!.', 'woocommerce' ) );
        }
        if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
            $errors->add( 'billing_phone_error', __( '<strong>Error</strong>: Teléfono es requerido!.', 'woocommerce' ) );
        }
        if ( ! is_checkout() ) {
            if ( ! (int) isset( $_POST['woocommerce_privacy_policy'] ) ) {
                $errors->add( 'woocommerce_privacy_policy_error', __( 'Debe de aceptar los Terminos y Condiciones y Politicas de Privacidad', 'woocommerce' ) );
            }
        }
        return $errors;
    }

    public function save_name_fields( $customer_id )
    {
        if ( isset( $_POST['billing_first_name'] ) ) {
            update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
            update_user_meta( $customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']) );
        }
        if ( isset( $_POST['billing_last_name'] ) ) {
            update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
            update_user_meta( $customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']) );
        }
        if ( isset( $_POST['billing_country'] ) ) {
            update_user_meta( $customer_id, 'billing_country', sanitize_text_field( $_POST['billing_country'] ) );
        }
        if ( isset( $_POST['billing_address_1'] ) ) {
            update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
        }
        if ( isset( $_POST['billing_address_2'] ) ) {
            update_user_meta( $customer_id, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2'] ) );
        }
        if ( isset( $_POST['billing_state'] ) ) {
            update_user_meta( $customer_id, 'billing_state', sanitize_text_field( $_POST['billing_state'] ) );
        }
        if ( isset( $_POST['billing_city'] ) ) {
            update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );
        }
        if ( isset( $_POST['billing_postcode'] ) ) {
            update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
        }
        if ( isset( $_POST['billing_phone'] ) ) {
            update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
        }
    }
    public function form_product_search()
    {
        return '<div id="header_product_search">' . get_product_search_form() . '</div>';
    }
    public function add_loginout_link( $items, $args )
    {
        if( $args->theme_location === 'main_menu' ) {
            $items .= '<li class="menu-item"><a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '"class="menu_account">' . $this->icon_account . '</a></li>';
        }

        if( $args->theme_location === 'mobile_menu' ) {
            $current_user = wp_get_current_user();
            $first_name = $current_user->first_name;
            $last_name = $current_user->last_name;
            $subcats = array();
            ?>
                <div class="customer-profile">
                    <div class="customer-name">
                        <div class="account-menu-icon"><?php echo ( !is_user_logged_in() )? $this->icon_account : esc_html( $first_name[0] . $last_name[0] ) ?></div><b>Hola, <?php echo esc_html( ( !is_user_logged_in() )? 'Identifícate' : $first_name . ' ' . $last_name[0] . '.' ); ?></b>
                        </div>
                    </div>
                <div class="hmenu-content">
                    <ul class="hmenu" data-id="0">
                        <li><div class="menu-item menu_title">Buscar Por Departamento</div></li>
                        <?php
                        foreach($this->category_has_children() as $cat) {
                            $cat_children = $this->category_has_children( $cat->cat_ID );
                            $link = get_term_link( (int)$cat->cat_ID, 'product_cat' );
                            if( !empty( $cat_children ) ) {
                                $subcats[] = [ 'slug' => $cat->slug, 'cat' => $cat_children];
                            }
                            ?>
                            <li><a class="menu-item<?php if( !empty( $cat_children ) ) { echo esc_attr( ' menu-item-submenu' ); } ?>" href="<?php if( empty( $cat_children ) ) { echo esc_attr( $link ); } ?>" data-id="<?php echo esc_attr( count($subcats) ) ?>">
                                <div><?php echo esc_html( $cat->name ); ?></div>
                                <?php if( !empty( $cat_children ) ): ?>
                                    <i class="fas fa-chevron-right"></i>
                                <?php endif; ?>
                            </a></li>
                            <?php
                        }
                        ?>
                        <li class="menu_separator"></li>
                        <li><div class="menu-item menu_title">Ayuda Y Configuración</div></li>
                        <li><a class="menu-item menu-item-login" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) ?>" class="menu_login"><span>Tu Cuenta</span></a></li>
                        <li><a class="menu-item" href="#footer">Contáctanos</a></li>
                        <li><a class="menu-item" href="#">Términos y Condiciones</a></li>
                        <li><a class="menu-item" href="#">Política de Privacidad</a></li>
                    </ul>
                    <?php
                    foreach($subcats as $index => $cats):
                        ?>
                        <ul class="hmenu hmenu-right" data-id="<?php echo esc_attr( $index + 1 ) ?>">
                        <li><a class="menu-item menu_title" href="">
                            <i class="fas fa-arrow-left"></i>
                            <div>Menú Principal</div>
                        </a></li>
                            <?php
                            foreach($cats['cat'] as $cat):
                                $link = get_term_link( (int)$cat->cat_ID, 'product_cat' );
                                ?>
                                <li><a class="menu-item" href="<?php echo esc_attr( $link ); ?>">
                                    <div><?php echo esc_html( $cat->name ); ?></div>
                                </a></li>
                                <?php
                            endforeach;
                            ?>
                        </ul>
                        <?php
                    endforeach;
                    ?>
                </div>
            <?php
        }
        
        // if (!is_user_logged_in() && $args->theme_location == 'main_menu') {
        //     $items .= '<li class="menu-item"><a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '?signup=register" class="menu_register"><span>Crea tu cuenta</span></a></li>';
        //     $items .= '<li class="menu-item menu-item-login"><a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '" class="menu_login"><span>Ingresa</span></a></li>';
        // } else {
        //     $items .= '<li class="menu-item current-menu-parent menu-item-has-children dropdown" title="' . esc_attr( $current_user->display_name ) . '"><a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '"class="menu_login"><svg id="profile" xmlns="http://www.w3.org/2000/svg" width="19.463" height="19.463" viewBox="0 0 19.463 19.463"><path id="Path_14" data-name="Path 14" d="M15.408,9.731H13.7a5.677,5.677,0,1,0-7.93,0H4.055A4.059,4.059,0,0,0,0,13.786V14.6a.811.811,0,1,0,1.622,0v-.811a2.435,2.435,0,0,1,2.433-2.433H15.408a2.435,2.435,0,0,1,2.433,2.433v4.866a.811.811,0,1,0,1.622,0V13.786A4.059,4.059,0,0,0,15.408,9.731ZM5.677,5.677A4.055,4.055,0,1,1,9.731,9.731,4.059,4.059,0,0,1,5.677,5.677Zm0,0"/><path id="Path_15" data-name="Path 15" d="M.811,338.433a.811.811,0,0,0,.811-.811v-.811a.811.811,0,1,0-1.622,0v.811A.811.811,0,0,0,.811,338.433Zm0,0" transform="translate(0 -318.97)"/></svg><span>' . esc_html( $current_user->display_name ) . '</span></a><ul class="sub-menu">';
        return $items;
    }
    public function category_has_children( $term_id = 0 )
    {
        $children = get_categories( array( 
            'parent'      => $term_id,
            'taxonomy'      => 'product_cat',
            'hide_empty'    => true,    
        ) );
        return ( $children );
    }
    public function menu_account ()
    {
        $current_user = wp_get_current_user();
        $first_name = $current_user->first_name;
        $last_name = $current_user->last_name;
        $menu_lists = wc_get_account_menu_items();
        ?>
        <div id="sidr-account" class="account-right">
            <div class="customer-profile"><div class="customer-name"><div class="account-menu-icon"><?php echo ( !is_user_logged_in() )? $this->icon_account : esc_html( $first_name[0] . $last_name[0] ) ?></div><b>Hola, <?php echo esc_html( ( !is_user_logged_in() )? 'Identifícate' : $first_name . ' ' . $last_name[0] . '.' ); ?></b></div></div>
            <div class="hmenu-content">
                <ul class="hmenu">
                    <?php
                    if( is_user_logged_in() ){
                        foreach($menu_lists as $key => $menu ) :
                            ?> <li><a class="menu-item" href="<?php echo esc_url( trailingslashit( wc_get_account_endpoint_url( $key ) ) ) ?>"><?php echo esc_html( $menu ) ?></a></li> <?php
                        endforeach;
                    } else {
                        ?>
                        <li><a class="menu-item menu-item-login" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) ?>" class="menu_login"><span>Identifícate</span></a></li>
                        <li><a class="menu-item" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) . '?signup=register' ) ?>" class="menu_register"><span>Crea tu cuenta</span></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }
}