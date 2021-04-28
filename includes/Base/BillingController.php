<?php
/**
 * @package  Asia Home Shops
 */
namespace App\Base;

defined( 'ABSPATH' ) || exit;

class BillingController 
{
    public function register()
	{
		add_filter( 'woocommerce_checkout_fields' , array($this,'add_custom_billing_info') ,PHP_INT_MAX);
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this,'display_my_custom_billing_info'), 10, 1 );
        
        // Hook in
        add_filter( 'woocommerce_default_address_fields' , [$this,'custom_override_default_address_fields'] );
        
        // Hook in
        add_filter( 'woocommerce_checkout_fields' , [$this,'custom_override_checkout_fields'] );
        add_filter( 'woocommerce_checkout_fields', [$this,'remove_email'] , PHP_INT_MAX );


        add_filter( 'woocommerce_checkout_fields' , [$this,'custom_override_checkout_fields_ek'], 99 );
        

        add_filter('woocommerce_billing_fields', [$this,'wpb_custom_billing_fields']);

        add_filter( 'woocommerce_order_formatted_billing_address' , [$this,'woo_reorder_billing_fields'], 10, 2 );
        
        
	}

    public function add_custom_billing_info( $fields )
    {
        # code...
        $fields['billing']['codigo_telefono'] = array(
            // 'placeholder' => _x('', 'placeholder', 'woocommerce'),
            'required'    => true,
            'clear'       => false,
            'type'        => 'select',
            'label' => __('Codigo Telefono', 'woocommerce'),
            'priority'        => 90,
            'class'     => array('form-row-first', 'widto-little'),
            'options'     => array(
                "option52" => "🇻🇪+58",
                "option0" => "🇨🇦+1",
                "option1" => "🇺🇸+1",
                "option2" => "🇷🇺+7",
                "option3" => "🇪🇬+20",
                "option4" => "🇿🇦+27",
                "option5" => "🇬🇷+30",
                "option6" => "🇳🇱+31",
                "option7" => "🇧🇪+32",
                "option8" => "🇫🇷+33",
                "option9" => "🇪🇸+34",
                "option10" => "🇭🇺+36",
                "option11" => "🇮🇹+39",
                "option12" => "🇷🇴+40",
                "option13" => "🇨🇭+41",
                "option14" => "🇦🇹+43",
                "option15" => "🇬🇧+44",
                "option16" => "🇬🇬+44",
                "option17" => "🇮🇲+44",
                "option18" => "🇯🇪+44",
                "option19" => "🇩🇰+45",
                "option20" => "🇸🇪+46",
                "option21" => "🇳🇴+47",
                "option22" => "🇸🇯+47",
                "option23" => "🇵🇱+48",
                "option24" => "🇩🇪+49",
                "option25" => "🇵🇪+51",
                "option26" => "🇲🇽+52",
                "option27" => "🇨🇺+53",
                "option28" => "🇦🇷+54",
                "option29" => "🇧🇷+55",
                "option30" => "🇨🇱+56",
                "option31" => "🇨🇴+57",
                "option32" => "🇲🇾+60",
                "option33" => "🇦🇺+61",
                "option34" => "🇨🇨+61",
                "option35" => "🇨🇽+61",
                "option36" => "🇮🇩+62",
                "option37" => "🇵🇭+63",
                "option38" => "🇳🇿+64",
                "option39" => "🇸🇬+65",
                "option40" => "🇹🇭+66",
                "option41" => "🇯🇵+81",
                "option42" => "🇰🇷+82",
                "option43" => "🇻🇳+84",
                "option44" => "🇨🇳+86",
                "option45" => "🇹🇷+90",
                "option46" => "🇮🇳+91",
                "option47" => "🇵🇰+92",
                "option48" => "🇦🇫+93",
                "option49" => "🇱🇰+94",
                "option50" => "🇲🇲+95",
                "option51" => "🇮🇷+98",
            )
        );
        $fields['billing']['codigo_documento'] = array(
            // 'placeholder' => _x('', 'placeholder', 'woocommerce'),
            'required'    => true,
            'clear'       => false,
            'type'        => 'select',
            'label' => __('Codigo Documento', 'woocommerce'),
            'priority'        => 100,
            'class'     => array('form-row-first', 'widto-little'),
            'options'     => array(
                'option_1' => 'V-',
                'option_2' => 'E-',
                'option_3' => 'J-',
            )
        );    
        return $fields;
    }

    public function display_my_custom_billing_info($order){
        echo '<p><strong>'.__('Phone From Checkout Form').':</strong> ' . get_post_meta( $order->get_id(), '_codigo_telefono', true ) . '</p>';
    }


    // Our hooked in function - $fields is passed via the filter!
    function custom_override_checkout_fields( $fields ) {
        // $fields['billing']['billing_company']['class'][0] = 'form-row-first';
        $fields['billing']['billing_country']['class'][1] = 'form-row-first';
        $fields['billing']['billing_phone']['class'][2] = 'form-row-first';
        $fields['billing']['billing_email']['class'][1] = 'form-row-last';
        $fields['billing']['billing_email']['priority'] = 130 ;

        $fields['billing']['envios'] = array(
            'required'    => false,
            'clear'       => false,
            'type'        => 'select',
            'label'       => __('Compañias de envio (Cobro a destino)', 'woocommerce'),
            'priority'    => 140,
            'class'       => array('form-row-wide', 'envios','none'),
            'options'     => array(
                'envio_1' => 'MRW',
                'envio_2' => 'Domesa',
                'envio_3' => 'Zoom',
                'envio_4' => 'Tealca',
            )
        );   
        
        // $fields['shipping']['shipping_company']['class'][0] = 'form-row-first';
        $fields['shipping']['shipping_country']['class'][0] = 'form-row-first';
        $fields['shipping']['shipping_address_1']['class'][0] = 'form-row-first';
        $fields['shipping']['shipping_city']['class'][1] = 'form-row-first';
        $fields['shipping']['shipping_state']['class'][1] = 'form-row-last';

        return $fields;
    }


    // Our hooked in function - $address_fields is passed via the filter!
    function custom_override_default_address_fields( $address_fields ) {
        $address_fields['address_1']['class'][0] = 'form-row-last';

        return $address_fields;
    }

    // remove some fields from billing form
    // ref - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
    function wpb_custom_billing_fields( $fields = array() ) {

        unset($fields['billing_company']);
        return $fields;
    }

    /**
    * This function is used for remove email field from the checkout
    * 
    * @name _custom_checkout_fields
    * @param array $address_fields  array of the address fields
    */
    function remove_email( $address_fields ) {
        if( is_user_logged_in() ) {
            unset( $address_fields['billing']['billing_email'] );
        }
        return $address_fields;
    }
   // Remove some fields from billing form
        // Our hooked in function - $fields is passed via the filter!
        // Get all the fields - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
    function custom_override_checkout_fields_ek( $fields ) {
        unset($fields['shipping']['shipping_company']);
        unset($fields['shipping']['shipping_address_2']);
        unset($fields['shipping']['shipping_postcode']);
        return $fields;
    }

    function woo_reorder_billing_fields( $address, $wc_order ) {

        $address = array(
            'first_name'    => $wc_order->billing_first_name,
            'last_name'     => $wc_order->billing_last_name,
            'company'       => $wc_order->billing_company,
            'country'       => $wc_order->billing_country,
            'state'         => $wc_order->billing_state,
            'city'          => $wc_order->billing_city,
        );

        return $address;
    }
}